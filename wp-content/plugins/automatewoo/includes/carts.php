<?php

namespace AutomateWoo;

/**
 * Carts management class
 * @class Carts
 */
class Carts {

	/** @var bool - when true cart has been change */
	static $is_changed = false;


	/**
	 * Loaded if abandoned cart is enabled
	 */
	static function init() {
		$self = __CLASS__; /** @var $self Carts (for IDE) */

		add_action( 'automatewoo_two_minute_worker', [ $self, 'check_for_abandoned_carts' ] );
		add_action( 'automatewoo_two_days_worker', [ $self, 'clean_stored_carts' ] );

		add_action( 'woocommerce_cart_emptied', [ $self, 'cart_emptied' ] );

		if ( AW()->options()->abandoned_cart_includes_pending_orders ) {
			// clear when order is no longer failed or pending
			add_action( 'woocommerce_order_status_changed', [ $self, 'clear_cart_on_order_status_changed' ], 10, 3 );
		}
		else {
			// clear on order creation
			add_action( 'woocommerce_checkout_order_processed', [ $self, 'clear_cart_on_order_created' ] );
			add_action( 'woocommerce_thankyou', [ $self, 'clear_cart_on_order_created' ] );
		}

		add_action( 'shutdown', [ $self, 'maybe_store_cart' ] );

		// change events
		add_action( 'woocommerce_add_to_cart', [ $self, 'mark_as_changed' ] );
		add_action( 'woocommerce_applied_coupon', [ $self, 'mark_as_changed' ] );
		add_action( 'woocommerce_removed_coupon', [ $self, 'mark_as_changed' ] );
		add_action( 'woocommerce_cart_item_removed', [ $self, 'mark_as_changed' ] );
		add_action( 'woocommerce_cart_item_restored', [ $self, 'mark_as_changed' ] );
		add_action( 'woocommerce_before_cart_item_quantity_zero', [ $self, 'mark_as_changed' ] );
		add_action( 'woocommerce_after_cart_item_quantity_update', [ $self, 'mark_as_changed' ] );

		add_action( 'woocommerce_after_calculate_totals', [ $self, 'trigger_update_on_cart_and_checkout_pages' ] );

		add_action( 'wp_login', [ $self, 'mark_as_changed_with_cookie' ], 20 );
		add_action( 'wp', [ $self, 'check_for_cart_update_cookie' ], 99 );
	}


	static function mark_as_changed() {
		static::$is_changed = true;
	}


	static function mark_as_changed_with_cookie() {
		if ( ! headers_sent() ) {
			wc_setcookie( 'automatewoo_do_cart_update', 1 );
		}
	}


	/**
	 * Important not to run this in the admin area, may not update cart properly
	 */
	static function check_for_cart_update_cookie() {
		if ( ! empty( $_COOKIE[ 'automatewoo_do_cart_update' ] ) ) {
			self::mark_as_changed();
			wc_setcookie( 'automatewoo_do_cart_update', '', time() - HOUR_IN_SECONDS );
		}
	}


	static function trigger_update_on_cart_and_checkout_pages() {
		if (
				defined( 'WOOCOMMERCE_CART' )
				|| is_checkout()
				|| did_action( 'woocommerce_before_checkout_form' ) //  support for one page checkout plugins
		) {
			self::mark_as_changed();
		}
	}


	/**
	 * @return array
	 */
	static function get_statuses() {
		return apply_filters( 'automatewoo/cart/statuses', [
			'active' => __( 'Active', 'automatewoo' ),
			'abandoned' => __( 'Abandoned', 'automatewoo' )
		]);
	}


	/**
	 * Check if any active carts have been abandoned, runs every 2 minutes
	 */
	static function check_for_abandoned_carts() {

		/** @var Background_Processes\Abandoned_Carts $process */
		$process = Background_Processes::get( 'abandoned_carts' );

		// don't start a new process until the previous is finished
		if ( $process->has_queued_items() ) {
			$process->maybe_schedule_health_check();
			return;
		}

		$cart_abandoned_timeout = absint( AW()->options()->abandoned_cart_timeout ); // mins

		$timeout_date = new \DateTime();
		$timeout_date->modify("-$cart_abandoned_timeout minutes" );

		$query = new Cart_Query();
		$query->where('status', 'active' )
			->where( 'last_modified', $timeout_date, '<' )
			->set_limit( 100 )
			->set_return( 'ids' );

		if ( ! $carts = $query->get_results() ) {
			return;
		}

		$process->data( $carts )->start();
	}


	/**
	 * Logic to determine whether we should save the cart on certain hooks
	 */
	static function maybe_store_cart() {

		if ( ! self::$is_changed ) return; // cart has not changed
		if ( did_action( 'wp_logout' ) ) return; // don't clear the cart after logout
		if ( is_admin() ) return;

		// session only loaded on front end
		if ( WC()->session ) {
			$last_checkout = WC()->session->get('automatewoo_checkout_processed_time');

			// ensure checkout has not been processed in the last 5 minutes
			// this is a fallback for a rare case when the cart session is not cleared after checkout
			if ( $last_checkout && $last_checkout > ( time() - 5 * MINUTE_IN_SECONDS ) ) {
				return;
			}
		}

		if ( $user_id = AW()->session_tracker->get_detected_user_id() ) {
			self::store_user_cart( $user_id );
		}
		elseif ( $guest = AW()->session_tracker->get_current_guest() ) {
			// Store a guest cart if the guest has been stored in the database
			self::store_guest_cart( $guest );
			$guest->do_check_in();
		}
	}


	/**
	 * Attempts to update or insert carts for guests
	 *
	 * @param Guest $guest
	 * @return bool
	 */
	static function store_guest_cart( $guest ) {

		if ( ! $guest )
			return false;

		$cart = $guest->get_cart();

		if ( $cart ) {
			if ( self::is_empty() ) {
				$cart->delete();
			}
			else {
				$cart->sync();
			}
		}
		else {
			// cart is empty
			if ( self::is_empty() ) {
				return false;
			}

			// create new cart
			$cart = new Cart();
			$cart->set_guest_id( $guest->get_id() );
			$cart->set_token();
			$cart->sync();
		}

		return true;
	}


	/**
	 * Attempts to store cart for a registered user whether they are logged in or not
	 *
	 * @param bool $user_id
	 * @return bool
	 */
	static function store_user_cart( $user_id = false ) {

		if ( ! $user_id ) {
			// get user
			if ( ! $user_id = AW()->session_tracker->get_detected_user_id() )
				return false;
		}

		// If user is logged out their WC cart gets emptied
		// at this point we are tracking them via cookie
		// so it doesn't make sense to clear their abandoned cart
		if ( ! is_user_logged_in() && self::is_empty() ) {
			return false;
		}

		// does this user already have a stored cart?
		$existing_cart = Cart_Factory::get_by_user_id( $user_id );


		// if cart already exists
		if ( $existing_cart ) {

			// delete cart if empty otherwise update it
			if ( self::is_empty() ) {
				$existing_cart->delete();
			}
			else {
				$existing_cart->sync();
			}

			return true;
		}
		else {
			// if the cart doesn't already exist
			// and there are no items in cart no there is no need to insert
			if ( self::is_empty() ) {
				return false;
			}

			// create a new stored cart for the user
			$cart = new Cart();
			$cart->set_user_id( $user_id );
			$cart->set_token();
			$cart->sync();

			return true;
		}

	}


	/**
	 * woocommerce_cart_emptied fires when an order is placed and the cart is emptied.
	 * It does NOT fire when a user empties their cart.
	 * It appears to also NOT fire when an a pending or failed order is generated,
	 * important that it remains this way for the abandoned_cart_includes_pending_orders option
	 */
	static function cart_emptied() {

		if ( did_action( 'wp_logout' ) ) {
			return; // don't clear cart after logout
		}

		// Ensure carts are cleared for users and guests registered at checkout
		$user_id = AW()->session_tracker->get_detected_user_id();
		$guest = AW()->session_tracker->get_current_guest();

		if ( $user_id ) {
			$cart = Cart_Factory::get_by_user_id( $user_id );
			if ( $cart ) {
				$cart->delete();
			}
		}

		if ( $guest ) {
			$guest->delete_cart();
		}

		self::$is_changed = false; // cart is up-to-date
	}


	/**
	 * Ensure the stored abandoned cart is removed when an order is created.
	 * Clears even if payment has not gone through.
	 *
	 * @param $order_id
	 */
	static function clear_cart_on_order_created( $order_id ) {

		if ( WC()->session ) {
			WC()->session->set( 'automatewoo_checkout_processed_time', time() );
		}

		// clear by session key
		if ( $guest = AW()->session_tracker->get_current_guest() ) {
			$guest->delete_cart();
		}

		self::clear_cart_by_order( $order_id );
	}


	/**
	 * Clear cart when transition changes from pending, cancelled or failed
	 *
	 * @param $order_id
	 * @param $old_status
	 * @param $new_status
	 */
	static function clear_cart_on_order_status_changed( $order_id, $old_status, $new_status ) {
		$failed_statuses = [ 'pending', 'failed', 'cancelled' ];

		if ( in_array( $old_status, $failed_statuses ) && ! in_array( $new_status, $failed_statuses ) ) {
			self::clear_cart_by_order( $order_id );
		}
	}


	/**
	 * Clears and carts that match the customer from an order
	 *
	 * @param $order_id
	 */
	static function clear_cart_by_order( $order_id ) {

		if ( ! $order = wc_get_order( $order_id ) ) {
			return;
		}

		if ( $user_id = $order->get_user_id() ) {
			$cart = Cart_Factory::get_by_user_id( $user_id );
			if ( $cart ) {
				$cart->delete();
			}
		}

		// clear by email
		if ( $guest = Guest_Factory::get_by_email( Clean::email( Compat\Order::get_billing_email( $order ) ) ) ) {
			$guest->delete_cart();
		}

		self::$is_changed = false; // cart is up-to-date
	}


	/**
	 * Restores a cart into the current session
	 * @param bool $cart_token
	 * @return bool
	 */
	static function restore_cart( $cart_token ) {

		if ( ! $cart_token ) {
			return false;
		}

		$cart = Cart_Factory::get_by_token( $cart_token );

		if ( ! $cart || ! $cart->has_items() ) {
			return false;
		}

		$notices_backup = wc_get_notices();

		// merge restored items with existing
		$existing_items = WC()->cart->get_cart_for_session();

		foreach ( $cart->get_items() as $item ) {
			if ( isset( $existing_items[ $item->get_key() ] ) ) {
				continue; // item already exists in cart
			}

			WC()->cart->add_to_cart( $item->get_product_id(), $item->get_quantity(), $item->get_variation_id(), $item->get_variation_data() );
		}

		// restore coupons
		foreach ( $cart->get_coupons() as $coupon_code => $coupon_data ) {
			if ( ! WC()->cart->has_discount( $coupon_code ) ) {
				WC()->cart->add_discount( $coupon_code );
			}
		}

		// clear show notices for added coupons or products
		WC()->session->set( 'wc_notices', $notices_backup );

		return true;
	}


	/**
	 * Delete old inactive carts
	 */
	static function clean_stored_carts() {
		global $wpdb;

		if ( ! $clear_inactive_carts_after = absint( AW()->options()->clear_inactive_carts_after ) ) {
			return;
		}

		$delay_date = new \DateTime();
		$delay_date->modify("-$clear_inactive_carts_after days");

		$table = AW()->database_tables()->get_table( 'carts' );

		$wpdb->query( $wpdb->prepare("
			DELETE FROM ". $table->name . "
			WHERE last_modified < %s",
			$delay_date->format( Format::MYSQL )
		));
	}


	/**
	 * WC()->cart->is_empty() method added recently
	 * @return bool
	 */
	static function is_empty() {
		return 0 === sizeof( WC()->cart->get_cart() );
	}

}

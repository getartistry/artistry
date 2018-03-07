<?php

namespace AutomateWoo\Event_Helpers;

use AutomateWoo\Compat;
use AutomateWoo\Events;

/**
 * @class Order_Created
 */
class Order_Created {


	static function init() {
		// add order place hook, limited to fire once per order
		add_action( 'woocommerce_new_order', [ __CLASS__, 'order_created' ], 100 );
		add_action( 'woocommerce_api_create_order', [ __CLASS__, 'order_created' ], 100 );
		add_action( 'woocommerce_checkout_order_processed', [ __CLASS__, 'order_created' ], 100 );
		add_filter( 'wcs_renewal_order_created', [ __CLASS__, 'filter_renewal_orders' ], 100 );

		if ( is_admin() ) {
			add_action( 'transition_post_status', [ __CLASS__, 'transition_post_status' ], 50, 3 );
		}

		add_action( 'automatewoo/async/order_created', [ __CLASS__, 'pre_async_hook_check' ], 1 );
	}


	/**
	 * @param \WC_Order $order
	 * @return \WC_Order
	 */
	static function filter_renewal_orders( $order ) {
		self::order_created( Compat\Order::get_id( $order ) );
		return $order;
	}


	/**
	 * @param $new_status
	 * @param $old_status
	 * @param \WP_Post $post
	 */
	static function transition_post_status( $new_status, $old_status, $post ) {
		if ( $old_status === 'auto-draft' && $post->post_type === 'shop_order' ) {
			self::order_created( $post->ID );
		}
	}

	/**
	 * @param $order_id int
	 */
	static function order_created( $order_id ) {
		if ( ! $order_id || ! $order = wc_get_order( $order_id ) ) {
			return;
		}

		if ( Compat\Order::get_meta( $order, '_aw_checkout_order_processed' ) ) {
			return; // Ensure only order placed triggers once ever fire once per order
		}

		Compat\Order::update_meta( $order, '_aw_checkout_order_processed', true );
		Compat\Order::update_meta( $order, '_aw_pending_created_async_hook', true );

		do_action( 'automatewoo/order/created', $order_id );
		Events::schedule_async_event( 'automatewoo/async/order_created', [ $order_id ] );
	}


	static function pre_async_hook_check( $order_id ) {

		if ( ! $order_id || ! $order = wc_get_order( $order_id ) ) {
			return;
		}

		// additional meta check, but remove this time
		if ( ! Compat\Order::get_meta( $order, '_aw_pending_created_async_hook' ) ) {
			if ( AUTOMATEWOO_ENABLE_EXTRA_ASYNC_ORDER_CREATED_CHECK ) {
				remove_all_actions( current_action() ); // failed check so unhook
			}
			return;
		}

		Compat\Order::delete_meta( $order, '_aw_pending_created_async_hook' );
	}

}

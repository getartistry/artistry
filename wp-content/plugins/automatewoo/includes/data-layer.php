<?php

namespace AutomateWoo;

/**
 * @class Data_Layer
 */
class Data_Layer {

	private $data = [];


	/**
	 * @param array $data
	 */
	function __construct( $data = [] ) {

		if ( is_array( $data ) ) {
			$this->data = $data;
		}

		$this->init();
	}


	/**
	 * Initiate the data layer
	 */
	function init() {
		$this->ensure_customer_object_compatibility();
		do_action( 'automatewoo/data_layer/init' );
	}


	/**
	 * Auto fill customer based on user and user based on customer for compatibility with legacy triggers, rules etc
	 */
	function ensure_customer_object_compatibility() {
		if ( $this->get_customer() && ! $this->get_user() ) {

			if ( $this->get_customer()->is_registered() ) {
				$this->set_item( 'user', $this->get_customer()->get_user() );
			}
			else {
				// if the user is not registered at this point they may be a legacy order guest
				if ( $order = $this->get_order() ) {
					$this->set_item( 'user', AW()->order_helper->prepare_user_data_item( $order ) );
				}
				else {
					// IMPORTANT the customer might be a guest in which case remove the user completely
					unset( $this->data['user'] );
				}
			}
		}

		if ( $this->get_user() && ! $this->get_customer() ) {
			$this->set_item( 'customer', Customer_Factory::get_by_user_data_item( $this->get_user() ) );
		}

		// also inject customer objects for guests
		// but note that the guest data object hasn't been completely removed
		if ( $this->get_guest() && ! $this->get_customer() ) {
			$this->set_item( 'customer', Customer_Factory::get_by_guest_id( $this->get_guest()->get_id() ) );
		}
	}


	function ensure_customer_data_matches_order() {
		if ( ! $order = $this->get_order() ) {
			return;
		}

		if ( ! $customer = $this->get_customer() ) {
			return;
		}

		if ( $customer->get_email() != Compat\Order::get_billing_email( $order ) ) {
			$new_customer = Customer_Factory::get_by_order( $order );
			$this->set_item( 'customer', $new_customer );
		}
	}



	function clear() {
		$this->data = [];
	}


	/**
	 * Returns unvalidated data layer
	 * @return array
	 */
	function get_raw_data() {
		return $this->data;
	}


	/**
	 * @param $type
	 * @param $item
	 */
	function set_item( $type, $item ) {
		$this->data[$type] = $item;
	}


	/**
	 * @param string $type
	 * @return mixed
	 */
	function get_item( $type ) {

		if ( ! isset( $this->data[$type] ) ) {
			return false;
		}

		return aw_validate_data_item( $type, $this->data[$type] );
	}


	/**
	 * @return Customer|false
	 */
	function get_customer() {
		return $this->get_item( 'customer' );
	}


	/**
	 * @return Cart|false
	 */
	function get_cart() {
		return $this->get_item( 'cart' );
	}


	/**
	 * @return Guest|bool
	 */
	function get_guest() {
		return $this->get_item( 'guest' );
	}


	/**
	 * @return \WP_User|Order_Guest|false
	 */
	function get_user() {
		return $this->get_item( 'user' );
	}


	/**
	 * @return \WC_Order|false
	 */
	function get_order() {
		return $this->get_item( 'order' );
	}


	/**
	 * @return \WC_Subscription|false
	 */
	function get_subscription() {
		return $this->get_item( 'subscription' );
	}


	/**
	 * @return array|\WC_Order_Item_Product|false
	 */
	function get_order_item() {
		return $this->get_item( 'order_item' );
	}


	/**
	 * @return \WC_Memberships_User_Membership|false
	 */
	function get_membership() {
		return $this->get_item( 'membership' );
	}


	/**
	 * @return Wishlist|false
	 */
	function get_wishlist() {
		return $this->get_item( 'wishlist' );
	}


	/**
	 * @return \WC_Product|false
	 */
	function get_product() {
		return $this->get_item( 'product' );
	}


	/**
	 * @return Order_Note|false
	 */
	function get_order_note() {
		return $this->get_item( 'order_note' );
	}


	/**
	 * @return \WP_Comment|false
	 */
	function get_comment() {
		return $this->get_item( 'comment' );
	}


	/**
	 * @return Review|false
	 */
	function get_review() {
		return $this->get_item( 'review' );
	}


	/**
	 * @return Workflow|false
	 */
	function get_workflow() {
		return $this->get_item( 'workflow' );
	}


	/**
	 * @return \WP_Term|false
	 */
	function get_category() {
		return $this->get_item( 'category' );
	}


	/**
	 * @return \WP_Term|false
	 */
	function get_tag() {
		return $this->get_item( 'tag' );
	}


	/**
	 * @return string|bool
	 */
	function get_language() {

		if ( ! Integrations::is_wpml() ) {
			return false;
		}

		if ( $order = $this->get_order() ) {
			if ( $lang = Compat\Order::get_meta( $order, 'wpml_language' ) ) {
				return $lang;
			}
		}

		if ( $customer = $this->get_customer() ) {
			if ( $lang = $customer->get_language() ) {
				return $lang;
			}
		}

		if ( $user = $this->get_user() ) {
			if ( $lang = Language::get_user_language( $user ) ) {
				return $lang;
			}
		}

		if ( $guest = $this->get_guest() ) {
			if ( $lang = Language::get_guest_language( $guest ) ) {
				return $lang;
			}
		}

		return false;
	}

}

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_User
 */
class Data_Type_User extends Data_Type {

	/**
	 * @param \WP_User|Order_Guest $item
	 * @return bool
	 */
	function validate( $item ) {

		if ( ! is_a( $item, 'WP_User' ) && ! is_a( $item, 'AutomateWoo\Order_Guest' ) ) {
			return false;
		}

		if ( ! $item->user_email ) {
			return false; // social login users may not have an email address defined
		}

		return true;
	}


	/**
	 * @param \WP_User $item
	 * @return mixed
	 */
	function compress( $item ) {
		return $item->ID;
	}


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return mixed
	 */
	function decompress( $compressed_item, $compressed_data_layer ) {

		// if order based trigger always get the user data from the order
		if ( isset( $compressed_data_layer['order'] ) ) {
			if ( $order = wc_get_order( $compressed_data_layer['order'] ) ) {
				return AW()->order_helper->prepare_user_data_item( $order );
			}
		}

		if ( Integrations::subscriptions_enabled() && isset( $compressed_data_layer['subscription'] ) ) {
			if ( $subscription = wcs_get_subscription( $compressed_data_layer['subscription'] ) ) {
				return Subscription_Helper::prepare_user_data( $subscription );
			}
		}

		if ( $compressed_item ) {
			return get_user_by( 'id', absint( $compressed_item ) );
		}

		return false;
	}

}

return new Data_Type_User();

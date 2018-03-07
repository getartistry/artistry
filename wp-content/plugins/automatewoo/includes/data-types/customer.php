<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Customer
 */
class Data_Type_Customer extends Data_Type {

	/**
	 * @param Customer $item
	 * @return bool
	 */
	function validate( $item ) {

		if ( ! is_a( $item, 'AutomateWoo\Customer' ) ) {
			return false;
		}

		if ( ! $item->get_email() ) {
			return false; // social login users may not have an email address defined
		}

		return true;
	}


	/**
	 * @param Customer $item
	 * @return int
	 */
	function compress( $item ) {
		return $item->get_id();
	}


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return mixed
	 */
	function decompress( $compressed_item, $compressed_data_layer ) {

		if ( $compressed_item ) {
			return Customer_Factory::get( absint( $compressed_item ) );
		}

		// decompress customer from user, order or subscription data if present, used for triggers that have been converted from 'user' to 'customer' data types

		if ( isset( $compressed_data_layer['order'] ) ) {
			if ( $order = wc_get_order( $compressed_data_layer['order'] ) ) {
				return Customer_Factory::get_by_order( $order );
			}
		}

		if ( Integrations::subscriptions_enabled() && isset( $compressed_data_layer['subscription'] ) ) {
			if ( $subscription = wcs_get_subscription( $compressed_data_layer['subscription'] ) ) {
				return Customer_Factory::get_by_user_id( $subscription->get_user_id() );
			}
		}

		if ( isset( $compressed_data_layer['user'] ) ) {
			return Customer_Factory::get_by_user_id( absint( $compressed_data_layer['user'] ) );
		}

		return false;
	}

}

return new Data_Type_Customer();

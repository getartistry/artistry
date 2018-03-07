<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Data_Type_Subscription
 */
class Data_Type_Subscription extends Data_Type {


	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_a( $item, 'WC_Subscription' );
	}


	/**
	 * @param \WC_Subscription $item
	 * @return mixed
	 */
	function compress( $item ) {
		return Compat\Subscription::get_id( $item );
	}


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return mixed
	 */
	function decompress( $compressed_item, $compressed_data_layer ) {
		if ( Integrations::subscriptions_enabled() ) {
			return wcs_get_subscription( $compressed_item );
		}
	}

}

return new Data_Type_Subscription();

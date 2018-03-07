<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Membership
 * @since 2.8.3
 */
class Data_Type_Membership extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_a( $item, 'WC_Memberships_User_Membership' );
	}


	/**
	 * @param \WC_Memberships_User_Membership $item
	 * @return mixed
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
		if ( Integrations::is_memberships_enabled() ) {
			return wc_memberships_get_user_membership( absint( $compressed_item ) );
		}
	}

}

return new Data_Type_Membership();

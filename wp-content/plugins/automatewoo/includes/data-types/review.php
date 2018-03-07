<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Review
 */
class Data_Type_Review extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_a( $item, 'AutomateWoo\Review');
	}


	/**
	 * @param Review $item
	 * @return mixed
	 */
	function compress( $item ) {
		return $item->get_id();
	}


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return Review|false
	 */
	function decompress( $compressed_item, $compressed_data_layer ) {
		return new Review( $compressed_item );
	}

}

return new Data_Type_Review();

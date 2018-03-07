<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Post
 */
class Data_Type_Post extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_a( $item, 'WP_Post' );
	}


	/**
	 * @param $item \WP_Post
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
		return get_post( $compressed_item );
	}

}

return new Data_Type_Post();

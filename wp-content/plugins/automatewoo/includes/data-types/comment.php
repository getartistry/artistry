<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Comment
 */
class Data_Type_Comment extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_object( $item );
	}


	/**
	 * @param $item
	 * @return mixed
	 */
	function compress( $item ) {
		return $item->comment_ID;
	}


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return \WP_Comment|false
	 */
	function decompress( $compressed_item, $compressed_data_layer ) {
		return get_comment( $compressed_item );
	}

}

return new Data_Type_Comment();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Order_Note
 */
class Data_Type_Order_Note extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_a( $item, 'AutomateWoo\Order_Note' );
	}


	/**
	 * @param $item
	 * @return mixed
	 */
	function compress( $item ) {
		return $item->id;
	}


	/**
	 * @param $compressed_item
	 * @param $compressed_data_layer
	 * @return mixed
	 */
	function decompress( $compressed_item, $compressed_data_layer ) {
		if ( $comment = get_comment( $compressed_item ) ) {
			return new Order_Note( $comment->comment_ID, $comment->comment_content, $comment->comment_post_ID );
		}
	}

}

return new Data_Type_Order_Note();

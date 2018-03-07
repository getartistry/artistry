<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Data_Type_Cart
 */
class Data_Type_Cart extends Data_Type {

	/**
	 * @param $item
	 * @return bool
	 */
	function validate( $item ) {
		return is_a( $item, 'AutomateWoo\Cart');
	}


	/**
	 * @param Cart $item
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

		if ( $cart = AW()->get_cart( $compressed_item ) ) {
			return $cart;
		}

		// Cart may have been cleared but we will pass the cart object anyway
		// this behavior may change in the future
		$cart = new Cart();
		$cart->set_id( $compressed_item );

		return $cart;
	}

}

return new Data_Type_Cart();

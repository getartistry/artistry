<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Cart_Items
 */
class Cart_Items extends Abstract_Object {

	public $data_item = 'cart';

	public $is_multi = false;

	public $ajax_action = 'woocommerce_json_search_products_and_variations';

	public $class = 'wc-product-search';


	function init() {

		$this->title = __( 'Cart Items', 'automatewoo' );
		$this->group = __( 'Cart', 'automatewoo' );
		$this->placeholder = __( 'Search products...', 'automatewoo' );

		$this->compare_types = [
			'includes' => __( 'includes', 'automatewoo' ),
			'not_includes' => __( 'does not include', 'automatewoo' )
		];
	}


	/**
	 * @param $value
	 * @return string
	 */
	function get_object_display_value( $value ) {
		if ( $product = wc_get_product( absint( $value ) ) ) {
			return $product->get_formatted_name();
		}
	}


	/**
	 * @param $cart \AutomateWoo\Cart
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $cart, $compare, $value ) {

		if ( ! $product = wc_get_product( absint( $value ) ) )
			return false;

		$target_product_id = Compat\Product::get_id( $product );
		$is_variation = Compat\Product::is_variation( $product );
		$includes = false;

		foreach ( $cart->get_items() as $item ) {
			$id = $is_variation ? $item->get_variation_id() : $item->get_product_id();
			if ( $id == $target_product_id ) {
				$includes = true;
				break;
			}
		}

		switch ( $compare ) {
			case 'includes':
				return $includes;
				break;

			case 'not_includes':
				return ! $includes;
				break;
		}

	}
}

return new Cart_Items();

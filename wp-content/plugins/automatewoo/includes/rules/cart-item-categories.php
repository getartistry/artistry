<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Fields_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Cart_Item_Categories
 */
class Cart_Item_Categories extends Abstract_Select {

	public $data_item = 'cart';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Cart Item Categories', 'automatewoo' );
		$this->group = __( 'Cart', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_categories_list();
	}


	/**
	 * @param $cart \AutomateWoo\Cart
	 * @param $compare
	 * @param $expected
	 * @return bool
	 */
	function validate( $cart, $compare, $expected ) {

		if ( empty( $expected ) ) {
			return false;
		}

		$category_ids = [];

		foreach ( $cart->get_items() as $item ) {
			$terms = wp_get_object_terms( $item->get_product_id(), 'product_cat', [ 'fields' => 'ids' ] );
			$category_ids = array_merge( $category_ids, $terms );
		}

		$category_ids = array_filter( $category_ids );

		return $this->validate_select( $category_ids, $compare, $expected );
	}
}

return new Cart_Item_Categories();

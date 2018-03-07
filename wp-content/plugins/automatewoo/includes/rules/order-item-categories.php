<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Rule_Order_Item_Categories
 */
class Rule_Order_Item_Categories extends Rules\Abstract_Select {

	public $data_item = 'order';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Order Item Categories', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_categories_list();
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare
	 * @param $expected
	 * @return bool
	 */
	function validate( $order, $compare, $expected ) {

		if ( empty( $expected ) ) {
			return false;
		}

		$category_ids = [];

		foreach ( $order->get_items() as $item ) {
			$terms = wp_get_object_terms( Compat\Order_Item::get_product_id( $item ), 'product_cat', [ 'fields' => 'ids' ] );
			$category_ids = array_merge( $category_ids, $terms );
		}

		$category_ids = array_filter( $category_ids );

		return $this->validate_select( $category_ids, $compare, $expected );
	}
}

return new Rule_Order_Item_Categories();

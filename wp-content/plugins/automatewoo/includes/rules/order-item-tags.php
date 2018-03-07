<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Rule_Order_Item_Tags
 */
class Rule_Order_Item_Tags extends Rules\Abstract_Select {

	public $data_item = 'order';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Order Item Tags', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_product_tags_list();
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

		$tag_ids = [];

		foreach ( $order->get_items() as $item ) {
			$terms = wp_get_object_terms( Compat\Order_Item::get_product_id( $item ), 'product_tag', [ 'fields' => 'ids' ] );
			$tag_ids = array_merge( $tag_ids, $terms );
		}

		$tag_ids = array_filter( $tag_ids );

		return $this->validate_select( $tag_ids, $compare, $expected );
	}
}

return new Rule_Order_Item_Tags();

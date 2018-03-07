<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;
use AutomateWoo\Logic_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Items
 */
class Order_Items extends Abstract_Object {

	public $data_item = 'order';

	public $ajax_action = 'woocommerce_json_search_products_and_variations';

	public $class = 'wc-product-search';


	function init() {

		$this->title = __( 'Order Items', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
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
		if ( $product = wc_get_product( absint( $value ) ) )
			return $product->get_formatted_name();
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		if ( ! $expected_product = wc_get_product( absint( $value ) ) ) {
			return false;
		}

		$includes = false;

		foreach ( $order->get_items() as $item ) {

			$product = Compat\Order_Item::get_product( $item, $order );
			$includes = Logic_Helper::match_products( $product, $expected_product );

			if ( $includes ) {
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

return new Order_Items();

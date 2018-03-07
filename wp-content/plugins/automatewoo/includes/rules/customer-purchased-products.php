<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Purchased_Products
 */
class Customer_Purchased_Products extends Abstract_Object {

	public $data_item = 'customer';

	public $is_multi = false;

	public $ajax_action = 'woocommerce_json_search_products_and_variations';

	public $class = 'wc-product-search';


	function init() {
		$this->title = __( "Customer's Purchased Products - All Time", 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
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
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $expected_value
	 * @return bool
	 */
	function validate( $customer, $compare, $expected_value ) {

		if ( ! $product = wc_get_product( absint( $expected_value ) ) )
			return false;

		$product_id = Compat\Product::get_id( $product );

		$includes = in_array( $product_id, $customer->get_purchased_products() );

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

return new Customer_Purchased_Products();
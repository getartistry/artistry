<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Logic_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Product
 */
class Product extends Abstract_Object {

	public $data_item = 'product';

	public $ajax_action = 'woocommerce_json_search_products_and_variations';

	public $class = 'wc-product-search';


	function init() {
		$this->title = __( 'Product', 'automatewoo' );
		$this->group = __( 'Product', 'automatewoo' );

		$this->compare_types = [
			'is' => __( 'is', 'automatewoo' ),
			'is_not' => __( 'is not', 'automatewoo' )
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
	 * @param $product \WC_Product|\WC_Product_Variation
	 * @param $compare
	 * @param $expected
	 * @return bool
	 */
	function validate( $product, $compare, $expected ) {

		if ( ! $expected_product = wc_get_product( absint( $expected ) ) ) {
			return false;
		}

		$match = Logic_Helper::match_products( $product, $expected_product );

		switch ( $compare ) {
			case 'is':
				return $match;
				break;

			case 'is_not':
				return ! $match;
				break;
		}
	}

}

return new Product();

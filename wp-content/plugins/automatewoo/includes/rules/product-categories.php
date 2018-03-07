<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Fields_Helper;
use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Product_Categories
 */
class Product_Categories extends Abstract_Select {

	public $data_item = 'product';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Product Categories', 'automatewoo' );
		$this->group = __( 'Product', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_categories_list();
	}


	/**
	 * @param $product \WC_Product|\WC_Product_Variation
	 * @param $compare
	 * @param $expected
	 * @return bool
	 */
	function validate( $product, $compare, $expected ) {
		if ( empty( $expected ) ) {
			return false;
		}

		$product_id = Compat\Product::is_variation( $product ) ? Compat\Product::get_parent_id( $product ) : Compat\Product::get_id( $product );
		$categories = wp_get_object_terms( $product_id, 'product_cat', [ 'fields' => 'ids' ] );

		return $this->validate_select( $categories, $compare, $expected );
	}
}

return new Product_Categories();

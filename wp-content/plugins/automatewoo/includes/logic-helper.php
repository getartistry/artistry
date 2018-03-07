<?php

namespace AutomateWoo;

/**
 * @class Logic_Helper
 */
class Logic_Helper {


	/**
	 * Check if two products are the same, complexity aries when considering variations
	 *
	 * @param \WC_Product $actual_product
	 * @param \WC_Product $expected_product
	 * @return bool
	 */
	static function match_products( $actual_product, $expected_product ) {

		if ( ! $actual_product ) {
			return false;
		}

		$match = false;

		if ( Compat\Product::is_variation( $expected_product ) ) {
			// match a specific variation
			if ( Compat\Product::get_id( $expected_product ) == Compat\Product::get_id( $actual_product ) ) {
				$match = true;
			}
		}
		else {
			// match the main product or any of its variations
			$actual_main_product_id = Compat\Product::is_variation( $actual_product ) ? Compat\Product::get_parent_id( $actual_product ) : Compat\Product::get_id( $actual_product );

			if ( Compat\Product::get_id( $expected_product ) == $actual_main_product_id ) {
				$match = true;
			}
		}

		return $match;
	}

}
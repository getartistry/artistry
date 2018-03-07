<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Product_Parent_Sku
 * @since 2.9
 */
class Variable_Product_Parent_Sku extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the parent product's SKU.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {
		if ( $parent_id = Compat\Product::get_parent_id( $product ) ) {
			if ( $parent = wc_get_product( $parent_id ) ) {
				return $parent->get_sku();
			}
		}
	}
}

return new Variable_Product_Parent_Sku();

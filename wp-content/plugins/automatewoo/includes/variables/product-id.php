<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Product_ID
 */
class Variable_Product_ID extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the product's ID.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {
		return Compat\Product::get_id( $product );
	}
}

return new Variable_Product_ID();

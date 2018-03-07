<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Product_Description
 */
class Variable_Product_Description extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the description of the product or variation.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {
		return Compat\Product::get_description( $product );
	}

}

return new Variable_Product_Description();

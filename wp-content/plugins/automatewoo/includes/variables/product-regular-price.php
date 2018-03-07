<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Product_Regular_Price
 */
class Variable_Product_Regular_Price extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the product's formatted regular price.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {
		return wc_price( $product->get_regular_price() );
	}
}

return new Variable_Product_Regular_Price();
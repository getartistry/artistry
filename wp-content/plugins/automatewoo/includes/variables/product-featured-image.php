<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * @class Variable_Product_Featured_Image
 */
class Variable_Product_Featured_Image extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the product's featured image.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {
		return $product->get_image('shop_catalog');
	}
}

return new Variable_Product_Featured_Image();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Product_Meta
 */
class Variable_Product_Meta extends Variable_Abstract_Meta {


	function load_admin_details() {
		parent::load_admin_details();
		$this->description = __( "Displays an product's meta field.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {

		if ( ! $parameters['key'] ) {
			return false;
		}

		$value = Compat\Product::get_meta( $product, $parameters['key'] );

		// maybe look for parent meta
		if ( empty( $value ) && Compat\Product::is_variation( $product ) ) {
			$value = Compat\Product::get_parent_meta( $product, $parameters['key'] );
		}

		return $value;
	}

}

return new Variable_Product_Meta();

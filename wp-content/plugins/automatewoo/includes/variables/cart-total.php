<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Cart_Total
 */
class Variable_Cart_Total extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the formatted total of the cart.", 'automatewoo');
	}


	/**
	 * @param $cart Cart
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $cart, $parameters ) {
		return $cart->price( $cart->get_total() );
	}
}

return new Variable_Cart_Total();

<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Cart_Count
 */
class AW_Rule_Cart_Count extends AutomateWoo\Rules\Abstract_Number {

	public $data_item = 'cart';

	public $support_floats = false;


	function init() {
		$this->title = __( 'Cart Item Count', 'automatewoo' );
		$this->group = __( 'Cart', 'automatewoo' );
	}


	/**
	 * @param $cart AutomateWoo\Cart
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $cart, $compare, $value ) {
		return $this->validate_number( count( $cart->get_items() ), $compare, $value );
	}

}

return new AW_Rule_Cart_Count();

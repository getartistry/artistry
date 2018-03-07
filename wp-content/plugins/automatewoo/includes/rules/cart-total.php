<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Cart_Total
 */
class AW_Rule_Cart_Total extends AutomateWoo\Rules\Abstract_Number {

	/** @var array  */
	public $data_item = 'cart';

	public $support_floats = false;


	function init() {
		$this->title = __( 'Cart Total', 'automatewoo' );
		$this->group = __( 'Cart', 'automatewoo' );
	}


	/**
	 * @param $cart AutomateWoo\Cart
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $cart, $compare, $value ) {
		return $this->validate_number( $cart->get_total(), $compare, $value );
	}

}

return new AW_Rule_Cart_Total();

<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Cart_Coupons
 */
class AW_Rule_Cart_Coupons extends AutomateWoo\Rules\Abstract_Select {

	public $data_item = 'cart';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Cart Coupons', 'automatewoo' );
		$this->group = __( 'Cart', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return AutomateWoo\Fields_Helper::get_coupons_list();
	}


	/**
	 * @param $cart AutomateWoo\Cart
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $cart, $compare, $value ) {
		return $this->validate_select( array_keys( $cart->get_coupons() ), $compare, $value );
	}


}

return new AW_Rule_Cart_Coupons();

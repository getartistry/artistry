<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Coupons
 */
class AW_Rule_Order_Coupons extends AutomateWoo\Rules\Abstract_Select {

	public $data_item = 'order';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Order Coupons', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return AutomateWoo\Fields_Helper::get_coupons_list();
	}


	/**
	 * @param WC_Order $order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {
		return $this->validate_select( $order->get_used_coupons(), $compare, $value );
	}


}

return new AW_Rule_Order_Coupons();

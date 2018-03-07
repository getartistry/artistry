<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Shipping_Country
 */
class AW_Rule_Order_Shipping_Country extends AutomateWoo\Rules\Abstract_Select {

	public $data_item = 'order';


	function init() {
		$this->title = __( 'Order Shipping Country', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return WC()->countries->get_allowed_countries();
	}


	/**
	 * @param $order WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {
		return $this->validate_select( AutomateWoo\Compat\Order::get_shipping_country( $order ), $compare, $value );
	}

}

return new AW_Rule_Order_Shipping_Country();

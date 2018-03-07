<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Shipping_Method_String
 */
class AW_Rule_Order_Shipping_Method_String extends AutomateWoo\Rules\Abstract_String {

	public $data_item = 'order';


	function init() {
		$this->title = __( 'Order Shipping Method - Text Match', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @param $order WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {
		return $this->validate_string( $order->get_shipping_method(), $compare, $value );
	}

}

return new AW_Rule_Order_Shipping_Method_String();

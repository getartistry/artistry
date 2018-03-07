<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Billing_Phone
 */
class Variable_Order_Billing_Phone extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the billing phone number for the order.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {
		return Compat\Order::get_billing_phone( $order );
	}
}

return new Variable_Order_Billing_Phone();
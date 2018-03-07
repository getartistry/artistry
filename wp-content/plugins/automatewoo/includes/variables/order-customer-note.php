<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Customer_Note
 */
class Variable_Order_Customer_Note extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer provided note for the order.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {
		return Compat\Order::get_customer_note( $order );
	}
}

return new Variable_Order_Customer_Note();

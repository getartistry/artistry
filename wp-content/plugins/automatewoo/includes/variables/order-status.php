<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Status
 */
class Variable_Order_Status extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the status of the order.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {
		return $order->get_status();
	}
}

return new Variable_Order_Status();
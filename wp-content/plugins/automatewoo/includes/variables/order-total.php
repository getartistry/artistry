<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Total
 */
class Variable_Order_Total extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the formatted total of the order.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {
		return $order->get_formatted_order_total();
	}
}

return new Variable_Order_Total();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Itemscount
 */
class Variable_Order_Itemscount extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the number of items in the order.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {
		return $order->get_item_count();
	}
}


return new Variable_Order_Itemscount();
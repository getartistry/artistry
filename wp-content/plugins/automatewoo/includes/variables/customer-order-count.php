<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Order_Count
 */
class Variable_Customer_Order_Count extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the total number of orders the customer has placed.", 'automatewoo');
	}


	/**
	 * @param $customer Customer
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return mixed
	 */
	function get_value( $customer, $parameters, $workflow ) {
		if ( ! $customer->is_registered() ) {
			return false;
		}
		return $customer->get_order_count();
	}

}

return new Variable_Customer_Order_Count();

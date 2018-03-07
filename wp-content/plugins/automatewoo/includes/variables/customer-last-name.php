<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Last_Name
 */
class Variable_Customer_Last_Name extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer's last name.", 'automatewoo');
	}


	/**
	 * @param $customer Customer
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return string
	 */
	function get_value( $customer, $parameters, $workflow ) {
		// order name takes precedence
		if ( $order = $workflow->data_layer()->get_order() ) {
			return Compat\Order::get_billing_last_name( $order );
		}
		return $customer->get_last_name();
	}

}

return new Variable_Customer_Last_Name();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_First_Name
 */
class Variable_Customer_First_Name extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer's first name.", 'automatewoo');
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
			return Compat\Order::get_billing_first_name( $order );
		}
		return $customer->get_first_name();
	}

}

return new Variable_Customer_First_Name();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Full_Name
 */
class Variable_Customer_Full_Name extends Variable {

	function load_admin_details() {
		$this->description = __( "Displays the customer's full name.", 'automatewoo');
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
			return sprintf( _x( '%1$s %2$s', 'full name', 'automatewoo' ), Compat\Order::get_billing_first_name( $order ), Compat\Order::get_billing_last_name( $order ) );
		}
		return $customer->get_full_name();
	}

}

return new Variable_Customer_Full_Name();

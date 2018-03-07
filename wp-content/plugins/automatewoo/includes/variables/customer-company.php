<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Company
 */
class Variable_Customer_Company extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer's billing company.", 'automatewoo');
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
			return Compat\Order::get_billing_company( $order );
		}
		return $customer->get_billing_company();
	}

}

return new Variable_Customer_Company();

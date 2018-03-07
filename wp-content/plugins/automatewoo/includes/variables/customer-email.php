<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Email
 */
class Variable_Customer_Email extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer's email address. You can use this variable in the To field when sending emails.", 'automatewoo');
	}


	/**
	 * @param Customer $customer
	 * @param $parameters
	 * @param Workflow $workflow
	 * @return string
	 */
	function get_value( $customer, $parameters, $workflow ) {
		$email = false;

		// order email takes precedence
		if ( $order = $workflow->data_layer()->get_order() ) {
			$email = Clean::email( Compat\Order::get_billing_email( $order ) );
		}

		if ( ! $email ) {
			$email = $customer->get_email();
		}

		return $email;
	}

}

return new Variable_Customer_Email();

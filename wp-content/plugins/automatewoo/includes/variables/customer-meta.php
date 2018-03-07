<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Meta
 */
class Variable_Customer_Meta extends Variable_Abstract_Meta {


	function load_admin_details() {
		$this->description = __( "Displays the value of a customer's meta field. If the customer has an account, the value is pulled from the user meta table. If the customer is a guest, the guest meta table is used.", 'automatewoo');
		parent::load_admin_details();
	}


	/**
	 * @param $customer Customer
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return string
	 */
	function get_value( $customer, $parameters, $workflow ) {
		if ( empty( $parameters['key'] ) ) {
			return false;
		}

		return $customer->get_meta( Clean::string( $parameters['key'] ) );
	}

}

return new Variable_Customer_Meta();

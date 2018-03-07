<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Total_Spent
 */
class Variable_Customer_Total_Spent extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the formatted total spent for the customer.", 'automatewoo');
	}


	/**
	 * @param $customer Customer
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return string
	 */
	function get_value( $customer, $parameters, $workflow ) {
		return wc_price( $customer->get_total_spent() );
	}

}

return new Variable_Customer_Total_Spent();

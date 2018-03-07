<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_Country
 */
class Variable_Customer_Country extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer's billing country.", 'automatewoo');
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
			$country = Compat\Order::get_billing_country( $order );
		}
		else {
			$country = $customer->get_billing_country();
		}

		return aw_get_country_name( $country );
	}

}

return new Variable_Customer_Country();

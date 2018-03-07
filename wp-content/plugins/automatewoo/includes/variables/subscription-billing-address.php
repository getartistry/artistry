<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_Billing_Address
 */
class Variable_Subscription_Billing_Address extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the formatted billing address for the subscription.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $subscription, $parameters ) {
		return $subscription->get_formatted_billing_address();
	}
}

return new Variable_Subscription_Billing_Address();
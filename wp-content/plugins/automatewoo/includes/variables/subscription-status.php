<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_Status
 */
class Variable_Subscription_Status extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the formatted status of the subscription.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters
	 * @return string
	 */
	function get_value( $subscription, $parameters ) {
		return $subscription->get_status();
	}
}

return new Variable_Subscription_Status();

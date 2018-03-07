<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_ID
 */
class Variable_Subscription_ID extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the ID of the subscription.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters
	 * @return string
	 */
	function get_value( $subscription, $parameters ) {
		return Compat\Subscription::get_id( $subscription );
	}
}

return new Variable_Subscription_ID();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_Meta
 */
class Variable_Subscription_Meta extends Variable_Abstract_Meta {


	function load_admin_details() {
		parent::load_admin_details();
		$this->description = __( "Displays a subscription's meta field.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters array
	 * @return string|bool
	 */
	function get_value( $subscription, $parameters ) {
		if ( $parameters['key'] ) {
			return Compat\Subscription::get_meta( $subscription, $parameters['key'] );
		}
		return false;
	}
}

return new Variable_Subscription_Meta();

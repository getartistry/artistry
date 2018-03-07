<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_Payment_Method
 */
class Variable_Subscription_Payment_Method extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the payment method of the subscription.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters
	 * @return string
	 */
	function get_value( $subscription, $parameters ) {
		return $subscription->get_payment_method_to_display();
	}
}

return new Variable_Subscription_Payment_Method();


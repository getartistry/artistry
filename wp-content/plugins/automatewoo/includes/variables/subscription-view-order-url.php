<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_View_Order_Url
 */
class Variable_Subscription_View_Order_Url extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays a URL to the subscription page in the My Account area.", 'automatewoo');
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters
	 * @return string
	 */
	function get_value( $subscription, $parameters ) {
		return $subscription->get_view_order_url();
	}

}

return new Variable_Subscription_View_Order_Url();

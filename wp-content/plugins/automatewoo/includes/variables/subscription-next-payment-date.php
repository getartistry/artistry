<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Subscription_Next_Payment_Date
 */
class Variable_Subscription_Next_Payment_Date extends Variable_Abstract_Datetime {


	function load_admin_details() {
		parent::load_admin_details();
		$this->description = __( "Displays the subscription next payment date in your website timezone.", 'automatewoo') . ' ' . $this->_desc_format_tip;
	}


	/**
	 * @param $subscription \WC_Subscription
	 * @param $parameters
	 * @return string
	 */
	function get_value( $subscription, $parameters ) {
		return $this->format_datetime( $subscription->get_date( 'next_payment', 'site' ), $parameters );
	}
}

return new Variable_Subscription_Next_Payment_Date();

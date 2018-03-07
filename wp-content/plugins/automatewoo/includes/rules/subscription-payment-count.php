<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Subscription_Payment_Count
 */
class AW_Rule_Subscription_Payment_Count extends AutomateWoo\Rules\Abstract_Number {

	public $data_item = 'subscription';

	public $support_floats = false;


	function init() {
		$this->title = __( 'Subscription Payment Count', 'automatewoo' );
		$this->group = __( 'Subscription', 'automatewoo' );
	}


	/**
	 * @param $subscription WC_Subscription
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $subscription, $compare, $value ) {
		return $this->validate_number( $subscription->get_completed_payment_count(), $compare, $value );
	}

}

return new AW_Rule_Subscription_Payment_Count();

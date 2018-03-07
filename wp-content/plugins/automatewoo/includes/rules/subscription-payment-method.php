<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Subscription_Payment_Method
 */
class AW_Rule_Subscription_Payment_Method extends AutomateWoo\Rules\Abstract_Select {

	public $data_item = 'subscription';


	function init() {
		$this->title = __( 'Subscription Payment Method', 'automatewoo' );
		$this->group = __( 'Subscription', 'automatewoo' );
	}


	function load_select_choices() {
		$choices = [];

		foreach( WC()->payment_gateways()->get_available_payment_gateways() as $gateway ) {
			if ( $gateway->supports('subscriptions') ) {
				$choices[$gateway->id] = $gateway->get_title();
			}
		}

		return $choices;
	}


	/**
	 * @param $subscription WC_Subscription
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $subscription, $compare, $value ) {
		return $this->validate_select( AutomateWoo\Compat\Subscription::get_payment_method( $subscription ), $compare, $value );
	}

}

return new AW_Rule_Subscription_Payment_Method();

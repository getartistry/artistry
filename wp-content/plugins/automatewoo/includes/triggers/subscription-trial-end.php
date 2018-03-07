<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Trial_End
 * @since 2.1.0
 */
class Trigger_Subscription_Trial_End extends Trigger_Abstract_Subscriptions {

	public $name = 'subscription_trial_end';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Subscription Trial End', 'automatewoo' );
	}


	function load_fields() {
		$this->add_field_subscription_products();
	}


	function register_hooks() {
		add_action( 'woocommerce_scheduled_subscription_trial_end', [ $this, 'trigger_for_subscription' ], 20, 1 );
	}



	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$user = $workflow->data_layer()->get_user();
		$subscription = $workflow->data_layer()->get_subscription();

		if ( ! $user || ! $subscription )
			return false;

		if ( ! $this->validate_subscription_products_field( $workflow ) )
			return false;

		return true;
	}

}

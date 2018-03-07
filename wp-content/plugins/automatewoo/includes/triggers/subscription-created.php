<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Created
 * @since 3.0.0
 */
class Trigger_Subscription_Created extends Trigger_Abstract_Subscriptions {


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Subscription Created', 'automatewoo' );
	}


	function load_fields() {
		$this->add_field_subscription_products();
	}


	function register_hooks() {
		add_action( 'automatewoo/async/subscription_created', [ $this, 'trigger_for_subscription' ] );
	}


	/**
	 * @param $workflow Workflow
	 * @return bool
	 */
	function validate_workflow( $workflow ) {

		$subscription = $workflow->data_layer()->get_subscription();

		if ( ! $subscription ) {
			return false;
		}

		if ( ! $this->validate_subscription_products_field( $workflow ) ) {
			return false;
		}

		return true;
	}

}

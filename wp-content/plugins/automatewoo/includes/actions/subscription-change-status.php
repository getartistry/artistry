<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Subscription_Change_Status
 * @since 2.1.0
 */
class Action_Subscription_Change_Status extends Action {

	public $required_data_items = [ 'subscription' ];


	function load_admin_details() {
		$this->title = __( 'Change Subscription Status', 'automatewoo' );
		$this->group = __( 'Subscriptions', 'automatewoo' );
	}


	function load_fields() {

		$status = new Fields\Subscription_Status( false );
		$status->set_name( 'status' );
		$status->set_required();

		$this->add_field($status);
	}


	function run() {

		$subscription = $this->workflow->data_layer()->get_subscription();
		$status = Clean::string( $this->get_option('status') );

		if ( ! $status || ! $subscription )
			return;

		$subscription->update_status( $status, sprintf(
			__( 'Subscription status changed by AutomateWoo Workflow #%s.', 'automatewoo' ),
			$this->workflow->get_id()
		));
	}

}

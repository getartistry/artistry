<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Customer_Factory;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for the subscription before renewal trigger
 */
class Subscription_Before_Renewal extends Base {

	/** @var string  */
	public $action = 'subscription_before_renewal';


	/**
	 * @param array $data
	 * @return mixed
	 */
	protected function task( $data ) {

		$subscription = isset( $data['subscription_id'] ) ? wcs_get_subscription( absint( $data['subscription_id'] ) ) : false;
		$workflow = isset( $data['workflow_id'] ) ? AW()->get_workflow( absint( $data['workflow_id'] ) ) : false;

		if ( ! $subscription || ! $workflow ) {
			return false;
		}

		$workflow->maybe_run([
			'subscription' => $subscription,
			'customer' => Customer_Factory::get_by_user_id( $subscription->get_user_id() )
		]);

		return false;
	}

}

return new Subscription_Before_Renewal();

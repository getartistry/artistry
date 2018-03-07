<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Payment_Failed
 */
class Trigger_Subscription_Payment_Failed extends Trigger_Abstract_Subscriptions {


	function __construct() {
		parent::__construct();
		$this->supplied_data_items[] = 'order';
	}


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Subscription Renewal Payment Failed', 'automatewoo' );
	}


	function load_fields() {
		$this->add_field_subscription_products();
	}


	function register_hooks() {
		add_action( 'woocommerce_subscription_renewal_payment_failed', [ $this, 'trigger' ], 20, 2 );
	}


	/**
	 * @param \WC_Subscription $subscription
	 * @param \WC_Order $order
	 */
	function trigger( $subscription, $order ) {
		if ( ! $subscription || ! $order ) {
			return;
		}

		$this->maybe_run([
			'subscription' => $subscription,
			'order' => $order,
			'customer' => Customer_Factory::get_by_user_id( $subscription->get_user_id() )
		]);
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

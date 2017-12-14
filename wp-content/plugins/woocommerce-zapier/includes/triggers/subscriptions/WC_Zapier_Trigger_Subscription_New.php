<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Zapier_Trigger_Subscription_New extends WC_Zapier_Trigger_Subscription {

	public function __construct() {
		$this->trigger_title = __( 'Subscription Created', 'wc_zapier' );

		$this->trigger_description = __( 'Triggers when a subscription is created, either via the Checkout or via the REST API.', 'wc_zapier' );

		// Prefix the trigger key with wc. to denote that this is a trigger that relates to a WooCommerce order
		$this->trigger_key = 'wc.new_subscription';

		$this->sort_order = 4;

		// Subscriptions created via the WooCommerce checkout process
		// This hook accepts 3 parameters, but we only need the first one (the WC_Subscription object).
		// The first parameter is the WC_Subscription object, which we need (and is converted to a subscription ID).
		// The second parameter is the WC_Order object, which we don't need.
		// The third parameter is the WC_Cart object, which we don't need.
		$this->actions['woocommerce_checkout_subscription_created'] = 1;

		// Subscriptions created via the WooCommerce REST API
		// This hook accepts 2 parameters, but we only need the first one (the subscription ID).
		// The first parameter is the subscription ID (an integer).
		// The second parameter is the WC_API_Subscriptions object, which we don't need.
		$this->actions['wcs_api_subscription_created'] = 1;

		parent::__construct();
	}

}
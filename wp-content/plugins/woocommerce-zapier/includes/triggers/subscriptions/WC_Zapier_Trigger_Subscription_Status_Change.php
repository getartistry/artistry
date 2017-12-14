<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Zapier_Trigger_Subscription_Status_Change extends WC_Zapier_Trigger_Subscription {

	public function __construct() {
		$this->trigger_title = __( 'Subscription Status Changed', 'wc_zapier' );

		$this->trigger_description = sprintf( __( 'Advanced: triggers every time a subscription changes status.<br />Consider using with a Filter.<br />See the <a href="%1$s" target="_blank">Advanced Zaps documentation</a> for more information.', 'wc_zapier' ), 'https://docs.woocommerce.com/document/woocommerce-zapier/#advancedzaps' );

		// Prefix the trigger key with wc. to denote that this is a trigger that relates to a WooCommerce order
		$this->trigger_key = 'wc.subscription_status_change';

		$this->sort_order = 9;

		// This hook accepts 3 parameters, and we need all of them.
		// The first parameter is the WC_Subscription object, which we need (and is converted to a subscription ID).
		// The second parameter is the new status (a string).
		// The third parameter is the old/previous status (a string).
		$this->actions['woocommerce_subscription_status_updated'] = 3;

		parent::__construct();
	}

}
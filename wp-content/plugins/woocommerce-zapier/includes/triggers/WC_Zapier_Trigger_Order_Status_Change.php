<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Zapier_Trigger_Order_Status_Change extends WC_Zapier_Trigger_Order {

	public function __construct() {

		// Prefix the trigger key with wc. to denote that this is a trigger that relates to a WooCommerce order
		$this->trigger_key         = 'wc.order_status_change';

		$this->sort_order = 3;

		$this->trigger_title       = __( 'New Order Status Change', 'wc_zapier' );

		$this->trigger_description = sprintf( __( 'Advanced: triggers every time an order changes status.<br />Consider using with a Filter.<br />See the <a href="%1$s" target="_blank">Advanced Zaps documentation</a> for more information.', 'wc_zapier' ), 'https://docs.woocommerce.com/document/woocommerce-zapier/#advancedzaps' );

		// This hook accepts 3 parameters, and we need all of them.
		// The first parameter is the Order ID (an integer).
		// The second parameter is the old/previous status (a string).
		// The third parameter is the new status (a string).
		$this->actions[ 'woocommerce_order_status_changed' ] = 3;

		parent::__construct();
	}

}
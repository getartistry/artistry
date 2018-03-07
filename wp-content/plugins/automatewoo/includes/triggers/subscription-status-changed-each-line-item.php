<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Subscription_Status_Changed_Each_Line_Item
 * @since 2.9
 */
class Trigger_Subscription_Status_Changed_Each_Line_Item extends Trigger_Subscription_Status_Changed {

	public $is_run_for_each_line_item = true;


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Subscription Status Changed - Each Line Item', 'automatewoo' );
		$this->description = __( 'This trigger runs for every line item of a subscription when the status changes. Using this trigger allows access to the product data of the subscription line item.', 'automatewoo' );
	}


	/**
	 * @param int $subscription_id
	 * @param string $new_status
	 * @param string $old_status
	 */
	function catch_hooks( $subscription_id, $new_status, $old_status ) {
		Temporary_Data::set( 'old_status', $subscription_id, $old_status );
		$this->trigger_for_each_subscription_line_item( $subscription_id );
	}


}

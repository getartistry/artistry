<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_Payment_Received_Each_Line_Item
 */
class Trigger_Order_Paid_Each_Line_Item extends Trigger_Abstract_Order_Base {

	public $is_run_for_each_line_item = true;


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Paid - Each Line Item', 'automatewoo' );
		$this->description = __( 'Triggers for each order line item at the end of the payment process after the order status has been changed and stock has been reduced.', 'automatewoo' );
	}


	function register_hooks() {
		add_action( $this->get_hook_order_paid(), [ $this, 'trigger_for_each_order_item' ] );
	}

}

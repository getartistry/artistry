<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_Refunded
 */
class Trigger_Order_Refunded extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'refunded';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __('Order Refunded', 'automatewoo');
	}

}

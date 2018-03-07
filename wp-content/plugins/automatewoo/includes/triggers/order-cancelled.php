<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_Cancelled
 */
class Trigger_Order_Cancelled extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'cancelled';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __('Order Cancelled', 'automatewoo');
	}


}

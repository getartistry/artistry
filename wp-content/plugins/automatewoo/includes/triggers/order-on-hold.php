<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_On_Hold
 */
class Trigger_Order_On_Hold extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'on-hold';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __('Order On Hold', 'automatewoo');
	}


}

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_Processing
 */
class Trigger_Order_Processing extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'processing';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Processing', 'automatewoo' );
	}

}

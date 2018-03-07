<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_Completed
 */
class Trigger_Order_Completed extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'completed';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Completed', 'automatewoo' );
	}

}

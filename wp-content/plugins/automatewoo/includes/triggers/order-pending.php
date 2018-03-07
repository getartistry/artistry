<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Trigger_Order_Pending
 */
class Trigger_Order_Pending extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'pending';


	function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Pending Payment', 'automatewoo' );
	}

}

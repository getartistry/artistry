<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_User_Run_Count
 */
class AW_Rule_Order_Run_Count extends AutomateWoo\Rules\Abstract_Number {

	public $data_item = 'order';

	public $support_floats = false;


	function init() {
		$this->title = __( "This Workflow's Run Count For Order", 'automatewoo' );
		$this->group = __( 'Workflow', 'automatewoo' );
	}


	/**
	 * @param WC_Order $order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		if ( ! $workflow = $this->get_workflow() )
			return false;

		return $this->validate_number( $workflow->get_run_count_for_order( $order ), $compare, $value );
	}

}

return new AW_Rule_Order_Run_Count();

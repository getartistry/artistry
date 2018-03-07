<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Run_Count
 */
class Customer_Run_Count extends Abstract_Number {

	public $data_item = 'customer';

	public $support_floats = false;


	function init() {
		$this->title = __( "This Workflow's Run Count For Customer", 'automatewoo' );
		$this->group = __( 'Workflow', 'automatewoo' );
	}


	/**
	 * @param \AutomateWoo\Customer $customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {

		if ( ! $workflow = $this->get_workflow() )
			return false;

		return $this->validate_number( $workflow->get_run_count_for_customer( $customer ), $compare, $value );
	}

}

return new Customer_Run_Count();

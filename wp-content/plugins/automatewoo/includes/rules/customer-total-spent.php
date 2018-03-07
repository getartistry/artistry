<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Total_Spent
 */
class Customer_Total_Spent extends Abstract_Number {

	public $data_item = 'customer';

	public $support_floats = true;


	function init() {
		$this->title = __( 'Customer Total Spent', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		return $this->validate_number( $customer->get_total_spent(), $compare, $value );
	}

}

return new Customer_Total_Spent();

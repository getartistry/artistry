<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_City
 */
class Customer_City extends Abstract_String {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer City', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		// order data takes precedence
		if ( $order = $this->get_workflow()->data_layer()->get_order() ) {
			$city = Compat\Order::get_billing_city( $order );
		}
		else {
			$city = $customer->get_billing_city();
		}

		return $this->validate_string( $city, $compare, $value );
	}

}

return new Customer_City();

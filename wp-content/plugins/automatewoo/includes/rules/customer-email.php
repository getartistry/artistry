<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Email
 */
class Customer_Email extends Abstract_String {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer Email', 'automatewoo' );
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
			$email = Compat\Order::get_billing_email( $order );
		}
		else {
			$email = $customer->get_email();
		}

		return $this->validate_string( $email, $compare, $value );
	}

}

return new Customer_Email();

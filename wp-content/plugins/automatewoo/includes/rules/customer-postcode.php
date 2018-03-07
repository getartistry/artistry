<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Postcode
 */
class Customer_Postcode extends Abstract_String {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer Postcode', 'automatewoo' );
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
			$postcode = Compat\Order::get_billing_postcode( $order );
		}
		else {
			$postcode = $customer->get_billing_postcode();
		}

		return $this->validate_string( $postcode, $compare, $value );
	}

}

return new Customer_Postcode();

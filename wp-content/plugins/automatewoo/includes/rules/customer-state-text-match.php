<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_State_Text_Match
 */
class Customer_State_Text_Match extends Abstract_String {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer State - Text Match', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		// order takes precedence
		if ( $order = $this->get_workflow()->data_layer()->get_order() ) {
			$country = Compat\Order::get_billing_country( $order );
			$state = Compat\Order::get_billing_state( $order );
		}
		else {
			$country = $customer->get_billing_country();
			$state = $customer->get_billing_state();
		}

		return $this->validate_string( aw_get_state_name( $country, $state ), $compare, $value );
	}

}

return new Customer_State_Text_Match();

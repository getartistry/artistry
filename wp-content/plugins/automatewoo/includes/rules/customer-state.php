<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_State
 */
class Customer_State extends Abstract_Select {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer State', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		$return = [];

		foreach ( WC()->countries->get_states() as $country_code => $states ) {
			foreach ( $states as $state_code => $state_name ) {
				$return[ "$country_code|$state_code" ] = aw_get_country_name( $country_code ) . ' - ' . $state_name;
			}
		}

		return $return;
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

		return $this->validate_select( "$country|$state", $compare, $value );
	}

}

return new Customer_State();

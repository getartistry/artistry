<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Country
 */
class Customer_Country extends Abstract_Select {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer Country', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return WC()->countries->get_allowed_countries();
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
			$country = Compat\Order::get_billing_country( $order );
		}
		else {
			$country = $customer->get_billing_country();
		}

		return $this->validate_select( $country, $compare, $value );
	}

}

return new Customer_Country();

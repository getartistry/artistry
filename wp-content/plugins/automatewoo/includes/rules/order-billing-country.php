<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Billing_Country
 */
class Order_Billing_Country extends Abstract_Select {

	public $data_item = 'order';


	function init() {
		$this->title = __( 'Order Billing Country', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return WC()->countries->get_allowed_countries();
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		$country = Compat\Order::get_billing_country( $order );

		if ( ! $country )
			return false;

		return $this->validate_select( $country, $compare, $value );
	}

}

return new Order_Billing_Country();

<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Order_Statuses
 */
class Customer_Order_Statuses extends Abstract_Select {

	public $data_item = 'customer';

	public $is_multi = true;


	function init() {
		$this->title = __( "Customer's Current Order Statuses", 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
		unset( $this->compare_types[ 'matches_all' ] );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return wc_get_order_statuses();
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {

		$orders = wc_get_orders([
			'customer' => $customer->is_registered() ? $customer->get_user_id() : $customer->get_email(),
			'limit' => -1
		]);

		$statuses = [];
		foreach ( $orders as $order ) {
			/** @var $order \WC_Order */
			$statuses[] = 'wc-' . $order->get_status();
		}

		return $this->validate_select( $statuses, $compare, $value );
	}

}

return new Customer_Order_Statuses();

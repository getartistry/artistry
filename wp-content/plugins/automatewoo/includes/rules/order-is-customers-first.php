<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Is_Customers_First
 */
class AW_Rule_Order_Is_Customers_First extends AutomateWoo\Rules\Abstract_Bool {

	public $data_item = 'order';


	function init() {
		$this->title = __( "Order Is Customer's First?", 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @param $order WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		$user_id = $order->get_user_id();
		$billing_email = AutomateWoo\Compat\Order::get_billing_email( $order );

		$orders = wc_get_orders([
			'customer' => $user_id ? $user_id : $billing_email,
			'limit' => 1,
			'return' => 'ids',
			'exclude' => [ AutomateWoo\Compat\Order::get_id( $order ) ]
		]);

		$is_first = empty( $orders );

		switch ( $value ) {
			case 'yes':
				return $is_first;
				break;

			case 'no':
				return ! $is_first;
				break;
		}
	}

}

return new AW_Rule_Order_Is_Customers_First();

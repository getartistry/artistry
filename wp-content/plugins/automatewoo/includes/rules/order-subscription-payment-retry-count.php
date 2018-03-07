<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Subscription_Payment_Retry_Count
 */
class Order_Subscription_Payment_Retry_Count extends Abstract_Number {

	public $data_item = 'order';

	public $support_floats = false;


	function init() {
		$this->title = __( 'Order Subscription Payment Retry Count', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {
		$count = \WCS_Retry_Manager::store()->get_retry_count_for_order( Compat\Order::get_id( $order ) );
		return $this->validate_number( $count, $compare, $value );
	}


}

return new Order_Subscription_Payment_Retry_Count();

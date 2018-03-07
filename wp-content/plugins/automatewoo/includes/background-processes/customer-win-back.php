<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Customer_Factory;
use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for the customer win back trigger
 */
class Customer_Win_Back extends Base {

	/** @var string  */
	public $action = 'customer_win_back';


	/**
	 * @param array $data
	 * @return mixed
	 */
	protected function task( $data ) {

		$customer = isset( $data['customer_id'] ) ? Customer_Factory::get( absint( $data['customer_id'] ) ) : false;
		$workflow = isset( $data['workflow_id'] ) ? AW()->get_workflow( absint( $data['workflow_id'] ) ) : false;

		if ( ! $customer || ! $workflow ) {
			return false;
		}

		// make the customer's last order object available for this trigger
		$orders = wc_get_orders([
			'customer' => $customer->get_user_id(),
			'status' => apply_filters( 'automatewoo/customer/last_order_date_statuses', Compat\Order::get_paid_statuses() ),
			'limit' => 1
		]);

		if ( empty( $orders ) ) {
			return false; // don't run if customer has no orders
		}

		$workflow->maybe_run([
			'customer' => $customer,
			'order' => current( $orders )
		]);

		return false;
	}

}

return new Customer_Win_Back();

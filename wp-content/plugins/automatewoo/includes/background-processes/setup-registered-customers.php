<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Customer_Factory;
use AutomateWoo\Compat;
use AutomateWoo\Events;

if ( ! defined( 'ABSPATH' ) ) exit;

class Setup_Registered_Customers extends Base {

	/** @var string  */
	public $action = 'setup_registered_customers';


	/**
	 * @param int $user_id
	 * @return mixed
	 */
	protected function task( $user_id ) {

		if ( ! $customer = Customer_Factory::get_by_user_id( absint( $user_id ) ) ) {
			return false;
		}

		update_user_meta( $user_id, '_automatewoo_customer_id', $customer->get_id() );

		// set the last purchase date
		$orders = wc_get_orders([
			'type' => 'shop_order',
			'status' => [ 'completed', 'processing' ],
			'limit' => 1,
			'customer' => $user_id,
			'orderby' => 'date',
			'order' => 'DESC'
		]);

		if ( $orders ) {
			if ( $date_created = Compat\Order::get_date_created( $orders[0], true ) ) {
				$customer->set_date_last_purchased( $date_created );
				$customer->save();
			}
		}

		return false;
	}


	/**
	 * Process completed, trigger action
	 */
	protected function complete() {
		parent::complete();
		Events::schedule_async_event( 'automatewoo_after_setup_registered_customers' );
	}

}

return new Setup_Registered_Customers();

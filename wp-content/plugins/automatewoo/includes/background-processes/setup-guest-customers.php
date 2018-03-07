<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Customer_Factory;
use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

class Setup_Guest_Customers extends Base {

	/** @var string  */
	public $action = 'setup_guest_customers';


	/**
	 * @param int $order_id
	 * @return mixed
	 */
	protected function task( $order_id ) {

		if ( ! $order = wc_get_order( $order_id ) ) {
			return false;
		}

		if ( ! $customer = Customer_Factory::get_by_order( $order ) ) {
			return false;
		}

		if ( $guest = $customer->get_guest() ) {
			$guest->set_ip( Compat\Order::get_customer_ip( $order ) );
			$guest->save();
		}

		if ( ! $customer->get_date_last_purchased() ) {

			// set the last purchase date
			$orders = wc_get_orders([
				'type' => 'shop_order',
				'status' => [ 'completed', 'processing' ],
				'limit' => 1,
				'customer' => $customer->get_email(),
				'orderby' => 'date',
				'order' => 'DESC',
			]);

			if ( $orders ) {
				if ( $date_created = Compat\Order::get_date_created( $orders[0], true ) ) {
					$customer->set_date_last_purchased( $date_created );
					$customer->save();
				}
			}
		}

		return false;
	}


	/**
	 * Process completed, trigger action
	 */
	protected function complete() {
		parent::complete();
		update_option( '_automatewoo_setup_guest_customers_complete', true, false );
	}

}

return new Setup_Guest_Customers();

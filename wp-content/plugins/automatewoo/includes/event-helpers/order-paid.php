<?php

namespace AutomateWoo\Event_Helpers;

use AutomateWoo\Compat;
use AutomateWoo\Events;

/**
 * Event to fire when an order is first paid, supports payments by invoice, cheque, bank etc
 *
 * @class Order_Paid
 * @since 3.2.2
 */
class Order_Paid {


	static function init() {
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'check_status_change' ], 50, 3 );
	}


	/**
	 * @param int $order_id
	 * @param $old_status
	 * @param $new_status
	 */
	static function check_status_change( $order_id, $old_status, $new_status ) {

		if ( ! $order_id || ! $order = wc_get_order( $order_id ) ) {
			return;
		}

		if ( in_array( $old_status, Compat\Order::get_paid_statuses() ) ) {
			return;
		}

		if ( ! in_array( $new_status, Compat\Order::get_paid_statuses() ) ) {
			return;
		}

		if ( Compat\Order::get_meta( $order, '_aw_is_paid' ) ) {
			return;
		}

		Compat\Order::update_meta( $order, '_aw_is_paid', true );

		do_action( 'automatewoo/order/paid', $order );
		Events::schedule_async_event( 'automatewoo/order/paid_async', [ $order_id ] );
	}

}

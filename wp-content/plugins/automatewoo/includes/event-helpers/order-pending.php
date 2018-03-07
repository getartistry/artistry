<?php

namespace AutomateWoo\Event_Helpers;

use AutomateWoo\Events;

/**
 * @class Order_Pending
 */
class Order_Pending {


	static function init() {
		add_action( 'automatewoo/order/created', [ __CLASS__, 'schedule_pending_check' ] );
		add_action( 'automatewoo_check_for_pending_order', [ __CLASS__, 'do_pending_check' ] );
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'maybe_clear_scheduled_check' ], 10, 3 );
	}


	/**
	 * @param $order_id int
	 */
	static function schedule_pending_check( $order_id ) {
		$delay = apply_filters( 'automatewoo_order_pending_check_delay', 5 ) * 60;
		Events::schedule_async_event( 'automatewoo_check_for_pending_order', [ $order_id ], $delay  );
	}


	/**
	 * @param int $order_id
	 * @param string $old_status
	 * @param string $new_status
	 */
	static function maybe_clear_scheduled_check( $order_id, $old_status, $new_status ) {
		if ( $old_status === 'pending' ) {
			Events::clear_scheduled_hook( 'automatewoo_check_for_pending_order', [ $order_id ] );
		}
	}


	/**
	 * @param $order_id int
	 */
	static function do_pending_check( $order_id ) {

		if ( ! $order_id || ! $order = wc_get_order( $order_id ) ) {
			return;
		}

		if ( $order->has_status( 'pending' ) ) {
			do_action( 'automatewoo_order_pending', $order_id );
		}
	}

}

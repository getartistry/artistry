<?php

namespace AutomateWoo\Event_Helpers;

use AutomateWoo\Events;

/**
 * @class Order_Status_Changed
 */
class Order_Status_Changed {


	static function init() {
		add_action( 'woocommerce_order_status_changed', [ __CLASS__, 'status_changed' ], 50, 3 );
	}


	/**
	 * @param int $order_id
	 * @param string|bool $old_status
	 * @param string|bool $new_status
	 */
	static function status_changed( $order_id, $old_status = false, $new_status = false ) {
		do_action( 'automatewoo/order/status_changed', $order_id, $old_status, $new_status );
		Events::schedule_async_event( 'automatewoo/order/status_changed_async', [ $order_id, $old_status, $new_status ] );
	}

}

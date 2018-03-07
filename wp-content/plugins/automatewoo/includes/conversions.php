<?php

namespace AutomateWoo;

/**
 * @class Conversions
 * @since 2.1
 */
class Conversions {


	/**
	 * Max number of days that a purchase to be considered a conversion
	 * @return int
	 */
	static function get_conversion_window() {
		return absint( apply_filters( 'automatewoo_conversion_window', AW()->options()->conversion_window ) );
	}


	/**
	 * @param $order_id
	 */
	static function check_order_for_conversion( $order_id ) {

		if ( ! $order = wc_get_order( $order_id ) ) {
			return;
		}

		if ( ! $customer = Customer_Factory::get_by_order( $order ) ) {
			return;
		}

		if ( ! $conversion_window_end = new \DateTime( Compat\Order::get_date_created( $order, true ) )) {
			return;
		}

		$conversion_window_start = clone $conversion_window_end;
		$conversion_window_start->modify( '-' . self::get_conversion_window() . ' days' );

		if ( ! $logs = self::get_logs_by_customer( $customer, $conversion_window_start, $conversion_window_end ) ) {
			return;
		}

		// check that at least one log shows that it has been opened i.e. has tracking data
		foreach ( $logs as $log ) {

			if ( ! $log->get_meta( 'tracking_data' ) ) {
				continue;
			}

			// has tracking data so mark the order as a conversion
			Compat\Order::update_meta( $order, '_aw_conversion', $log->get_workflow_id() );
			Compat\Order::update_meta( $order, '_aw_conversion_log', $log->get_id() );

			break; // break loop so we only mark one log as converted
		}
	}


	/**
	 * @param Customer $customer
	 * @param \DateTime $conversion_window_start
	 * @param \DateTime $conversion_window_end
	 * @return Log[]
	 */
	static function get_logs_by_customer( $customer, $conversion_window_start, $conversion_window_end ) {

		$fuzzy_match = [];

		$fuzzy_match[] = [
			'key' => '_data_layer_customer',
			'value' => $customer->get_id()
		];

		$fuzzy_match[] = [
			'key' => 'guest_email',
			'value' => $customer->get_email()
		];

		if ( $customer->is_registered() ) {
			$fuzzy_match[] = [
				'key' => 'user_id',
				'value' => $customer->get_user_id()
			];
		}

		$query = ( new Log_Query() )
			->set_ordering('date', 'DESC')
			->where( 'conversion_tracking_enabled', true )
			->where( 'date', $conversion_window_start, '>' )
			->where( 'date', $conversion_window_end, '<' );

		$query->where_meta[] = $fuzzy_match;

		return $query->get_results();
	}

}

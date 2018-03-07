<?php

namespace AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Subscription
 * @since 2.9
 */
class Subscription extends Order {


	/**
	 * @param \WC_Subscription $subscription
	 * @param bool $gmt
	 * @return string
	 */
	static function get_date_created( $subscription, $gmt = false ) {
		$timezone = $gmt ? 'gmt' : 'site';

		if ( version_compare( \WC_Subscriptions::$version, '2.2.0', '<' ) ) {
			return $subscription->get_date( 'start', $timezone );
		}
		else {
			return $subscription->get_date( 'date_created', $timezone );
		}
	}


	/**
	 * @param \WC_Subscription $subscription
	 * @param bool $gmt
	 * @return string
	 */
	static function get_date_last_order_created( $subscription, $gmt = false ) {
		$timezone = $gmt ? 'gmt' : 'site';

		if ( version_compare( \WC_Subscriptions::$version, '2.2.0', '<' ) ) {
			return $subscription->get_date( 'last_payment', $timezone );
		}
		else {
			return $subscription->get_date( 'last_order_date_created', $timezone );
		}
	}


}

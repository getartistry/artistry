<?php

namespace AutomateWoo\Event_Helpers;

use AutomateWoo\Compat;
use AutomateWoo\Events;

/**
 * @class Subscription_Created
 */
class Subscription_Created {


	static function init() {
		add_action( 'woocommerce_checkout_subscription_created', [ __CLASS__, 'subscription_created' ], 20, 1 );
		add_action( 'wcs_api_subscription_created', [ __CLASS__, 'subscription_created' ], 20, 1 );
	}


	/**
	 * @param mixed $subscription
	 */
	static function subscription_created( $subscription ) {

		if ( is_numeric( $subscription ) ) {
			$subscription = wcs_get_subscription( $subscription );
		}

		if ( ! $subscription ) {
			return;
		}

		Events::schedule_async_event( 'automatewoo/async/subscription_created', [ Compat\Subscription::get_id( $subscription ) ] );
	}


}
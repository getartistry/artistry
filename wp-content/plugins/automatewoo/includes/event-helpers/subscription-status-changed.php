<?php

namespace AutomateWoo\Event_Helpers;

use AutomateWoo\Compat;
use AutomateWoo\Events;

/**
 * @class Subscription_Status_Changed
 */
class Subscription_Status_Changed {

	/** @var bool */
	public static $_doing_payment = false;


	static function init() {
		// Whenever a renewal payment is due subscription is placed on hold and then back to active if successful
		// Block this trigger while this happens
		add_action( 'woocommerce_scheduled_subscription_payment', [ __CLASS__, 'before_payment' ], 0, 1 );
		add_action( 'woocommerce_scheduled_subscription_payment', [ __CLASS__, 'after_payment' ], 1000, 1 );

		add_action( 'woocommerce_subscription_status_updated', [ __CLASS__, 'status_changed' ], 10, 3 );
	}


	/**
	 * @param $subscription_id
	 */
	static function before_payment( $subscription_id ) {
		self::$_doing_payment = true;
	}


	/**
	 * @param $subscription_id
	 */
	static function after_payment( $subscription_id ) {

		self::$_doing_payment = false;

		$subscription = wcs_get_subscription( $subscription_id );

		if ( $subscription && ! $subscription->has_status( 'active' ) ) {
			// if status was changed (no longer active) during payment trigger now
			self::status_changed( $subscription, $subscription->get_status(), 'active' );
		}
	}


	/**
	 * @param \WC_Subscription $subscription
	 * @param string $new_status
	 * @param string $old_status
	 */
	static function status_changed( $subscription, $new_status, $old_status ) {

		if ( self::$_doing_payment || ! $subscription ) {
			return;
		}

		do_action( 'automatewoo/subscription/status_changed', Compat\Subscription::get_id( $subscription ), $new_status, $old_status );

		Events::schedule_async_event( 'automatewoo/subscription/status_changed_async', [ Compat\Subscription::get_id( $subscription ), $new_status, $old_status ] );
	}

}

<?php

namespace AutomateWoo;

/**
 * @class Subscription_Helper
 * @since 2.8.2
 */
class Subscription_Helper {

	/**
	 * @param \WC_Subscription $subscription
	 * @return \WP_User|bool
	 */
	static function prepare_user_data( $subscription ) {

		if ( ! $subscription || ! Integrations::subscriptions_enabled() ) {
			return false;
		}

		$user = $subscription->get_user();

		if ( ! $user ) {
			return false;
		}

		// ensure first and last name are set
		if ( ! $user->first_name ) $user->first_name = Compat\Subscription::get_billing_first_name( $subscription );
		if ( ! $user->last_name ) $user->last_name = Compat\Subscription::get_billing_last_name( $subscription );
		if ( ! $user->billing_phone ) $user->billing_phone = Compat\Subscription::get_billing_phone( $subscription );

		return $user;
	}

}

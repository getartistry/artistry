<?php

namespace AutomateWoo;

/**
 * @class Memberships_Helper
 * @since 2.8.3
 */
class Memberships_Helper {

	/**
	 * @return array
	 */
	static function get_membership_plans() {
		$options = [];

		foreach( wc_memberships_get_membership_plans() as $plan ) {
			$options[ $plan->get_id() ] = $plan->get_name();
		}

		return $options;
	}


	/**
	 * Get statuses without status prefix
	 * @return array
	 */
	static function get_membership_statuses() {
		$statuses = [];

		foreach ( wc_memberships_get_user_membership_statuses() as $status => $value ) {
			$status = 0 === strpos( $status, 'wcm-' ) ? substr( $status, 4 ) : $status;
			$statuses[ $status ] = $value['label'];
		}

		return $statuses;
	}


	/**
	 * @param \WC_Memberships_User_Membership $membership
	 * @return \WP_User|bool
	 */
	static function get_user_data( $membership ) {

		if ( ! $membership || ! Integrations::is_memberships_enabled() ) {
			return false;
		}

		$user = get_userdata( $membership->get_user_id() );

		if ( ! $user ) {
			return false;
		}

		return $user;
	}

}

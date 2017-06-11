<?php

class ITSEC_Password_Expiration {

	private $settings;

	function run() {

		$this->settings = ITSEC_Modules::get_settings( 'password-expiration' );

		add_action( 'user_profile_update_errors', array( $this, 'validate_valid_password' ), 11 ); //make sure to clear password nag
		add_action( 'validate_password_reset', array( $this, 'validate_valid_password' ), 11 ); //make sure to clear password nag if reseting
		add_action( 'wp_login', array( $this, 'wp_login' ), 10, 2 ); //set meta if they need to change their password
		add_action( 'current_screen', array( $this, 'admin_init' ) ); //redirect to profile page and show a require password change nag

	}

	/**
	 * Process redirection of all dashboard pages for password reset
	 *
	 * @since 1.8
	 *
	 * @return void
	 */
	public function admin_init() {

		if ( isset( get_current_screen()->id ) && ( 'profile' === get_current_screen()->id || 'profile-network' === get_current_screen()->id ) ) {

			$current_user = wp_get_current_user();

			if ( isset( $current_user->ID ) && $current_user->ID !== 0 ) { //make sure we have a valid user

				$required = get_user_meta( $current_user->ID, 'itsec_password_change_required', true );

				if ( $required == true ) {

//					wp_safe_redirect( admin_url( 'profile.php?itsec_password_expired=true#pass1' ) );
//					exit();

				}

			}

		}

	}

	/**
	 * Check for errors in password submission and update meta accordingly
	 *
	 * This will run whether password expiration is used directly or not to make it easier for users to handle later
	 *
	 * @since 1.8
	 *
	 * @param object $errors WordPress errors
	 *
	 * @return object WordPress error object
	 *
	 **/
	public function validate_valid_password( $errors ) {

		global $itsec_globals;

		$user = wp_get_current_user();

		if ( $user instanceof WP_User ) {

			if ( wp_check_password( isset( $_POST['pass1'] ) ? $_POST['pass1'] : '', isset( $user->data->user_pass ) ? $user->data->user_pass : false, $user->ID ) ) {
				$errors->add( 'pass', __( '<strong>ERROR</strong>: The password you have chosen appears to have been used before. You must choose a new password.', 'it-l10n-ithemes-security-pro' ) );
			}

			if ( is_wp_error( $errors ) && empty( $errors->errors ) && isset( $_POST['pass1'] ) && strlen( trim( $_POST['pass1'] ) ) > 0 ) {

				$current_user = get_current_user_id();

				delete_user_meta( $current_user, 'itsec_password_change_required' );
				update_user_meta( $current_user, 'itsec_last_password_change', $itsec_globals['current_time_gmt'] );

			}

		}

		return $errors;

	}

	/**
	 * Handle redirection to password change form on login
	 *
	 * @since 1.8
	 *
	 * @param string $username the username attempted
	 * @param        object    wp_user the user
	 *
	 * @return bool|void false on failure
	 */
	public function wp_login( $username, $user = null ) {

		global $itsec_globals;

		//Get a valid user or terminate the hook (all we care about is forcing the password change... Let brute force protection handle the rest
		if ( $user !== null ) {

			$current_user = $user;

		} elseif ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();

		} else {

			return false;

		}

		//determine the minimum role for enforcement
		$min_role = isset( $this->settings['expire_role'] ) ? $this->settings['expire_role'] : 'administrator';

		//all the standard roles and level equivalents
		$available_roles = array(
			'administrator' => '8',
			'editor'        => '5',
			'author'        => '2',
			'contributor'   => '1',
			'subscriber'    => '0'
		);

		$allowed_expire = false;

		foreach ( $current_user->roles as $capability ) {

			if ( isset( $available_roles[ $capability ] ) && $available_roles[ $capability ] >= $available_roles[ $min_role ] ) {
				$allowed_expire = true;
			}

		}

		if ( $allowed_expire === true ) {

			$last_change = get_user_meta( $current_user->ID, 'itsec_last_password_change', true );

			if ( isset( $this->settings['expire_force'] ) && $this->settings['expire_force'] > 0 ) {

				$oldest_allowed = $this->settings['expire_force'];

			} else {

				$oldest_allowed = $itsec_globals['current_time_gmt'] - ( isset( $this->settings['expire_max'] ) ? absint( $this->settings['expire_max'] ) * 86400 : 10368000 );

			}

			if (
				$last_change === false || //They've never changed their password (at least not since the feature was added)
				$last_change <= $oldest_allowed //they haven't changed their password since before the admin required a forced reset
			) {

				update_user_meta( $current_user->ID, 'itsec_password_change_required', true );

			}

		}

	}

}

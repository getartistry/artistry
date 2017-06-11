<?php
/**
 * WooCommerce Social Login
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Social Login to newer
 * versions in the future. If you wish to customize WooCommerce Social Login for your
 * needs please refer to http://docs.woothemes.com/document/woocommerce-social-login/ for more information.
 *
 * @package   WC-Social-Login/Providers
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2016, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Disqus social login provider class
 *
 * @since 1.6.0
 */
class WC_Social_Login_Provider_Disqus extends WC_Social_Login_Provider {


	/**
	 * Constructor for the provider.
	 *
	 * @param string $base_auth_path base authentication path
	 */
	public function __construct( $base_auth_path ) {

		$this->id                = 'disqus';
		$this->title             = __( 'Disqus', 'woocommerce-social-login' );
		$this->strategy_class    = 'SVDisqus';
		$this->color             = '#2e9fff';
		$this->require_ssl       = false;
		$this->internal_callback = 'oauth2callback';

		$this->notices = array(
			'account_linked'         => __( 'Your Disqus account is now linked to your account.', 'woocommerce-social-login' ),
			'account_unlinked'       => __( 'Disqus account was successfully unlinked from your account.', 'woocommerce-social-login' ),
			'account_already_linked' => __( 'This Disqus account is already linked to another user account.', 'woocommerce-social-login' ),
			'account_already_exists' => __( 'A user account using the same email address as this Disqus account already exists.', 'woocommerce-social-login' ),
		);

		parent::__construct( $base_auth_path );

		// normalize profile
		add_filter( 'wc_social_login_' . $this->get_id() . '_profile', array( $this, 'normalize_profile' ) );

	}


	/**
	 * Get the description
	 *
	 * @since 1.6.0
	 * @see WC_Social_Login_Provider::get_description()
	 * @return string
	 */
	public function get_description() {

		return sprintf( __( 'Need help setting up and configuring Disqus? %sRead the docs%s', 'woocommerce-social-login' ), '<a href="http://docs.woothemes.com/document/woocommerce-social-login-create-social-apps#disqus">', '</a>' );
	}


	/**
	 * Return the providers opAuth config
	 *
	 * @since 1.6.0
	 * @return array
	 */
	public function get_opauth_config() {

		/**
		 * Filter provider's Opauth configuration.
		 *
		 * @since 1.6.0
		 * @param array $config See https://github.com/opauth/opauth/wiki/Opauth-configuration - Strategy
		 */
		return apply_filters( 'wc_social_login_' . $this->get_id() . '_opauth_config', array(
			'redirect_uri'      => $this->get_callback_url(),
			'strategy_class'    => $this->get_strategy_class(),
			'strategy_url_name' => $this->get_id(),
			'api_key'           => $this->get_client_id(),
			'api_secret'        => $this->get_client_secret(),
			'scope'             => 'read,email',
		) );
	}


	/**
	 * Override the default form fields to tweak the title for the client ID/secret
	 * so it matches Disqus's UI
	 *
	 * @since 1.6.0
	 * @see WC_Social_Login_Provider::init_form_fields()
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields['id']['title']     = __( 'API Key', 'woocommerce-social-login' );
		$this->form_fields['secret']['title'] = __( 'API Secret', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.6.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_login_button_text() {

		return __( 'Log in with Disqus', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.6.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_link_button_text() {

		return __( 'Link your account to Disqus', 'woocommerce-social-login' );
	}

	/**
	 * Disqus returns a `name`, so try to map it to `first_name` & `last_name`
	 *
	 * @since 1.6.0
	 * @param array $profile Disqus profile data
	 * @return array Profile after our mappings
	 */
	public function normalize_profile( $profile ) {

		// Disqus only provides the 'name' so we need to try to split this to 'first_name' & 'last_name'
		// but we do not want to overwrite the 'first_name' & 'last_name' if they are already set
		$profile_types = array( 'raw', 'info' );

		foreach ( $profile_types as $type ) {

			if ( isset( $profile[ $type ]['name'] ) ) {

				$name = explode( ' ', $profile[ $type ]['name'] );

				if ( ! isset( $profile[ $type ]['first_name'] ) ) {
					// slice the last element
					$profile[ $type ]['first_name'] = implode( ' ', array_slice( $name, 0, count( $name ) - 1 ) );
				}

				if ( ! isset( $profile[ $type ]['last_name'] ) ) {
					// get the last element
					$profile[ $type ]['last_name'] = array_pop( $name );
				}
			}
		}

		return $profile;
	}
}

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
 * Twitter social login provider class
 *
 * @since 1.0
 */
class WC_Social_Login_Provider_Twitter extends WC_Social_Login_Provider {


	/**
	 * Constructor for the provider.
	 *
	 * @param string $base_auth_path base authentication path
	 */
	public function __construct( $base_auth_path ) {

		$this->id                = 'twitter';
		$this->title             = __( 'Twitter', 'woocommerce-social-login' );
		$this->strategy_class    = 'SVTwitter';
		$this->color             = '#00aced';
		$this->internal_callback = 'oauth_callback';
		$this->require_ssl       = false;

		$this->notices = array(
			'account_linked'         => __( 'Your Twitter account is now linked to your account.', 'woocommerce-social-login' ),
			'account_unlinked'       => __( 'Twitter account was successfully unlinked from your account.', 'woocommerce-social-login' ),
			'account_already_linked' => __( 'This Twitter account is already linked to another user account.', 'woocommerce-social-login' ),
			'account_already_exists' => __( 'A user account using the same email address as this Twitter account already exists.', 'woocommerce-social-login' ),
		);

		parent::__construct( $base_auth_path );

		// normalize profile
		add_filter( 'wc_social_login_' . $this->get_id() . '_profile', array( $this, 'normalize_profile' ) );
	}


	/**
	 * Get the provider's description
	 *
	 * Individual providers may override this to provide specific instructions,
	 * like displaying a callback URL
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::get_description()
	 * @return string strategy class
	 */
	public function get_description() {

		/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
		return sprintf( __( 'Need help setting up and configuring Twitter? %1$sRead the docs%2$s', 'woocommerce-social-login' ), '<a href="http://docs.woothemes.com/document/woocommerce-social-login-create-social-apps#twitter">', '</a>' . '<br/><br/>' . sprintf( /* translators: %s - a url */ __( 'The callback URL is %s', 'woocommerce-social-login' ), '<code>' . $this->get_callback_url() . '</code>' ) );
	}


	/**
	 * Override parent check to ensure cURL is available, as Twitter
	 * strategy requires it.
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::is_available()
	 * @return bool
	 */
	public function is_available() {

		return parent::is_available() && extension_loaded( 'curl' );
	}


	/**
	 * Return the providers opAuth config
	 *
	 * @since 1.0
	 * @return array
	 */
	public function get_opauth_config() {

		/**
		 * Filter provider's Opauth configuration.
		 *
		 * @since 1.0
		 * @param array $config See https://github.com/opauth/opauth/wiki/Opauth-configuration - Strategy
		 */
		return apply_filters( 'wc_social_login_' . $this->get_id() . '_opauth_config', array(
			'oauth_callback'     => $this->get_callback_url(),
			'strategy_class'    => $this->get_strategy_class(),
			'strategy_url_name' => $this->get_id(),
			'key'               => $this->get_client_id(),
			'secret'            => $this->get_client_secret(),
		) );
	}


	/**
	 * Override the default form fields to tweak the title for the client ID/secret
	 * so it matches Twitter's UI
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::init_form_fields()
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields['id']['title']     = __( 'Consumer Key', 'woocommerce-social-login' );
		$this->form_fields['secret']['title'] = __( 'Consumer Secret', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_login_button_text() {

		return __( 'Log in with Twitter', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_link_button_text() {

		return __( 'Link your account to Twitter', 'woocommerce-social-login' );
	}


	/**
	 * Twitter returns a `name`, so try to map it to `first_name` & `last_name`
	 *
	 * @since 1.1.0
	 * @param array $profile twitter profile data
	 * @return array
	 */
	public function normalize_profile( $profile ) {

		// Twitter only provides the 'name' so we need to try to split this to 'first_name' & 'last_name'
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

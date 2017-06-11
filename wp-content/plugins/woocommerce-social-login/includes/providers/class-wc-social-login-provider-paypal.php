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
 * PayPal social login provider class
 *
 * @since 1.1.0
 */
class WC_Social_Login_Provider_PayPal extends WC_Social_Login_Provider {


	/**
	 * Constructor for the provider.
	 *
	 * @since 1.1.0
	 * @param string $base_auth_path base authentication path
	 */
	public function __construct( $base_auth_path ) {

		$this->id                = 'paypal';
		$this->title             = __( 'PayPal', 'woocommerce-social-login' );
		$this->strategy_class    = 'SVPayPal';
		$this->color             = '#00457C';
		$this->internal_callback = 'oauth2callback';
		$this->require_ssl       = false;

		$this->notices = array(
			'account_linked'         => __( 'Your PayPal account is now linked to your account.', 'woocommerce-social-login' ),
			'account_unlinked'       => __( 'PayPal account was successfully unlinked from your account.', 'woocommerce-social-login' ),
			'account_already_linked' => __( 'This PayPal account is already linked to another user account.', 'woocommerce-social-login' ),
			'account_already_exists' => __( 'A user account using the same email address as this PayPal account already exists.', 'woocommerce-social-login' ),
		);

		parent::__construct( $base_auth_path );
	}


	/**
	 * Get the description, overridden to display the callback URL
	 * as a convenience since PayPal requires the admin to enter it for the app
	 *
	 * @since 1.1.0
	 * @see WC_Social_Login_Provider::get_description()
	 * @return string
	 */
	public function get_description() {

		/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
		return sprintf( __( 'Need help setting up and configuring PayPal? %1$sRead the docs%2$s', 'woocommerce-social-login' ), '<a href="http://docs.woothemes.com/document/woocommerce-social-login-create-social-apps#paypal">', '</a>' ) . '<br/><br/>' . sprintf( /* translators: %s - a url */ __( 'The App redirect URL is %s', 'woocommerce-social-login' ), '<code>' . $this->get_callback_url() . '</code>' );
	}


	/**
	 * Return the providers opAuth config
	 *
	 * @since 1.1.0
	 * @return array
	 */
	public function get_opauth_config() {

		/**
		 * Filter provider's Opauth configuration.
		 *
		 * @since 1.1.0
		 * @param array $config See https://github.com/opauth/opauth/wiki/Opauth-configuration - Strategy
		 */
		return apply_filters( 'wc_social_login_' . $this->get_id() . '_opauth_config', array(
			'redirect_uri'      => $this->get_callback_url(),
			'strategy_class'    => $this->get_strategy_class(),
			'strategy_url_name' => $this->get_id(),
			'client_id'         => $this->get_client_id(),
			'client_secret'     => $this->get_client_secret(),
			'environment'       => $this->get_option( 'environment' ),
		) );
	}


	/**
	 * Override the default form fields to:
	 *
	 * 1) Add the environment setting
	 * 2) tweak the title for the client ID/secret so it matches PayPal's UI
	 *
	 * @since 1.1.0
	 * @see WC_Social_Login_Provider::init_form_fields()
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields['environment'] = array(
			/* translators: https:www.skyverge.com/for-translators-environments/ */
			'title'    => __( 'Environment', 'woocommerce-social-login' ),
			'type'     => 'select',
			'desc_tip' => __( 'Select which environment to process logins under.', 'woocommerce-social-login' ),
			'options'  => array(
				/* translators: Live (Production) environment - https:www.skyverge.com/for-translators-environments/ */
				'live'    => __( 'Live', 'woocommerce-social-login' ),
				/* translators: Placeholders: Sandbox (Test) environment - https:www.skyverge.com/for-translators-environments/ */
				'sandbox' => __( 'Sandbox', 'woocommerce-social-login' ),
			),
			'default'  => 'live',
		);

		$this->form_fields['id']['title']     = __( 'Client ID', 'woocommerce-social-login' );
		$this->form_fields['secret']['title'] = __( 'Secret', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.1.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_login_button_text() {

		return __( 'Log in with PayPal', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.1.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_link_button_text() {

		return __( 'Link your account to PayPal', 'woocommerce-social-login' );
	}


}

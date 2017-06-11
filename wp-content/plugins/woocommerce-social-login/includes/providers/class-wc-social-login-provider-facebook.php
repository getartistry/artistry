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
 * Facebook social login provider class
 *
 * @since 1.0
 */
class WC_Social_Login_Provider_Facebook extends WC_Social_Login_Provider {


	/**
	 * Constructor for the provider.
	 *
	 * @param string $base_auth_path base authentication path
	 */
	public function __construct( $base_auth_path ) {

		$this->id             = 'facebook';
		$this->title          = __( 'Facebook', 'woocommerce-social-login' );
		$this->strategy_class = 'SVFacebook';
		$this->color          = '#3b5998';
		$this->require_ssl    = false;
		/* translators: Placeholders: %1$s - <a> tag, %2$s - </a> tag */
		$this->description    = sprintf( __( 'Need help setting up and configuring Facebook? %1$sRead the docs%2$s', 'woocommerce-social-login' ), '<a href="http://docs.woothemes.com/document/woocommerce-social-login-create-social-apps#facebook">', '</a>' );

		$this->notices = array(
			'account_linked'         => __( 'Your Facebook account is now linked to your account.', 'woocommerce-social-login' ),
			'account_unlinked'       => __( 'Facebook account was successfully unlinked from your account.', 'woocommerce-social-login' ),
			'account_already_linked' => __( 'This Facebook account is already linked to another user account.', 'woocommerce-social-login' ),
			'account_already_exists' => __( 'A user account using the same email address as this Facebook account already exists.', 'woocommerce-social-login' ),
		);

		parent::__construct( $base_auth_path );
	}


	/**
	 * Get the description
	 *
	 * @since 1.6.0
	 * @see WC_Social_Login_Provider::get_description()
	 * @return string
	 */
	public function get_description() {

		return sprintf( __( 'Need help setting up and configuring Facebook? %sRead the docs%s', 'woocommerce-social-login' ), '<a href="http://docs.woothemes.com/document/woocommerce-social-login-create-social-apps#facebook">', '</a>' );
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
			'redirect_uri'      => $this->get_callback_url(),
			'strategy_class'    => $this->get_strategy_class(),
			'strategy_url_name' => $this->get_id(),
			'app_id'            => $this->get_client_id(),
			'app_secret'        => $this->get_client_secret(),
			'scope'             => 'public_profile, email', // user_location is excluded as it requires approval from Facebook
		) );
	}


	/**
	 * Override the default form fields to tweak the title for the client ID/secret
	 * so it matches Facebook's UI
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::init_form_fields()
	 */
	public function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields['id']['title']     = __( 'App ID', 'woocommerce-social-login' );
		$this->form_fields['secret']['title'] = __( 'App Secret', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_login_button_text() {

		return __( 'Log in with Facebook', 'woocommerce-social-login' );
	}


	/**
	 * Return the default login button text
	 *
	 * @since 1.0
	 * @see WC_Social_Login_Provider::get_default_login_button_text()
	 * @return string
	 */
	public function get_default_link_button_text() {

		return __( 'Link your account to Facebook', 'woocommerce-social-login' );
	}


}

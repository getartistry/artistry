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
 * @package     WC-Social-Login/Includes
 * @author      SkyVerge
 * @copyright   Copyright (c) 2014-2016, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Opauth class
 *
 * @since 1.0
 */
class WC_Social_Login_Opauth {


	/** @var string base authentication path */
	private $base_auth_path;

	/** @var array configuration */
	private $config;


	/**
	 * Constructor
	 *
	 * @param string $base_auth_path base authentication path
	 */
	public function __construct( $base_auth_path ) {

		$this->base_auth_path = $base_auth_path;

		add_action( 'init', array( $this, 'init_config' ), 11 );
		add_action( 'woocommerce_api_' . $base_auth_path . '/callback', array( $this, 'callback' ) );

		// redirect after updating email
		add_filter ( 'wp_redirect', array( $this, 'redirect_after_save_account_details' ) );
	}


	/**
	 * Initialize Opauth configuration
	 *
	 * Initializes Opauth configuration with the configured
	 * strategies. Opauth will be instantiated separately
	 * in the authentication and callback methods, because Opauth
	 * will try to create authentication request instantly when
	 * instantiated.
	 *
	 * @since 1.0
	 */
	public function init_config() {

		$url = parse_url( home_url() );

		$config = array(
			'host'               => sprintf( '%s://%s', ! empty( $url['scheme'] ) ? $url['scheme'] : 'http', $url['host'] ),
			'path'               => sprintf( '%s/wc-api/%s/', ! empty( $url['path'] ) ? $url['path'] : '', $this->base_auth_path ),
			'callback_transport' => 'post',
			'security_salt'      => get_option( 'wc_social_login_opauth_salt' ),
			'Strategy'           => array(),
			'debug'              => true,
		);

		// Loop over available providers and add their configuration
		foreach ( wc_social_login()->get_available_providers() as $provider ) {

			if ( ! $provider->uses_opauth() ) {
				continue;
			}

			$config['Strategy'][ $provider->get_id() ] = $provider->get_opauth_config();
		}

		$this->config = apply_filters( 'wc_social_login_opauth_config', $config );

		// render an error notice if the user has been redirected due to an error
		if ( ! empty( $_GET['wc-social-login-error'] ) ) {

			wc_add_notice( __( 'Provider Authentication error', 'woocommerce-social-login' ), 'error' );

		} elseif ( ! empty( $_GET['wc-social-login-registration-error'] ) ) {

			// this message is copied from wc_create_new_customer()
			wc_add_notice( sprintf( __( '%sERROR%s: Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'woocommerce-social-login' ), '<strong>', '</strong>' ), 'error' );

		} elseif ( ! empty( $_GET['wc-social-login-restricted-role-error'] ) ) {

			wc_add_notice( __( 'Oops, it looks like you may already have an account&hellip; please log in to link your profile.', 'woocommerce-social-login' ), 'error' );
		}

		// render a notice if the user has been redirected to update email address
		if ( ! empty( $_GET['wc-social-login-missing-email'] ) ) {
			wc_add_notice( __( 'Please enter your email address to complete your registration', 'woocommerce-social-login' ), 'notice' );
		}
	}


	/**
	 * Authenticate using Opauth
	 *
	 * Creates an instance of Opauth - this will instantly
	 * create an authentication request based on the current
	 * url route. Expects a url route with the schema {$path}/{$strategy}.
	 *
	 * Providers using Opauth should call this method in their authentication routes
	 *
	 * @since 1.0
	 */
	public function authenticate() {
		new Opauth( $this->config );
		exit;
	}


	/**
	 * Authentication callback
	 *
	 * This method handles the `final` callback from Opauth
	 * to verify the response, handle errors and pass handling
	 * of user profile to the Provider class.
	 *
	 * @since 1.0
	 */
	public function callback() {

		// Create a new Opauth instance without triggering authentication
		$opauth = new Opauth( $this->config, false );

		try {

			// only GET/POST supported
			switch ( $opauth->env['callback_transport'] ) {

				case 'post':
					$response = json_decode( base64_decode( $_POST['opauth'] ), true );
					break;

				case 'get':
					$response = json_decode( base64_decode( $_GET['opauth'] ), true );
					break;

				default:
					throw new Exception( 'Opauth unsupported transport callback' );
			}

			$validation_reason = null;

			// check for error response
			if ( array_key_exists( 'error', $response ) ) {

				throw new Exception( 'Response error' );

			} elseif ( empty( $response['auth'] ) || empty( $response['timestamp'] ) || empty( $response['signature'] ) || empty( $response['auth']['provider'] ) || empty( $response['auth']['uid'] ) ) {

				// ensure required data
				throw new Exception( 'Invalid auth response - missing required components' );

			} elseif ( ! $opauth->validate( sha1( print_r( $response['auth'], true ) ), $response['timestamp'], $response['signature'], $validation_reason ) ) {

				// validate response has not been modified
				throw new Exception( sprintf( 'Invalid auth response - %s', $validation_reason ) );
			}

		} catch ( Exception $e ) {

			// log error messages and response data
			wc_social_login()->log( sprintf( 'Error: %s, Response: %s', $e->getMessage(), print_r( $response, true ) ) );

			$this->redirect( 'error' );
		}

		// valid response, get provider
		$provider = wc_social_login()->get_provider( strtolower( $response['auth']['provider'] ) );

		$profile = new WC_Social_Login_Provider_Profile( $response['auth'] );

		// Let the provider handle processing user profile and logging in
		$user_id = $provider->process_profile( $profile );

		if ( is_wp_error( $user_id ) ) {

			// Redirect back with an error
			$this->redirect( 'error', 0, $user_id->get_error_code() );
		} else {

			// Redirect back to where we came from
			$this->redirect( null, $user_id );
		}
	}


	/**
	 * Redirect back to the provided return_url
	 *
	 * @since 1.0
	 * @param string $type redirect type, currently only `error` or null
	 * @param int $user_id the user ID. Default 0.
	 * @param string $error_code URL parameter used to display error message after redirect.
	 */
	public function redirect( $type = null, $user_id = 0, $error_code = 'wc-social-login-error' ) {

		$user = get_user_by( 'id', $user_id );

		if ( isset( $user->user_email ) && '' === $user->user_email ) {
			$return_url = add_query_arg( 'wc-social-login-missing-email', 'true', wc_customer_edit_account_url() );
		} else {
			$return_url = get_transient( 'wcsl_' . md5( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) );
			$return_url = $return_url ? esc_url( urldecode( $return_url ) ) : wc_get_page_permalink( 'myaccount' );
			delete_transient( 'wcsl_' . md5( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) );
		}

		if ( 'error' === $type ) {

			// using a query arg because wc_add_notice() only works when the WC session is available
			// which is only on the cart/checkout pages
			$return_url = add_query_arg( $error_code, 'true', $return_url );
		}

		wp_redirect( esc_url_raw( $return_url ) );
		exit;
	}

	/**
	 * Redirect back to the provided return_url
	 *
	 * @since 1.2.0
	 * @param string $redirect_location
	 * @param string $redirect_location
	 * @return string URL
	 */
	public function redirect_after_save_account_details( $redirect_location ) {

		$safe_redirect_location = wc_get_page_permalink( 'myaccount' );
		$safe_redirect_location = wp_sanitize_redirect( $safe_redirect_location );
		$safe_redirect_location = wp_validate_redirect( $safe_redirect_location, admin_url() );

		if ( $redirect_location === $safe_redirect_location && $new_location = get_transient( 'wcsl_' . md5( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) ) ) {
			$redirect_location = $new_location;
			delete_transient( 'wcsl_' . md5( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ) );
		}

		return $redirect_location;
	}


} // end \WC_Social_Login_Opauth class

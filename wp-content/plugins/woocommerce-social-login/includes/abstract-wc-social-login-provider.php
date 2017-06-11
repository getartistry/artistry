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
 * @package     WC-Social-Login/Abstracts
 * @author      SkyVerge
 * @copyright   Copyright (c) 2014-2016, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Abstact social login provider class
 *
 * @since 1.0
 */
abstract class WC_Social_Login_Provider extends WC_Settings_API {


	/** @var string The plugin ID. Used for option names. */
	public $plugin_id = 'wc_social_login_';

	/** @var string Social Login provider ID. */
	public $id;

	/** @var string Provider title. Shown in admin */
	protected $title;

	/** @var string Provider description. Shown in admin */
	protected $description;

	/** @var string 'yes' if the provider is enabled. */
	public $enabled;

	/** @var string Login button text. */
	protected $button_text;

	/** @var bool Whether this provider uses Opauth or not. */
	protected $use_opauth = true;

	/** @var string Opauth strategy class name, eg `Facebook`. */
	protected $strategy_class;

	/** @var string Opauth-specific internal callback path */
	protected $internal_callback = 'int_callback';

	/** @var boolean true if this provider requires SSL for authentication, false otherwise */
	protected $require_ssl;

	/** @var string Provider color. Used in admin reports. */
	protected $color;

	/** @var array provider-specific notices displayed to users */
	protected $notices;


	/**
	 * Constructor
	 *
	 * @param string $base_auth_path base authentication path
	 */
	public function __construct( $base_auth_path ) {

		// define and load provider settings
		$this->init_form_fields();
		$this->init_settings();


		// Add admin actions
		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_social_login_' . $this->id, array( $this, 'process_admin_options' ) );
		}

		// Add opauth-specific API methods
		if ( $this->use_opauth ) {
			// Opauth expects 2 routes for each provider: {$path}/{$provider} and
			// {$path}/{$provider}/int_callback (or oauth_callback) (for internal callbacks)
			// Both should instanciate Opauth, which will take over from there.
			add_action( 'woocommerce_api_' . $base_auth_path . '/' . $this->id, array( $this, 'authenticate' ) );
			add_action( 'woocommerce_api_' . $base_auth_path . '/' . $this->id . '/' . $this->internal_callback, array( $this, 'authenticate' ) );
		}

		// Remove social profile from logged-in user
		add_action( 'woocommerce_api_' . $base_auth_path . '/unlink/' . $this->id, array( $this, 'unlink_account' ) );
	}


	/**
	 * Render provider settings and description
	 *
	 * @since 1.0
	 */
	public function admin_options() {

		?><h3><?php echo esc_html( $this->get_title() ); ?></h3><?php

		echo wpautop( $this->get_description() );

		?>
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table>
		<?php
	}


	/**
	 * Define default provider settings fields
	 *
	 * @since 1.0
	 * @return array
	 */
	public function init_form_fields() {

		/**
		 * Filter default provider settings form fields
		 *
		 * @since 1.0
		 * @param array $form_fields
		 */
		$this->form_fields = apply_filters( 'wc_social_login_provider_default_form_fields',
			array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woocommerce-social-login' ),
				'type'    => 'checkbox',
				/* translators: Placeholders: %s - social login provider name/title, eg: Facebook, Amazon, etc. */
				'label'   => sprintf( __( 'Enable %s', 'woocommerce-social-login' ), $this->get_title() ),
				'default' => 'no',
			),
			'id' => array(
				/* translators: Client (app) ID for a Social Login provider, used for identifying and authenticating the app (in our case, the WooCommerce store). This is NOT a WooCommerce customer ID */
				'title'       => __( 'Client ID', 'woocommerce-social-login' ),
				'type'        => 'text',
				'description' => __( 'Your app ID', 'woocommerce-social-login' ),
				'desc_tip'    => true,
			),
			'secret' => array(
				/* translators: Client (app) secret for a Social Login provider, used for identifying and authenticating the app (in our case, the WooCommerce store). */
				'title'       => __( 'Client Secret', 'woocommerce-social-login' ),
				'type'        => 'password',
				'description' => __( 'Your app secret', 'woocommerce-social-login' ),
				'desc_tip'    => true,
			),
			'login_button_text' => array(
				'title'       => __( 'Login Button Text', 'woocommerce-social-login' ),
				'type'        => 'text',
				'description' => __( 'Controls the text displayed on the login button.', 'woocommerce-social-login' ),
				'desc_tip'    => true,
				'default'     => $this->get_default_login_button_text(),
			),
			'link_button_text' => array(
				'title'       => _x( 'Link Button Text', 'noun', 'woocommerce-social-login' ),
				'type'        => 'text',
				'description' => __( 'Controls the text displayed on the link account button.', 'woocommerce-social-login' ),
				'desc_tip'    => true,
				'default'     => $this->get_default_link_button_text(),
			),
		), $this->get_id() );
	}


	/**
	 * Check if the provider is available for use
	 *
	 * A provider is available when it's enabled and configured
	 *
	 * @since 1.0
	 * @return bool true if the provider is available, false otherwise
	 */
	public function is_available() {

		$is_available = ( $this->is_enabled() && $this->is_configured() );

		/**
		 * Filter whether the provider is available or not.
		 *
		 * @since 1.0
		 * @param bool $enabled True if enabled, false otherwise
		 * @param WC_Social_Login_Provider $provider Social Login provider
		 */
		return apply_filters( 'wc_social_login_provider_available', $is_available, $this );
	}


	/**
	 * Checks if a provider is enabled
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function is_enabled() {

		return 'yes' === $this->enabled;
	}


	/**
	 * Checks if a provider is configured
	 *
	 * By default, id and secret are the only required fields
	 *
	 * @since 1.0
	 * @return bool
	 */
	public function is_configured() {

		return $this->get_option( 'id' ) && $this->get_option( 'secret' );
	}


	/**
	 * Returns true if this provider requires SSL to function properly
	 *
	 * @since 1.0
	 * @return boolean true if this provider requires ssl
	 */
	public function requires_ssl() {

		return $this->require_ssl;
	}


	/**
	 * Returns true if this provider uses Opauth
	 *
	 * @since 1.0
	 * @return boolean true if this provider uses Opauth
	 */
	public function uses_opauth() {

		return $this->use_opauth;
	}


	/**
	 * Authenticate the user using the social login provider
	 *
	 * The default implementation uses Opauth to handle the
	 * authentication, but providers can override this with custom
	 * implementations.
	 *
	 * @since 1.0
	 */
	public function authenticate() {

		// Store return URL in WC session, as Opauth does not
		// provide a way to pass around custom query vars
		if ( isset( $_GET['return'] ) ) {
			set_transient( 'wcsl_' . md5( $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] ), $_GET['return'], 5 * MINUTE_IN_SECONDS );
		}

		wc_social_login()->opauth->authenticate();
	}


	/**
	 * Process authenticated user's profile
	 *
	 * @since 1.0
	 * @param WC_Social_Login_Provider_profile $profile
	 * @return int the user ID
	 */
	public function process_profile( $profile ) {
		global $wpdb;

		$user         = null;
		$found_via    = null;
		$new_customer = false;

		// Look up if the user already exists on WP

		// First, try to identify user based on the social identifier
		$user_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = %s AND meta_value = %s", '_wc_social_login_' . $this->id . '_uid', $profile->get_uid() ) );

		if ( $user_id ) {

			$user = get_user_by( 'id', $user_id );

			if ( $user ) {
				$found_via = 'uid';
			}
		}

		// Fall back to email - user may already have an account on WooCommerce with the
		// same email as in their social profile
		if ( ! $user && $profile->has_email() ) {

			$user = get_user_by( 'email', $profile->get_email() );

			if ( $user ) {
				$found_via = 'email';
			}
		}

		// If a user is already logged in...
		if ( is_user_logged_in() ) {

			// ...and a user matching the social profile was found,
			// check that the logged in user and found user are the same.
			// This happens when user is linking a new social profile to their account.
			if ( $user && get_current_user_id() !== $user->ID ) {

				if ( 'uid' === $found_via ) {
					wc_add_notice( $this->get_notice_text( 'account_already_linked' ), 'error' );
				} else {
					wc_add_notice( $this->get_notice_text( 'account_already_exists' ), 'error' );
				}

				return 0;
			}

			// If the social profile is not linked to any user accounts,
			// use the currently logged in user as the customer
			if ( ! $user ) {
				$user = get_user_by( 'id', get_current_user_id() );
			}
		}

		// Check if a user is found via email and not it one of the allowed roles
		if ( $user && 'email' === $found_via && ! in_array( $user->roles[0], apply_filters( 'wc_social_login_find_by_email_allowed_user_roles', array( 'subscriber', 'customer' ) ) ) ) {
			return new WP_Error( 'wc-social-login-restricted-role-error', __( 'An account with this email address already exists and has a restricted role.', 'woocommerce-social-login' ) );
		}

		// If no user was found, create one
		if ( ! $user ) {
			$user_id = $this->create_new_customer( $profile );

			if ( is_wp_error( $user_id ) ) {

				// log error messages and response data
				wc_social_login()->log( sprintf( 'Error: %s, Response: %s', 'registration-error', $user_id->get_error_message( 'registration-error' ) ) );

				return new WP_Error( 'wc-social-login-registration-error', $user_id->get_error_message( 'registration-error' ) );
			}

			$user = get_user_by( 'id', $user_id );

			// indicate that a new user was created
			$new_customer = true;
		}

		// Update customer's WP user profile and billing details
		$profile->update_customer_profile( $user->ID, $new_customer );

		// Log user in or add account linked notice for a logged in user
		if ( ! is_user_logged_in() ) {

			if ( ! $message = apply_filters( 'wc_social_login_set_auth_cookie', '', $user ) ) {

				wc_set_customer_auth_cookie( $user->ID );

				// Store login timestamp
				update_user_meta( $user->ID, '_wc_social_login_' . $this->get_id() . '_login_timestamp', current_time( 'timestamp' ) );
				update_user_meta( $user->ID, '_wc_social_login_' . $this->get_id() . '_login_timestamp_gmt', time() );

				/**
				 * User authenticated via social login.
				 *
				 * @since 1.0
				 * @param int $user_id ID of the user
				 * @param string $provider_id Social Login provider ID
				 */
				do_action( 'wc_social_login_user_authenticated', $user->ID, $this->get_id() );

			} else {

				wc_add_notice( $message, 'notice' );
			}

		} else {

			wc_add_notice( $this->get_notice_text( 'account_linked' ), 'notice' );
		}

		return $user->ID;
	}


	/**
	 * Create a WP user from the provider's data
	 *
	 * @since 1.0
	 * @param WC_Social_Login_Provider_profile $profile user profile object
	 * @return int|WP_Error The newly created user's ID or a WP_Error object if the user could not be created.
	 */
	public function create_new_customer( $profile ) {

		/**
		 * Filter data for user created by social login.
		 *
		 * @since 1.0
		 * @param array $userdata
		 * @param WC_Social_Login_Provider_Profile $profile
		 */
		$userdata = apply_filters( 'wc_social_login_' . $this->id . '_new_user_data', array(
			'role'       => 'customer',
			'user_login' => $profile->has_email() ? sanitize_email( $profile->get_email() ) : $profile->get_nickname(),
			'user_email' => $profile->get_email(),
			'user_pass'  => wp_generate_password(),
			'first_name' => $profile->get_first_name(),
			'last_name'  => $profile->get_last_name(),
		), $profile );

		// ensure username is not blank - if it is, use first and last name to generate a username
		if ( empty( $userdata['user_login'] ) ) {
			$userdata['user_login'] = $userdata['first_name'] . $userdata['last_name'];
		}

		// Ensure username is unique
		$append     = 1;
		$o_username = $userdata['user_login'];

		while ( username_exists( $userdata['user_login'] ) ) {
			$userdata['user_login'] = $o_username . $append;
			$append ++;
		}

		$customer_id = wp_insert_user( $userdata );

		if ( is_wp_error( $customer_id ) ) {
			return new WP_Error( 'registration-error', '<strong>' . __( 'ERROR', 'woocommerce-social-login' ) . '</strong>: ' . __( 'Couldn&#8217;t register you&hellip; please contact us if you continue to have problems.', 'woocommerce-social-login' ) );
		}

		// trigger New Account email
		do_action( 'woocommerce_created_customer', $customer_id, $userdata, false );

		return $customer_id;
	}


	/**
	 * Remove/unlink the social login profile from the currently logged in user
	 *
	 * @since 1.0
	 */
	public function unlink_account() {

		if ( ! $user_id = get_current_user_id() ) {
			return;
		}

		// remove all metas related to this social profile, except for the profile image
		delete_user_meta( $user_id, '_wc_social_login_' . $this->id . '_uid' );
		delete_user_meta( $user_id, '_wc_social_login_' . $this->id . '_profile' );
		delete_user_meta( $user_id, '_wc_social_login_' . $this->id . '_profile_full' );
		delete_user_meta( $user_id, '_wc_social_login_' . $this->id . '_login_timestamp' );
		delete_user_meta( $user_id, '_wc_social_login_' . $this->id . '_login_timestamp_gmt' );

		// unlink the profile image
		$this->unlink_profile_image( $user_id, $this->id );

		wc_add_notice( $this->get_notice_text( 'account_unlinked' ), 'notice' );

		/**
		 * User unlinked a social login profile.
		 *
		 * @since 1.0
		 * @param int $user_id ID of the user
		 * @param string $provider_id ID of the Social Login provider that was unlinked
		 */
		do_action( 'wc_social_login_account_unlinked', $user_id, $this->get_id() );

		$return_url = isset( $_GET['return'] ) ? esc_url( urldecode( $_GET['return'] ) ) : get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		wp_redirect( $return_url );
		exit;
	}

	/**
	 * Remove social profile image after unlinking the profile.
	 * Otherwise, we end up with an orphaned URL, possibly 404.
	 *
	 * @since 1.6.0
	 * @param int    $user_id The User ID
	 * @param string $provider_id The Social Profile ID
	 */
	protected function unlink_profile_image( $user_id, $provider_id ) {

		// preserve the value of the profile image being removed before deleting the meta
		$unlinked_image = get_user_meta( $user_id, '_wc_social_login_' . $provider_id . '_profile_image', true );
		delete_user_meta( $user_id, '_wc_social_login_' . $provider_id . '_profile_image' );

		$avatar_image = get_user_meta( $user_id, '_wc_social_login_profile_image', true );

		// check if unlinked image is the current avatar; if so, find a replacement
		if ( $avatar_image === $unlinked_image ) {

			// delete the avatar image
			delete_user_meta( $user_id, '_wc_social_login_profile_image' );

			// check other linked profiles for the replacement image
			foreach ( wc_social_login()->get_user_social_login_profiles( $user_id ) as $profile ) {

				if ( $profile->has_image() ) {
					// A replacement has been found. Set it as the new avatar.
					$profile->update_customer_profile_image( $user_id );
					break;
				}
			}
		}
	}


	/** Getters ******************************************************/


	/**
	 * Get the provider ID, e.g. `facebook`
	 *
	 * @since 1.0
	 * @return string provider ID
	 */
	public function get_id() {

		return $this->id;
	}


	/**
	 * Get the provider title, e.g. 'Facebook'
	 *
	 * @since 1.0
	 * @return string provider title
	 */
	public function get_title() {

		/**
		 * Filter social login provider's title.
		 *
		 * @since 1.0
		 * @param string $title
		 * @param string $provider_id Social Login provider ID
		 */
		return apply_filters( 'wc_social_login_provider_title', $this->title, $this->get_id() );
	}


	/**
	 * Get the provider's app client ID
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_client_id() {

		/**
		 * Filter the provider's app client ID.
		 *
		 * @since 1.0
		 * @param string $client_id
		 * @param string $provider_id Social Login provider ID
		 */
		return apply_filters( 'wc_social_login_provider_client_id', $this->get_option( 'id' ), $this->get_id() );
	}


	/**
	 * Get the provider's app client secret
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_client_secret() {

		/**
		 * Filter the provider's app client secret.
		 *
		 * @since 1.0
		 * @param string $client_secret
		 * @param string $provider_id Social Login provider ID
		 */
		return apply_filters( 'wc_social_login_provider_client_secret', $this->get_option( 'secret' ), $this->get_id() );
	}


	/**
	 * Get the login button text for the provider, e.g. 'Login with Facebook'
	 *
	 * This is admin-configurable
	 *
	 * @since 1.0
	 * @return string login button text
	 */
	public function get_login_button_text() {

		/**
		 * Filter social login provider's login button text.
		 *
		 * @since 1.0
		 * @param string $button_text
		 * @param string $provider_id Social Login provider ID
		 */
		return apply_filters( 'wc_social_login_provider_login_button_text', $this->get_option( 'login_button_text' ), $this->get_id() );
	}


	/**
	 * Return the default login button text. This is implemented by provider
	 * classes to ease translation as the text may vary depending on the
	 * context the provider name is used in.
	 *
	 * @since 1.0
	 * @return string
	 */
	abstract public function get_default_login_button_text();


	/**
	 * Get the link account button text for the provider, e.g. 'Link your account with Facebook'
	 *
	 * This is admin-configurable
	 *
	 * @since 1.0
	 * @return string link button text
	 */
	public function get_link_button_text() {

		/**
		 * Filter social login provider's link button text.
		 *
		 * @since 1.0
		 * @param string $button_text
		 * @param string $provider_id Social Login provider ID
		 */
		return apply_filters( 'wc_social_login_provider_link_button_text', $this->get_option( 'link_button_text' ), $this->get_id() );
	}


	/**
	 * Return the default link button text. This is implemented by provider
	 * classes to ease translation as the text may vary depending on the
	 * context the provider name is used in.
	 *
	 * @since 1.0
	 * @return string
	 */
	abstract public function get_default_link_button_text();


	/**
	 * Get the notice text shown to the user given the specified action
	 *
	 * + `account_linked` - social account successfully linked
	 * + `account_unlinked` - social account removed
	 * + `account_already_linked` - social account already linked to existing WP account
	 * + `account_already_exists` - WP account using the email provided by the provider already exists
	 *
	 * Note that notices are defined per-provider so they can be translated properly,
	 * see https://github.com/skyverge/wc-plugins/commit/59b16ecce9aa20ffa8fe3d0228b3d1640312d8ce
	 *
	 * @since 1.0
	 * @param string $action
	 * @return string notice text
	 */
	public function get_notice_text( $action ) {

		return isset( $this->notices[ $action ] ) ? $this->notices[ $action ] : '';
	}


	/**
	 * Get the provider's color
	 *
	 * @since 1.0
	 * @return string strategy class
	 */
	public function get_color() {

		/**
		 * Filter social login provider's color.
		 *
		 * @since 1.0
		 * @param string $color
		 * @param string $provider_id Social Login provider ID
		 */
		return apply_filters( 'wc_social_login_provider_color',  $this->color, $this->get_id() );
	}


	/**
	 * Get the provider's description
	 *
	 * Individual providers may override this to provide specific instructions,
	 * like displaying a callback URL
	 *
	 * @since 1.0
	 * @return string strategy class
	 */
	public function get_description() {

		return $this->description;
	}


	/**
	 * Get the Opauth Strategy class name, e.g. `Facebook`
	 *
	 * @since 1.0
	 * @return string strategy class
	 */
	public function get_strategy_class() {

		return $this->strategy_class;
	}


	/**
	 * Get the Opauth internal callback, e.g. `int_callback`
	 *
	 * @since 1.0
	 * @return string strategy class
	 */
	public function get_internal_callback() {

		return $this->internal_callback;
	}


	/**
	 * Get the auth URL for logging in with the provider
	 *
	 * Note this forces plain HTTP for the redirect to avoid redirect issues
	 * with SSL, where WC tries to break out of SSL on non-checkout pages
	 *
	 * @since 1.0
	 * @param string $action auth action, either `login` (default) to link account or `unlink` to unlink
	 * @param string $return_url URL to return the user to after authenticating
	 * @return string url
	 */
	public function get_auth_url( $return_url, $action = 'login' ) {

		$auth_path   = wc_social_login()->get_auth_path();
		$action      = ( 'unlink' === $action ) ? "{$action}/" : '';
		$provider_id = esc_attr( $this->get_id() );
		$return_url  = urlencode( $return_url );

		// returns a url like https://www.skyverge.com/wc-api/auth/amazon/?return={return_url}
		return get_home_url( null, "wc-api/{$auth_path}/{$action}{$provider_id}/?return={$return_url}" );
	}


	/**
	 * Get the callback URL for the provider
	 *
	 * For providers that require an explicitly declared callback URL,
	 * use this method to display it in provider settings
	 *
	 * @since 1.0
	 * @return string url
	 */
	public function get_callback_url() {

		$auth_path          = wc_social_login()->get_auth_path();
		$provider_id        = esc_attr( $this->get_id() );
		$internal_callback  = esc_attr( $this->get_internal_callback() );

		$force_ssl = $this->requires_ssl() || 'yes' === get_option( 'wc_social_login_force_ssl_callback_url', 'no' ) || ( apply_filters( 'wc_social_login_force_ssl_callback', false, $this ) );

		// returns a url like http://www.skyverge.com/wc-api/auth/amazon/oauth2callback
		return get_home_url( null, "wc-api/{$auth_path}/{$provider_id}/{$internal_callback}", $force_ssl ? 'https' : 'http' );
	}

	/**
	 * Return the providers opAuth config
	 *
	 * @since 1.0
	 * @return array
	 */
	abstract public function get_opauth_config();


} // end \WC_Social_Login_Provider abstract class

<?php
/**
 * Plugin Name: WooCommerce Social Login
 * Plugin URI: http://www.woocommerce.com/products/woocommerce-social-login/
 * Description: One-click registration and login via social networks like Facebook, Google, Twitter and Amazon
 * Author: SkyVerge
 * Author URI: http://www.woocommerce.com
 * Version: 2.3.2
 * Text Domain: woocommerce-social-login
 * Domain Path: /i18n/languages/
 * Copyright: (c) 2014-2017, SkyVerge, Inc. (info@skyverge.com)
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Social-Login
 * @author    SkyVerge
 * @category  Integration
 * @copyright Copyright (c) 2014-2017, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * Woo: 473617:b231cd6367a79cc8a53b7d992d77525d
 */

defined( 'ABSPATH' ) or exit;

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'woo-includes/woo-functions.php' );
}

// Plugin updates
woothemes_queue_update( plugin_basename( __FILE__ ), 'b231cd6367a79cc8a53b7d992d77525d', '473617' );

// WC active check
if ( ! is_woocommerce_active() ) {
	return;
}

// Required library class
if ( ! class_exists( 'SV_WC_Framework_Bootstrap' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'lib/skyverge/woocommerce/class-sv-wc-framework-bootstrap.php' );
}

SV_WC_Framework_Bootstrap::instance()->register_plugin( '4.6.4', __( 'WooCommerce Social Login', 'woocommerce-social-login' ), __FILE__, 'init_woocommerce_social_login', array(
	'minimum_wc_version'   => '2.5.5',
	'minimum_wp_version'   => '4.1',
	'backwards_compatible' => '4.4',
) );

function init_woocommerce_social_login() {


/**
 * # WooCommerce Social Login Main Plugin Class
 *
 * ## Plugin Overview
 *
 * This plugin allows customers to login and register via social login providers
 * like Facebook, Google, Twitter, etc. The login/register options are presented
 * to customers at checkout and my account page.
 *
 * ## Features
 *
 * + Pick & Choose social login providers you want to support on your site
 * + Customize each provider's icon and button text
 * + Customers do not need to create and remember another password
 * + View social login statistics
 *
 * ## Frontend Considerations
 *
 * On the frontend the social login buttons are rendered on the checkout page
 * and my account, if the customer is not already logged in.
 * A customer can associate their account with multiple social login providers.
 *
 * ### Widget
 *
 * The plugin adds a social login widget that can be added to pages via the
 * standard WordPress Widget admin
 *
 * ### Shortcode
 *
 * The plugin adds a social login shortcode which can be used like:
 *
 * [woocommerce_social_login_buttons return_url='https://www.example.com/my-account']
 *
 * ### Template Function
 *
 * The plugin defines an overrideable "template" function for displaying the
 * social login buttons, and can be used to provide enhanced theme integration,
 * etc.  Example usage:
 *
 * woocommerce_social_login_buttons('https://www.example.com/my-account')
 *
 * ## Admin Considerations
 *
 * Adds a tab to WooCommerce settings page, which lets store managers
 * enable/disable and configure different providers.
 *
 * ## Database
 *
 * ### Options table
 *
 * + `wc_social_login_provider_order` - array of provider id to numerical order
 * + `wc_social_login_version` - the current plugin version, set on install/upgrade
 *
 * ### User Meta
 * + `_wc_social_login_{provider id}_profile` - array of social profile values (email, nickname, name, etc)
 * + `_wc_social_login_{provider id}_identifier` -
 *
 * @since 1.0.0
 */
class WC_Social_Login extends SV_WC_Plugin {


	/** plugin version number */
	const VERSION = '2.3.2';

	/** @var WC_Social_Login single instance of this plugin */
	protected static $instance;

	/** plugin id */
	const PLUGIN_ID = 'social_login';

	/** plugin meta prefix */
	const PLUGIN_PREFIX = 'wc_social_login_';

	/** @var \WC_Social_Login_Admin instance */
	protected $admin;

	/** @var \WC_Social_Login_Frontend instance */
	protected $frontend;

	/** @var \WC_Social_Login_HybridAuth */
	protected $hybridauth;

	/** @var array login providers */
	private $providers;


	/**
	 * Initializes the plugin
	 *
	 * @since 1.0.0
	 * @return \WC_social_login
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'text_domain'        => 'woocommerce-social-login',
				'dependencies'       => array( 'curl' ),
				'display_php_notice' => true,
			)
		);

		// initialize
		add_action( 'init', array( $this, 'init' ) );

		// register widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}


	/**
	 * Autoload Provider classes
	 *
	 * @since 1.0.2
	 * @param string $class class name to load
	 */
	public function autoload( $class ) {

		if ( 0 === stripos( $class, 'wc_social_login_provider_' ) ) {

			$class = strtolower( $class );

			// provider classes
			$path = $this->get_plugin_path() . '/includes/providers/';
			$file = 'class-' . str_replace( '_', '-', $class ) . '.php';

			if ( is_readable( $path . $file ) ) {
				require_once( $path . $file );
			}
		}
	}


	/**
	 * Initialize Social Login
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Frontend includes
		if ( ! is_admin() ) {
			$this->frontend_includes();
		}

		// Admin includes
		if ( is_admin() && ! is_ajax() ) {
			$this->admin_includes();
		}

		// Set profile image avatar
		add_filter( 'get_avatar', array( $this, 'set_profile_image_avatar' ), 10, 2 );

		// Adjust the avatar URL
		add_filter( 'wc_social_login_profile_image', array( $this, 'adjust_avatar_url' ), 0 );
	}


	/**
	 * Include required frontend files
	 *
	 * @since 1.0.0
	 */
	private function frontend_includes() {

		require_once( $this->get_plugin_path() . '/includes/class-wc-social-login-hybridauth.php' );
		$this->hybridauth = new WC_Social_Login_HybridAuth( $this->get_auth_path() );

		require_once( $this->get_plugin_path() . '/includes/wc-social-login-template-functions.php' );

		$this->frontend = $this->load_class( '/includes/frontend/class-wc-social-login-frontend.php', 'WC_Social_Login_Frontend' );
	}


	/**
	 * Include required admin files
	 *
	 * @since 1.0.0
	 */
	private function admin_includes() {

		$this->admin = $this->load_class( '/includes/admin/class-wc-social-login-admin.php', 'WC_Social_Login_Admin' );
	}


	/**
	 * Return the admin class instance
	 *
	 * @since 1.8.0
	 * @return \WC_Social_Login_Admin
	 */
	public function get_admin_instance() {
		return $this->admin;
	}


	/**
	 * Return the frontend class instance
	 *
	 * @since 1.8.0
	 * @return \WC_Social_Login_Frontend
	 */
	public function get_frontend_instance() {
		return $this->frontend;
	}


	/**
	 * Return the hybridauth class instance
	 *
	 * @since 2.0.0
	 * @return \WC_Social_Login_HybridAuth
	 */
	public function get_hybridauth_instance() {
		return $this->hybridauth;
	}


	/**
	 * Backwards compat for changing the visibility of some class instances and
	 * the $providers member.
	 *
	 * @TODO Remove this entire method sometime after May 2017 {MR 2017-02-17}
	 *
	 * @since 1.8.0
	 */
	public function &__get( $name ) {

		switch ( $name ) {

			case 'providers':

				/* @deprecated since 2.0.0 */
				_deprecated_function( 'wc_social_login()->providers', '2.0.0', 'wc_social_login()->get_providers()' );
				return $this->get_providers();
		}

		// you're probably doing it wrong
		trigger_error( 'Call to undefined property ' . __CLASS__ . '::' . $name, E_USER_ERROR );

		return null;
	}


	/**
	 * Return deprecated/removed hooks.
	 *
	 * @since 2.0.0
	 * @return array
	 */
	protected function get_deprecated_hooks() {

		$deprecated_hooks = array();

		$providers = array(
			'amazon',
			'disqus',
			'facebook',
			'google',
			'instagram',
			'linkedin',
			'paypal',
			'twitter',
			'vkontakte',
			'yahoo',
		);

		foreach ( $providers as $provider_id ) {

			$old_id = 'vkontakte' === $provider_id ? 'vk' : $provider_id;

			// no more opauth config
			$deprecated_hooks[ 'wc_social_login_' . $old_id . '_opauth_config' ] = array(
				'version'     => '2.0.0',
				'removed'     => true,
				'replacement' => 'wc_social_login_' . $provider_id . '_hybridauth_config',
			);
		}

		return $deprecated_hooks;
	}


	/** Provider methods ******************************************************/


	/**
	 * load_providers function.
	 *
	 * Loads all social login providers which are hooked in.
	 *
	 * Providers are sorted into their user-defined order after being loaded.
	 *
	 * In 2.0.0 changed visibility from public to private
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function load_providers() {

		// autoload classes
		spl_autoload_register( array( $this, 'autoload' ) );

		// Base social login provider & profile
		require_once( $this->get_plugin_path() . '/includes/abstract-wc-social-login-provider.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-social-login-provider-profile.php' );

		// Providers can register themselves through this hook
		do_action( 'wc_social_login_load_providers' );

		// Register providers through a filter

		/**
		 * Filter the list of providers to load.
		 *
		 * @since 1.0.0
		 * @param array $providers_to_load list of provider classes to load
		 */
		$providers_to_load = apply_filters( 'wc_social_login_providers', array(
			'WC_Social_Login_Provider_Facebook',
			'WC_Social_Login_Provider_Twitter',
			'WC_Social_Login_Provider_Google',
			'WC_Social_Login_Provider_Amazon',
			'WC_Social_Login_Provider_LinkedIn',
			'WC_Social_Login_Provider_PayPal',
			'WC_Social_Login_Provider_Instagram',
			'WC_Social_Login_Provider_Disqus',
			'WC_Social_Login_Provider_Yahoo',
			'WC_Social_Login_Provider_VKontakte',
		) );

		foreach ( $providers_to_load as $provider ) {
			$this->register_provider( $provider );
		}

		$this->sort_providers();

		return $this->providers;
	}


	/**
	 * Register a provider
	 *
	 * @since 1.0.0
	 * @param \WC_Social_Login_Provider|string $provider Either the name of the provider's class, or an instance of the provider's class
	 */
	public function register_provider( $provider ) {

		if ( ! is_object( $provider ) ) {
			$provider = new $provider( $this->get_auth_path() );
		}

		$id = empty( $provider->instance_id ) ? $provider->get_id() : $provider->instance_id;

		$this->providers[ $id ] = $provider;
	}


	/**
	 * Sorts providers into the user defined order
	 *
	 * @since 1.0.0
	 * @return \WC_Social_Login_Provider[]
	 */
	public function sort_providers() {

		$sorted_providers = array();

		// Get order option
		$ordering   = (array) get_option( 'wc_social_login_provider_order' );
		$order_end  = 999;

		// Load shipping providers in order
		foreach ( $this->providers as $provider ) {

			if ( isset( $ordering[ $provider->get_id() ] ) && is_numeric( $ordering[ $provider->get_id() ] ) ) {
				// Add in position
				$sorted_providers[ $ordering[ $provider->get_id() ] ][] = $provider;
			} else {
				// Add to end of the array
				$sorted_providers[ $order_end ][] = $provider;
			}
		}

		ksort( $sorted_providers );

		$this->providers = array();

		foreach ( $sorted_providers as $providers ) {
			foreach ( $providers as $provider ) {
				$id = empty( $provider->instance_id ) ? $provider->get_id() : $provider->instance_id;
				$this->providers[ $id ] = $provider;
			}
		}

		return $this->providers;
	}


	/**
	 * Returns the authentication base path, defaults to `auth`
	 *
	 * e.g.: skyverge.com/wc-api/auth/facebook
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_auth_path() {

		/**
		 * Filter the authentication base path.
		 *
		 * @since 1.0.0
		 * @param string $auth_path the authentication base path
		 */
		return apply_filters( 'wc_social_login_auth_path', 'auth' );
	}


	/**
	 * Returns the callback URL format
	 *
	 * TODO: remove this method when removing backwards compatibility
	 * with OpAuth-style callbacks {IT 2016-10-12}
	 *
	 * @since 2.0.0
	 * @return string, one of `default` or `legacy`
	 */
	public function get_callback_url_format() {

		$url_format = get_option( 'wc_social_login_callback_url_format' );

		return 'legacy' === $url_format ? 'legacy' : 'default';
	}


	/**
	 * Returns all registered providers for usage
	 *
	 * @since 1.0.0
	 * @return \WC_Social_Login_Provider[]
	 */
	public function get_providers() {

		if ( ! isset( $this->providers ) ) {
			$this->load_providers();
		}

		return $this->providers;
	}


	/**
	 * Returns the requested provider, if found.
	 *
	 * @since 1.0.0
	 * @param string $provider_id
	 * @return \WC_Social_Login_Provider|null
	 */
	public function get_provider( $provider_id ) {

		$providers = $this->get_providers();

		return isset( $providers[ $provider_id ] ) ? $providers[ $provider_id ] : null;
	}


	/**
	 * Get available providers
	 *
	 * @since 1.0.0
	 * @return \WC_Social_Login_Provider[]
	 */
	public function get_available_providers() {

		$_available_providers = array();

		foreach ( $this->get_providers() as $provider ) {

			if ( $provider->is_available() ) {
				$_available_providers[ $provider->get_id() ] = $provider;
			}
		}

		/**
		 * Filter the available providers
		 *
		 * @since 1.0.0
		 * @param array $_available_providers the available providers
		 */
		return apply_filters( 'wc_social_login_available_providers', $_available_providers );
	}


	/** Admin providers ******************************************************/


	/**
	 * Render a notice for the user to read the docs before configuring
	 *
	 * @since 1.1.0
	 * @see SV_WC_Plugin::add_delayed_admin_notices()
	 */
	public function add_delayed_admin_notices() {

		// show any dependency notices
		parent::add_delayed_admin_notices();

		// add notice to read the documentation
		if ( $this->is_plugin_settings() ) {

			$this->get_admin_notice_handler()->add_admin_notice(
				/* translators: Placeholders: %1$s - opening HTML <a> tag, %2$s - closing HTML </a> tag */
				sprintf( __( 'Thanks for installing Social Login! Before you get started, please take a moment to %1$sread through the documentation%2$s.', 'woocommerce-social-login' ), '<a href="' . $this->get_documentation_url() . '">', '</a>' ),
				'read-the-docs',
				array(
					'always_show_on_settings' => false,
					'notice_class'            => 'updated',
				)
			);
		}

		$this->add_ssl_admin_notices();
	}


	/**
	 * Checks if SSL is required for any providers and not available and adds a
	 * dismissible admin notice if so. Notice will not be rendered to the admin
	 * user once dismissed unless on the plugin settings page, if any
	 *
	 * @since 1.1.0
	 * @see SV_WC_Payment_Gateway_Plugin::add_admin_notices()
	 */
	protected function add_ssl_admin_notices() {

		// Get available providers
		foreach ( $this->get_providers() as $provider ) {

			// Check if the provider requires SSL
			if ( $provider->is_enabled() && $provider->requires_ssl() ) {

				if ( 'no' === get_option( 'woocommerce_force_ssl_checkout' ) ) {

					$message = sprintf( _x( 'WooCommerce Social Login: %s requires SSL for authentication, please force WooCommerce over SSL.', 'Requires SSL', 'woocommerce-social-login' ), '<strong>' . $provider->get_title() . '</strong>' );

					$this->get_admin_notice_handler()->add_admin_notice( $message, $provider->get_id() . '-ssl-required' );
				}
			}
		}
	}

	/**
	 * Render admin notices
	 *
	 * @since 1.6.0
	 */
	public function add_admin_notices() {

		// show any dependency notices
		parent::add_admin_notices();

		// Warn about iThemes Security 'Filter Long URL Strings' setting
		if ( class_exists( 'ITSEC_Tweaks' ) ) {

			$ithemes_security_settings = get_site_option( 'itsec_tweaks', array( 'long_url_strings' => false ) );

			if ( $this->is_plugin_settings() && isset( $ithemes_security_settings['long_url_strings'] ) && $ithemes_security_settings['long_url_strings'] ) {

				$this->get_admin_notice_handler()->add_admin_notice(
					esc_html__( 'Oops, looks like iThemes Security is set to Filter Long URLs. This is likely to cause a conflict with Social Login -- please disable that setting for optimal functionality.', 'woocommerce-social-login' ),
					'ithemes_security_long_url_strings',
					array( 'always_show_on_settings' => false )
				);
			}
		}

		// Warn about deprecated callback URLs
		if ( get_option( 'wc_social_login_upgraded_from_opauth' ) && 'legacy' === get_option( 'wc_social_login_callback_url_format' ) ) {

			$this->get_admin_notice_handler()->add_admin_notice(
				/* translators: %1$s, %3$s - opening <a> tag, %2$s, %4$s - closing </a> tag */
				sprintf( esc_html__( 'Please update callback URLs for each Social Login provider, then switch callback URLs to the Default format in the %1$sSocial Login settings%2$s. You can %3$slearn more from the plugin documentation%4$s.', 'woocommerce-social-login' ), '<a href="' . $this->get_settings_url() . '">', '</a>', '<a href="https://docs.woocommerce.com/document/woocommerce-social-login/#upgrading-to-v2">', '</a>' ),
				'update_callback_url_format',
				array( 'dismissible' => true, 'notice_class' => 'error', 'always_show_on_settings' => true )
			);
		}
	}

	/**
	 * Returns conditional dependencies based on the provider selected
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::get_dependencies()
	 * @return array of dependencies
	 */
	protected function get_dependencies() {

		$dependencies = array();

		foreach ( $this->get_providers() as $provider ) {

			if ( 'twitter' === $provider->get_id() && $provider->is_enabled() ) {
				$dependencies[] = 'curl';
			}
		}

		return array_merge( parent::get_dependencies(), $dependencies );
	}


	/**
	 * Register social login widgets
	 *
	 * @since 1.0.0
	 */
	public function register_widgets() {

		// load widget
		require_once( $this->get_plugin_path() . '/includes/widgets/class-wc-social-login-widget.php' );

		// register widget
		register_widget( 'WC_Social_Login_Widget' );
	}


	/** Helper methods ********************************************************/


	/**
	 * Main Social Login Instance, ensures only one instance is/can be loaded
	 *
	 * @since 1.4.0
	 * @see wc_social_login()
	 * @return WC_Social_Login
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Returns the plugin name, localized
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::get_plugin_name()
	 * @return string the plugin name
	 */
	public function get_plugin_name() {

		return __( 'WooCommerce Social Login', 'woocommerce-social-login' );
	}


	/**
	 * Returns __FILE__
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::get_file()
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {

		return __FILE__;
	}


	/**
	 * Gets the URL to the settings page
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::is_plugin_settings()
	 * @param string $_ unused
	 * @return string URL to the settings page
	 */
	public function get_settings_url( $_ = '' ) {

		return admin_url( 'admin.php?page=wc-settings&tab=social_login' );
	}


	/**
	 * Gets the plugin documentation URL
	 *
	 * @since 1.5.0
	 * @see SV_WC_Plugin::get_documentation_url()
	 * @return string
	 */
	public function get_documentation_url() {

		return 'http://docs.woocommerce.com/document/woocommerce-social-login/';
	}


	/**
	 * Gets the plugin support URL
	 *
	 * @since 1.5.0
	 * @see SV_WC_Plugin::get_support_url()
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/marketplace-ticket-form/';
	}


	/**
	 * Returns true if on the Social Login settings page
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::is_plugin_settings()
	 * @return boolean true if on the settings page
	 */
	public function is_plugin_settings() {

		return isset( $_GET['page'] ) && 'wc-settings' === $_GET['page'] && isset( $_GET['tab'] ) && 'social_login' === $_GET['tab'];
	}


	/**
	 * Get user's social login profiles
	 *
	 * @since 1.0.0
	 * @param int $user_id optional Default: current user id
	 * @return \WC_Social_Login_Provider_Profile[] Array of found profiles or empty array if none are found
	 */
	public function get_user_social_login_profiles( $user_id = null ) {

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		// bail out if user is not logged in
		if ( 0 === (int) $user_id ) {
			return array();
		}

		$linked_social_login_profiles = array();

		foreach ( $this->get_available_providers() as $provider ) {

			$social_profile = get_user_meta( $user_id, '_wc_social_login_' . $provider->get_id() . '_profile', true );

			if ( $social_profile ) {
				$linked_social_login_profiles[ $provider->id ] = new WC_Social_Login_Provider_Profile( $provider->get_id(), $social_profile );
			}
		}

		return $linked_social_login_profiles;
	}


	/**
	 * Get the CSS for styling button colors
	 *
	 * @since 1.1.0
	 * @return string CSS
	 */
	public function get_button_colors_css() {

		ob_start();

		foreach ( $this->get_available_providers() as $provider ) {
			?>
			a.button-social-login.button-social-login-<?php echo esc_attr( $provider->get_id() ); ?>,
			.widget-area a.button-social-login.button-social-login-<?php echo esc_attr( $provider->get_id() ); ?>,
			.social-badge.social-badge-<?php echo esc_attr( $provider->get_id() ); ?> {
			background: <?php echo esc_attr( $provider->get_color() ) ?>;
			border-color: <?php echo esc_attr( $provider->get_color() ) ?>;
			}
			<?php
		}

		return preg_replace( '/\s+/', ' ', ob_get_clean() );
	}


	/** Lifecycle providers ******************************************************/


	/**
	 * Install default settings
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::install()
	 */
	protected function install() {

		// settings page defaults.  unfortunately we can't dynamically pull these because the requisite core WC classes aren't loaded
		// a better solution may be to set any defaults within the save method of the social provider settings classes
		add_option( 'wc_social_login_display', array( 'checkout', 'my_account' ) );
		add_option( 'wc_social_login_text', __( 'For faster checkout, login or register using your social account.', 'woocommerce-social-login' ) );
		add_option( 'wc_social_login_text_non_checkout', __( 'Use a social account for faster login or easy registration.', 'woocommerce-social-login' ) );
	}


	/**
	 * Upgrade to the installed version
	 *
	 * @since 1.1.0
	 * @param string $installed_version
	 * @see SV_WC_Plugin::upgrade()
	 */
	protected function upgrade( $installed_version ) {
		global $wpdb;

		// upgrade to 1.1.0
		if ( version_compare( $installed_version, '1.1.0', '<' ) ) {

			// display option is now a multiselect
			update_option( 'wc_social_login_display', explode( ',', get_option( 'wc_social_login_display', '' ) ) );
		}

		// upgrade to 2.0.0
		if ( version_compare( $installed_version, '2.0.0', '<' ) ) {

			// this install has been upgraded from an opauth-based version,
			// set teh callback url format to legacy to guve users time to upgrade
			add_option( 'wc_social_login_upgraded_from_opauth', true );
			add_option( 'wc_social_login_callback_url_format', 'legacy' );

			// vk is now vkontakte
			update_option( 'wc_social_login_vkontakte_settings', get_option( 'wc_social_login_vk_settings' ) );
			delete_option( 'wc_social_login_vk_settings' );

			// Social provider uid and full_profile have been renamed in usermeta. Also,
			// profile fields have been readjusted
			foreach ( array_keys( $this->get_providers() ) as $provider_id ) {

				$provider_id = esc_attr( $provider_id );
				$old_id      = 'vkontakte' === $provider_id ? 'vk' : $provider_id;

				// remove old profiles
				$wpdb->query( "
					DELETE FROM $wpdb->usermeta
					WHERE meta_key = '_wc_social_login_{$old_id}_profile_full'
				" );

				// rename uid => identifier
				$wpdb->query( "
					UPDATE $wpdb->usermeta
					SET meta_key = '_wc_social_login_{$provider_id}_identifier'
					WHERE meta_key = '_wc_social_login_{$old_id}_uid'
				" );

				// for vkontakte, also update the profile_image meta
				if ( 'vkontakte' === $provider_id ) {

					// options that need to be renamed/updated
					$vk_options = array( 'profile', 'profile_image', 'login_timestamp', 'login_timestamp_gmt' );

					foreach ( $vk_options as $option_name ) {

						$wpdb->query( "
							UPDATE $wpdb->usermeta
							SET meta_key = '_wc_social_login_{$provider_id}_{$option_name}'
							WHERE meta_key = '_wc_social_login_{$old_id}_{$option_name}'
						" );
					}
				}

				// restructure profiles
				// TODO: this can potentially timeout on large customer bases, perhaps refactor? {IT 2016-10-08}
				$results = $wpdb->get_results( "
					SELECT user_id, meta_value
					FROM $wpdb->usermeta
					WHERE meta_key = '_wc_social_login_{$provider_id}_profile'
				" );

				if ( ! empty( $results ) ) {
					foreach ( $results as $row ) {

						$profile = maybe_unserialize( $row->meta_value );

						if ( isset( $profile['nickname'] ) ) {
							$profile['display_name'] = $profile['nickname'];
							unset( $profile['nickname'] );
						}

						if ( isset( $profile['location'] ) ) {
							$profile['city'] = $profile['location'];
							unset( $profile['location'] );
						}

						if ( isset( $profile['image'] ) ) {
							$profile['photo_url'] = $profile['image'];
							unset( $profile['image'] );
						}

						if ( isset( $profile['urls'] ) ) {

							if ( isset( $profile['urls']['website'] ) ) {
								$profile['web_site_url'] = $profile['urls']['website'];
							}

							if ( isset( $profile['urls'][ $provider_id ] ) ) {
								$profile['profile_url'] = $profile['urls'][ $provider_id ];
							}

							unset( $profile['urls'] );
						}

						unset( $profile['provider'] );

						update_user_meta( $row->user_id, '_wc_social_login_' . $provider_id . '_profile', $profile );
					}
				}

			}
		}

		// upgrade to 2.3.0
		if ( version_compare( $installed_version, '2.3.0', '<' ) ) {

			// add new option to display login text on non-checkout pages
			add_option( 'wc_social_login_text_non_checkout', __( 'Use a social account for faster login or easy registration.', 'woocommerce-social-login' ) );
		}
	}


	/**
	 * Set profile image avatar
	 *
	 * Filters the get_avatar() function and sets the img src to stored profile image
	 *
	 * @since 1.1.0
	 * @param string $avatar Image tag for the user's avatar.
	 * @param mixed $id_or_email A user ID, email address, or comment object.
	 * @return string avatar img src
	 */
	public function set_profile_image_avatar( $avatar, $id_or_email ) {

		if ( is_admin() ) {
			$screen = get_current_screen();

			if ( is_object( $screen ) && 'options-discussion' === $screen->id ) {
				return $avatar;
			}
		}

		$user_id = 0;

		if ( is_numeric( $id_or_email ) ) {

			$user_id = (int) $id_or_email;

		} elseif ( is_object( $id_or_email ) ) {

			if ( ! empty( $id_or_email->user_id ) ) {
				$user_id = (int) $id_or_email->user_id;
			}

		} else {
			$user = get_user_by( 'email', $id_or_email );

			if ( $user ) {
				$user_id = $user->ID;
			}
		}

		if ( $user_id && $image = get_user_meta( $user_id, '_wc_social_login_profile_image', true ) ) {

			/**
			 * Filter the profile image URL.
			 *
			 * @since 1.2.0
			 * @param string $image the profile image URL
			 */
			$image = apply_filters( 'wc_social_login_profile_image', $image );

			if ( ! ( ( is_ssl() || 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) ) && strpos( $image, 'instagram.com' ) ) ) {
				$avatar = preg_replace( "/src='(.*?)'/i", "src='" . $image . "'", $avatar );
				$avatar = preg_replace( "/srcset='(.*?)'/i", "srcset='" . $image . " 2x'", $avatar );
			}
		}

		return $avatar;
	}

	/**
	 * Fix URLs of the avatars provided by social networks.
	 *
	 * @since 1.6.0
	 * @param string $url URL received from the social profile
	 * @return string URL after our changes
	 */
	public function adjust_avatar_url( $url ) {

		// Instagram and VK do not support SSL avatars. For others - we force https.
		if ( false === strpos( $url, 'instagram.com' ) && false === strpos( $url, '.vk.me' ) ) {
			$url = set_url_scheme( $url, 'https' );
		}

		return $url;
	}


} // end WC_Social_Login class


/**
 * Returns the One True Instance of Social Login
 *
 * @since 1.4.0
 * @return WC_Social_Login
 */
function wc_social_login() {
	return WC_Social_Login::instance();
}

// fire it up!
wc_social_login();

} // init_woocommerce_social_login()

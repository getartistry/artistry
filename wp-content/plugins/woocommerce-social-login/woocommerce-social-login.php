<?php
/**
 * Plugin Name: WooCommerce Social Login
 * Plugin URI: http://www.woothemes.com/products/woocommerce-social-login/
 * Description: One-click registration and login via social networks like Facebook, Google, Twitter and Amazon
 * Author: WooThemes / SkyVerge
 * Author URI: http://www.woothemes.com
 * Version: 1.7.1
 * Text Domain: woocommerce-social-login
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2014-2016 SkyVerge, Inc. (info@skyverge.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Social-Login
 * @author    SkyVerge
 * @category  Integration
 * @copyright Copyright (c) 2014-2016, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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

SV_WC_Framework_Bootstrap::instance()->register_plugin( '4.2.0', __( 'WooCommerce Social Login', 'woocommerce-social-login' ), __FILE__, 'init_woocommerce_social_login', array( 'minimum_wc_version' => '2.3.6', 'backwards_compatible' => '4.2.0' ) );

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
 * + `wc_social_login_opauth_salt` - Randomly generated Opauth salt value
 * + `wc_social_login_version` - the current plugin version, set on install/upgrade
 *
 * ### User Meta
 * + `_wc_social_login_{provider id}_profile` - array of social profile values (email, nickname, name, etc)
 * + `_wc_social_login_{provider id}_uid` -
 *
 * @since 1.0.0
 */
class WC_Social_Login extends SV_WC_Plugin {


	/** plugin version number */
	const VERSION = '1.7.1';

	/** @var WC_Social_Login single instance of this plugin */
	protected static $instance;

	/** plugin id */
	const PLUGIN_ID = 'social_login';

	/** plugin meta prefix */
	const PLUGIN_PREFIX = 'wc_social_login_';

	/** plugin text domain, DEPRECATED as of 1.7.0 */
	const TEXT_DOMAIN = 'woocommerce-social-login';

	/** @var \WC_Social_Login_Admin instance */
	public $admin;

	/** @var \WC_Social_Login_Frontend instance */
	public $frontend;

	/** @var array login providers */
	public $providers;

	/** @var WC_Social_Login_Opauth */
	public $opauth;


	/**
	 * Initializes the plugin
	 *
	 * @since 1.0.0
	 * @return \WC_social_login
	 */
	public function __construct() {

		parent::__construct( self::PLUGIN_ID, self::VERSION );

		// Initialize
		add_action( 'init', array( $this, 'init' ) );

		// Register widgets
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}


	/**
	 * Autoload Opauth, Strategies, and Provider classes
	 *
	 * @since 1.0.2
	 * @param string $class class name to load
	 */
	public function autoload( $class ) {

		if ( 0 === stripos( $class, 'opauth' ) ) {

			// Opauth classes, note that Opauth handles loading strategies internally
			$path = $this->get_plugin_path() . '/lib/opauth/lib/Opauth/';

			$file = $class . '.php';

			if ( is_readable( $path . $file ) ) {
				require_once( $path . $file );
			}

		} elseif ( 0 === stripos( $class, 'wc_social_login_provider_' ) ) {

			$class = strtolower( $class );

			// Provider classes
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

		// autoload classes
		spl_autoload_register( array( $this, 'autoload' ) );

		// Base social login provider & profile
		require_once( $this->get_plugin_path() . '/includes/abstract-wc-social-login-provider.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-social-login-provider-profile.php' );

		// Load providers
		$this->load_providers();

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

		require_once( $this->get_plugin_path() . '/includes/class-wc-social-login-opauth.php' );
		$this->opauth = new WC_Social_Login_Opauth( $this->get_auth_path() );

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
	 * Load plugin text domain.
	 *
	 * @since 1.0.0
	 * @see SV_WC_Plugin::load_translation()
	 */
	public function load_translation() {

		load_plugin_textdomain( 'woocommerce-social-login', false, dirname( plugin_basename( $this->get_file() ) ) . '/i18n/languages' );
	}


	/** Provider methods ******************************************************/


	/**
	 * load_providers function.
	 *
	 * Loads all social login providers which are hooked in.
	 *
	 * Providers are sorted into their user-defined order after being loaded.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function load_providers() {

		$this->unregister_providers();

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
			'WC_Social_Login_Provider_VK',
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
	 * @param object|string $provider Either the name of the provider's class, or an instance of the provider's class
	 */
	public function register_provider( $provider ) {

		if ( ! is_object( $provider ) ) {
			$provider = new $provider( $this->get_auth_path() );
		}

		$id = empty( $provider->instance_id ) ? $provider->get_id() : $provider->instance_id;

		$this->providers[ $id ] = $provider;
	}


	/**
	 * Unregister all providers
	 *
	 * @since 1.0.0
	 */
	public function unregister_providers() {
		unset( $this->providers );
	}


	/**
	 * Sorts providers into the user defined order
	 *
	 * @since 1.0.0
	 * @return array
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
	 * Returns all registered providers for usage
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_providers() {
		return $this->providers;
	}


	/**
	 * Returns the requested provider, if found.
	 *
	 * @since 1.0.0
	 * @param string $provider_id
	 * @return WC_Social_Login_Provider|null
	 */
	public function get_provider( $provider_id ) {
		return isset( $this->providers[ $provider_id ] ) ? $this->providers[ $provider_id ] : null;
	}


	/**
	 * Get available providers
	 *
	 * @since 1.0.0
	 * @return array
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
				sprintf( __( 'Thanks for installing Social Login! Before you get started, please take a moment to %sread through the documentation%s.', 'woocommerce-social-login' ),
					'<a href="' . $this->get_documentation_url() . '">', '</a>' ),
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

		return 'http://docs.woothemes.com/document/woocommerce-social-login/';
	}


	/**
	 * Gets the plugin support URL
	 *
	 * @since 1.5.0
	 * @see SV_WC_Plugin::get_support_url()
	 * @return string
	 */
	public function get_support_url() {

		return 'http://support.woothemes.com/';
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
	 * @return array|null Array of found profiles or null if none found
	 */
	public function get_user_social_login_profiles( $user_id = null ) {

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$linked_social_login_profiles = array();

		foreach ( $this->get_available_providers() as $provider ) {

			$social_profile = get_user_meta( $user_id, '_wc_social_login_' . $provider->get_id() . '_profile_full', true );

			if ( $social_profile ) {

				// add provider to profile, as it's not saved with the raw profile
				$social_profile['provider'] = $provider->id;


				$linked_social_login_profiles[ $provider->id ] = new WC_Social_Login_Provider_Profile( $social_profile );
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

		add_option( 'wc_social_login_opauth_salt', wp_generate_password( 62, true, true ) );

		// settings page defaults.  unfortunately we can't dynamically pull these because the requisite core WC classes aren't loaded
		// a better solution may be to set any defaults within the save method of the social provider settings classes
		add_option( 'wc_social_login_display', array( 'checkout', 'my_account' ) );
		add_option( 'wc_social_login_text', __( 'For faster checkout, login or register using your social account.', 'woocommerce-social-login' ) );
	}


	/**
	 * Upgrade to the installed version
	 *
	 * @since 1.1.0
	 * @param string $installed_version
	 * @see SV_WC_Plugin::upgrade()
	 */
	protected function upgrade( $installed_version ) {

		// upgrade to 1.1.0
		if ( version_compare( $installed_version, '1.1.0', '<' ) ) {

			// display option is now a multiselect
			update_option( 'wc_social_login_display', explode( ',', get_option( 'wc_social_login_display', '' ) ) );
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

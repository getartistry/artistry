<?php
/*
Plugin Name: YITH WooCommerce Social Login Premium
Description: YITH WooCommerce Social Premium login is a plugin that allows you to login to your e-commerce site through your Facebook or Twitter or Google+ and others social networks account.
Version: 1.2.0
Author: YITHEMES
Author URI: http://yithemes.com/
Text Domain: yith-woocommerce-social-login
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*
 * @package YITH WooCommerce Social Login Premium
 * @since   1.0.0
 * @author  YITHEMES
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( !defined( 'YITH_YWSL_DIR' ) ) {
	define( 'YITH_YWSL_DIR', plugin_dir_path( __FILE__ ) );
}


// Free version deactivation if installed __________________
if( ! function_exists( 'yit_deactive_free_version' ) ) {
	require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YITH_YWSL_FREE_INIT', plugin_basename( __FILE__ ) );

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_YWSL_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YITH_YWSL_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_YWSL_DIR  );


// Registration hook  ________________________________________
if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


// WooCommerce installation check _________________________
if ( !function_exists( 'yith_ywsl_install_woocommerce_admin_notice' ) ) {
	function yith_ywsl_install_woocommerce_admin_notice() { ?>
	<div class="error">
		<p><?php _e( 'YITH Woocommerce Social Login is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-social-login' ); ?></p>
	</div>
<?php
	}
}

// Define constants ________________________________________
if ( defined( 'YITH_YWSL_VERSION' ) ) {
	return;
} else {
	define( 'YITH_YWSL_VERSION', '1.2.0' );
}

if ( !defined( 'YITH_YWSL_PREMIUM' ) ) {
	define( 'YITH_YWSL_PREMIUM', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YITH_YWSL_INIT' ) ) {
	define( 'YITH_YWSL_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YITH_YWSL_FILE' ) ) {
	define( 'YITH_YWSL_FILE', __FILE__ );
}


if ( !defined( 'YITH_YWSL_URL' ) ) {
	define( 'YITH_YWSL_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'YITH_YWSL_HYBRID_URL' ) ) {
	define( 'YITH_YWSL_HYBRID_URL', YITH_YWSL_URL.'includes/hybridauth/' );
}

if ( !defined( 'YITH_YWSL_ASSETS_URL' ) ) {
	define( 'YITH_YWSL_ASSETS_URL', YITH_YWSL_URL . 'assets' );
}

if ( !defined( 'YITH_YWSL_TEMPLATE_PATH' ) ) {
	define( 'YITH_YWSL_TEMPLATE_PATH', YITH_YWSL_DIR . 'templates' );
}

if ( !defined( 'YITH_YWSL_INC' ) ) {
	define( 'YITH_YWSL_INC', YITH_YWSL_DIR . '/includes/' );
}

if ( !defined( 'YITH_YWSL_SLUG' ) ) {
	define( 'YITH_YWSL_SLUG', 'yith-woocommerce-social-login' );
}

if ( !defined( 'YITH_YWSL_SECRET_KEY' ) ) {
	define( 'YITH_YWSL_SECRET_KEY', 'MGEW3aQ8xcX09uQUyIW1' );
}


if ( ! function_exists( 'yith_ywsl_premium_install' ) ) {
	function yith_ywsl_premium_install() {

		if ( !function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'yith_ywsl_install_woocommerce_admin_notice' );
		} else {
			do_action( 'yith_ywsl_premium_init' );
		}
	}

	add_action( 'plugins_loaded', 'yith_ywsl_premium_install', 11 );
}

if ( ! function_exists( 'yith_ywsl_premium_constructor' ) ) {
	function yith_ywsl_premium_constructor() {

		// Load YWSL text domain ___________________________________
		load_plugin_textdomain( 'yith-woocommerce-social-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		if ( session_id() == '' ) {
			session_start();
		}

		require_once( YITH_YWSL_INC . 'functions.yith-social-login.php' );
		require_once( YITH_YWSL_INC . 'class-yith-social-login.php' );
		require_once( YITH_YWSL_INC . 'class-yith-social-login-premium.php' );
		require_once( YITH_YWSL_DIR . 'widgets/class.yith-ywsl-widget.php' );

		if ( is_admin() ) {
			require_once( YITH_YWSL_INC . 'class-yith-social-login-admin.php' );
			require_once( YITH_YWSL_INC . 'class-yith-social-login-admin-premium.php' );
			YITH_WC_Social_Login_Admin_Premium();
		}

		if( ! is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
			require_once( YITH_YWSL_INC . 'class-yith-social-login-frontend.php' );
			YITH_WC_Social_Login_Frontend();
		}

		YITH_WC_Social_Login_Premium();

	}

	add_action( 'yith_ywsl_premium_init', 'yith_ywsl_premium_constructor' );
}

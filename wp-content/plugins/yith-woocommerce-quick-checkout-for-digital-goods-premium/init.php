<?php
/**
 * Plugin Name: YITH WooCommerce Quick Checkout for Digital Goods Premium
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-quick-checkout-for-digital-goods/
 * Description: Speed up checkout for digital products and remove unessential fields during purchase
 * Author: YITHEMES
 * Text Domain: yith-woocommerce-quick-checkout-for-digital-goods
 * Version: 1.1.0
 * Author URI: http://yithemes.com/
 * WC requires at least: 2.6.0
 * WC tested up to: 3.2.0 RC2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ywqcdg_install_woocommerce_premium_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Quick Checkout for Digital Goods is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-quick-checkout-for-digital-goods' ); ?></p>
	</div>
	<?php
}

if ( ! defined( 'YWQCDG_VERSION' ) ) {
	define( 'YWQCDG_VERSION', '1.1.0' );
}

if ( ! defined( 'YWQCDG_INIT' ) ) {
	define( 'YWQCDG_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YWQCDG_SLUG' ) ) {
	define( 'YWQCDG_SLUG', 'yith-woocommerce-quick-checkout-for-digital-goods' );
}

if ( ! defined( 'YWQCDG_SECRET_KEY' ) ) {
	define( 'YWQCDG_SECRET_KEY', 'YtRtuRJaH9aSNq3Vrl37' );
}

if ( ! defined( 'YWQCDG_PREMIUM' ) ) {
	define( 'YWQCDG_PREMIUM', '1' );
}

if ( ! defined( 'YWQCDG_FILE' ) ) {
	define( 'YWQCDG_FILE', __FILE__ );
}

if ( ! defined( 'YWQCDG_DIR' ) ) {
	define( 'YWQCDG_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YWQCDG_URL' ) ) {
	define( 'YWQCDG_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YWQCDG_ASSETS_URL' ) ) {
	define( 'YWQCDG_ASSETS_URL', YWQCDG_URL . 'assets' );
}

if ( ! defined( 'YWQCDG_TEMPLATE_PATH' ) ) {
	define( 'YWQCDG_TEMPLATE_PATH', YWQCDG_DIR . 'templates' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWQCDG_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YWQCDG_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YWQCDG_DIR );

function ywqcdg_init() {

	/* Load text domain */
	load_plugin_textdomain( 'yith-woocommerce-quick-checkout-for-digital-goods', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/* === Global YITH WooCommerce Quick Checkout for Digital Goods  === */
	YITH_WQCDG();

}

add_action( 'ywqcdg_init', 'ywqcdg_init' );

function ywqcdg_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ywqcdg_install_woocommerce_premium_admin_notice' );
	} else {
		do_action( 'ywqcdg_init' );
	}

}

add_action( 'plugins_loaded', 'ywqcdg_install', 11 );

/**
 * Init default plugin settings
 */
if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! function_exists( 'YITH_WQCDG' ) ) {

	/**
	 * Unique access to instance of YITH_WC_Quick_Checkout_Digital_Goods
	 *
	 * @since   1.0.0
	 * @return  YITH_WC_Quick_Checkout_Digital_Goods
	 * @author  Alberto Ruggiero
	 */
	function YITH_WQCDG() {

		// Load required classes and functions
		require_once( YWQCDG_DIR . 'class.yith-wc-quick-checkout-digital-goods.php' );

		return YITH_WC_Quick_Checkout_Digital_Goods::get_instance();

	}

}

<?php
/**
 * Plugin Name: WooCommerce Google Product Feed
 * Plugin URI: https://www.woocommerce.com/products/google-product-feed/
 * Description: WooCommerce extension that allows you to more easily populate advanced attributes into the Google Merchant Centre feed
 * Author: Lee Willis
 * Version: 7.1.2
 * Author URI: http://www.leewillis.co.uk/
 * License: GPLv3
 *
 * @package woocommerce-gpf
 */

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

// The current DB schema version.
define( 'WOOCOMMERCE_GPF_DB_VERSION', 2 );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'd55b4f852872025741312839f142447e', '18619' );


require_once( dirname( __FILE__ ) . '/vendor/autoload_52.php' );
require_once( 'woocommerce-gpf-cache.php' );
if ( is_admin() ) {
	require_once( 'woocommerce-gpf-common.php' );
	require_once( 'gamajo-template-loader.class.php' );
	require_once( 'woocommerce-gpf-template-loader.class.php' );
	require_once( 'woocommerce-gpf-admin.php' );
}

/**
 * Bodge for WPEngine.com users - provide the feed at a URL that doesn't
 * rely on query arguments as WPEngine don't support URLs with query args
 * if the requestor is a googlebot. #broken
 */
function woocommerce_gpf_endpoints() {

	add_rewrite_tag( '%woocommerce_gpf%', '([^/]+)' );
	add_rewrite_tag( '%gpf_start%', '([0-9]{1,})' );
	add_rewrite_tag( '%gpf_limit%', '([0-9]{1,})' );
	add_rewrite_tag( '%gpf_categories%', '^(\d+(,\d+)*)?$' );
	add_rewrite_rule( 'woocommerce_gpf/([^/]+)/gpf_start/([0-9]{1,})/gpf_limit/([0-9]{1,})/gpf_categories/(\d+(,\d+)*)', 'index.php?woocommerce_gpf=$matches[1]&gpf_start=$matches[2]&gpf_limit=$matches[3]&gpf_categories=$matches[4]', 'top' );
	add_rewrite_rule( 'woocommerce_gpf/([^/]+)/gpf_start/([0-9]{1,})/gpf_limit/([0-9]{1,})', 'index.php?woocommerce_gpf=$matches[1]&gpf_start=$matches[2]&gpf_limit=$matches[3]', 'top' );
	add_rewrite_rule( 'woocommerce_gpf/([^/]+)/gpf_start/([0-9]{1,})', 'index.php?woocommerce_gpf=$matches[1]&gpf_start=$matches[2]', 'top' );
	add_rewrite_rule( 'woocommerce_gpf/([^/]+)/gpf_categories/(\d+(,\d+)*)', 'index.php?woocommerce_gpf=$matches[1]&gpf_categories=$matches[2]', 'top' );
	add_rewrite_rule( 'woocommerce_gpf/([^/]+)', 'index.php?woocommerce_gpf=$matches[1]', 'top' );
}
add_action( 'init', 'woocommerce_gpf_endpoints' );



/**
 * Include the relevant files dependant on the page request type
 */
function woocommerce_gpf_includes() {

	global $wp_query;

	// Parsing for legacy URLs.
	if ( isset( $_REQUEST['action'] ) && 'woocommerce_gpf' === $_REQUEST['action'] ) {
		if ( isset( $_REQUEST['feed_format'] ) ) {
			$wp_query->query_vars['woocommerce_gpf'] = $_REQUEST['feed_format'];
		} else {
			$wp_query->query_vars['woocommerce_gpf'] = 'google';
		}
	}

	if ( isset( $wp_query->query_vars['woocommerce_gpf'] ) ) {
		require_once( 'woocommerce-gpf-common.php' );
		require_once( 'woocommerce-gpf-feed.php' );
		if ( 'google' === $wp_query->query_vars['woocommerce_gpf'] ) {
			require_once 'woocommerce-gpf-feed-google.php';
		} elseif ( 'googleinventory' === $wp_query->query_vars['woocommerce_gpf'] ) {
			require_once 'woocommerce-gpf-feed-google-inventory.php';
		} elseif ( 'bing' === $wp_query->query_vars['woocommerce_gpf'] ) {
			require_once 'woocommerce-gpf-feed-bing.php';
		}
		require_once( 'woocommerce-gpf-feed-item.php' );
		require_once( 'woocommerce-gpf-frontend.php' );
	}

}
add_action( 'template_redirect', 'woocommerce_gpf_includes' );

/**
 * Include/invoke relevant classes if we're doing product structured data.
 */
function woocommerce_gpf_structured_data() {
	global $woocommerce_gpf_structured_data;
	require_once( 'woocommerce-gpf-structured-data.php' );
	$woocommerce_gpf_structured_data = new WoocommerceGpfStructuredData();
}
// Loads at priority 5 to ensure it runs before WooCommerce's hook.
add_action( 'woocommerce_single_product_summary', 'woocommerce_gpf_structured_data', 5 );


/**
 * Determine if this is a feed URL.
 *
 * May need to be used before parse_query, so we have to manually check all
 * sorts of combinations.
 *
 * @return boolean  True if a feed is being generated.
 */
function woocommerce_gpf_is_generating_feed() {
	return
		( isset( $_REQUEST['action'] ) && 'woocommerce_gpf' === $_REQUEST['action'] ) ||
		( isset( $_SERVER['REQUEST_URI'] ) && stripos( $_SERVER['REQUEST_URI'], '/woocommerce_gpf' ) === 0 ) ||
		isset( $_REQUEST['woocommerce_gpf'] );
}

/**
 * Override the default customer address.
 */
function woocommerce_gpf_set_customer_default_location( $location ) {
	if ( woocommerce_gpf_is_generating_feed() ) {
		return wc_format_country_state_string( get_option( 'woocommerce_default_country' ) );
	} else {
		return $location;
	}
}
add_filter( 'woocommerce_customer_default_location_array', 'woocommerce_gpf_set_customer_default_location' );

/**
 * Create database table to cache the Google product taxonomy.
 */
function woocommerce_gpf_install() {

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . 'woocommerce_gpf_google_taxonomy';

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$sql = "CREATE TABLE $table_name (
	            taxonomy_term text,
	            search_term text
			) $charset_collate";
	dbDelta( $sql );

	$sql = "CREATE TABLE `" . $wpdb->prefix . "wc_gpf_render_cache` (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  `post_id` bigint(20) unsigned NOT NULL,
	  `name` varchar(32) NOT NULL,
	  `value` text NOT NULL,
	  UNIQUE KEY composite_cache_idx (`post_id`, `name`)
	) $charset_collate";
	dbDelta( $sql );

	flush_rewrite_rules();

	// Upgrade old tables on plugin deactivation / activation.
	$wpdb->query( "ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci" );

	update_option( 'woocommerce_gpf_db_version', WOOCOMMERCE_GPF_DB_VERSION );

	// Set default settings if there are none.
	$settings = get_option( 'woocommerce_gpf_config' );
	if ( false === $settings ) {
		$settings = array(
			'product_fields'     => array(
				'availability'            => 'on',
				'brand'                   => 'on',
				'mpn'                     => 'on',
				'product_type'            => 'on',
				'google_product_category' => 'on',
				'size_system'             => 'on',
			),
			'product_defaults' => array(
				'availability' => 'in stock',
			),
		);
		if ( version_compare( WOOCOMMERCE_VERSION, '2.4.0', '>' ) ) {
			$settings['include_variations'] = 'on';
		}
		add_option( 'woocommerce_gpf_config', $settings, '', 'yes' );
	}
}
register_activation_hook( __FILE__, 'woocommerce_gpf_install' );


/**
 * Disable attempts to GZIP the feed output to avoid memory issues.
 */
function woocommerce_gpf_block_wordpress_gzip_compression() {
	if ( isset( $_GET['woocommerce_gpf'] ) ) {
		remove_action( 'init', 'ezgz_buffer' );
	}
}
add_action( 'plugins_loaded', 'woocommerce_gpf_block_wordpress_gzip_compression' );


function woocommerce_gpf_prevent_wporg_update_check( $r, $url ) {
	if ( 0 === strpos( $url, 'https://api.wordpress.org/plugins/update-check/' ) ) {
		$my_plugin = plugin_basename( __FILE__ );
		$plugins   = @json_decode( $r['body']['plugins'], true );
		if ( null === $plugins ) {
			return $r;
		}
		unset( $plugins['active'][ array_search( $my_plugin, $plugins['active'], true ) ] );
		unset( $plugins['plugins'][ $my_plugin ] );
		$r['body']['plugins'] = json_encode( $plugins );
	}
	return $r;
}
add_filter( 'http_request_args', 'woocommerce_gpf_prevent_wporg_update_check', 10, 2 );

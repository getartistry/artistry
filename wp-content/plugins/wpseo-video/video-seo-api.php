<?php
/**
 * @package Yoast\VideoSEO
 */

/**
 * Throw an error if WordPress SEO is not installed.
 *
 * @since 0.2
 */
function yoast_wpseo_missing_error() {
	if ( current_user_can( 'install_plugins' ) || current_user_can( 'activate_plugins' ) ) {
		$page_slug = 'plugin-install.php';
		if ( is_multisite() === true && is_super_admin() ) {
			$base_url = network_admin_url( $page_slug );
		}
		else {
			$base_url = admin_url( $page_slug );
		}

		$url = add_query_arg(
			array(
				'tab'                 => 'search',
				'type'                => 'term',
				's'                   => 'wordpress+seo',
				'plugin-search-input' => 'Search+Plugins',
			),
			$base_url
		);

		/* translators: %1$s and %3$s expand to anchor tags with a link to the download page for Yoast SEO . %2$s expands to Yoast SEO.*/
		$message = sprintf( esc_html__( 'Please %1$sinstall & activate %2$s%3$s and then enable its XML sitemap functionality to allow the Video SEO module to work.', 'yoast-video-seo' ), '<a href="' . esc_url( $url ) . '">', 'Yoast SEO', '</a>' );
	}
	else {
		/* translators: %1$s expands to Yoast SEO.*/
		$message = sprintf( esc_html__( 'Please ask the (network) admin to install & activate %1$s and then enable its XML sitemap functionality to allow the Video SEO module to work.', 'yoast-video-seo' ), 'Yoast SEO' );
	}

	yoast_wpseo_video_seo_self_deactivate( $message, false );
}


/**
 * Throw an error if WordPress is out of date.
 *
 * @since 1.5.4
 */
function yoast_wordpress_upgrade_error() {
	$message = esc_html__( 'Please upgrade WordPress to the latest version to allow WordPress and the Video SEO module to work properly.', 'yoast-video-seo' );
	yoast_wpseo_video_seo_self_deactivate( $message );
}


/**
 * Throw an error if WordPress SEO is out of date.
 *
 * @since 1.5.4
 */
function yoast_wpseo_upgrade_error() {
	/* translators: $1$s expands to Yoast SEO.*/
	$message = sprintf( esc_html__( 'Please upgrade the %1$s plugin to the latest version to allow the Video SEO module to work.', 'yoast-video-seo' ), 'Yoast SEO' );
	yoast_wpseo_video_seo_self_deactivate( $message );
}


/**
 * Throw an error if the PHP SPL extension is disabled (prevent white screens)
 *
 * @since 1.7
 */
function yoast_phpspl_missing_error() {
	$message = esc_html__( 'The PHP SPL extension seems to be unavailable. Please ask your web host to enable it.', 'yoast-video-seo' );
	yoast_wpseo_video_seo_self_deactivate( $message );
}


/**
 * Initialize the Video SEO module on plugins loaded, so WP SEO should have set its constants and loaded its main classes.
 *
 * @since 0.2
 */
function yoast_wpseo_video_seo_init() {
	if ( ! function_exists( 'spl_autoload_register' ) ) {
		add_action( 'admin_init', 'yoast_phpspl_missing_error' );
	}
	elseif ( ! version_compare( $GLOBALS['wp_version'], '4.4', '>=' ) ) {
		add_action( 'admin_init', 'yoast_wordpress_upgrade_error' );
	}
	else {
		if ( defined( 'WPSEO_VERSION' ) ) {
			// Allow beta version.
			if ( version_compare( WPSEO_VERSION, '3.0', '>=' ) ) {
				add_action( 'plugins_loaded', 'yoast_wpseo_video_seo_meta_init', 10 );
				add_action( 'plugins_loaded', 'yoast_wpseo_video_seo_sitemap_init', 20 );
			}
			else {
				add_action( 'admin_init', 'yoast_wpseo_upgrade_error' );
			}
		}
		else {
			add_action( 'admin_init', 'yoast_wpseo_missing_error' );
		}
	}
	add_action( 'init', array( 'WPSEO_Video_Sitemap', 'load_textdomain' ), 1 );
}


/**
 * Initialize the video metadata class
 */
function yoast_wpseo_video_seo_meta_init() {
	WPSEO_Meta_Video::init();
}


/**
 * Initialize the main plugin class
 */
function yoast_wpseo_video_seo_sitemap_init() {
	$GLOBALS['wpseo_video_xml'] = new WPSEO_Video_Sitemap();
}

/**
 * Self-deactivate plugin
 *
 * @since 1.7
 *
 * @param string $message    Error message.
 * @param bool   $use_prefix Prefix the text with Activation.
 */
function yoast_wpseo_video_seo_self_deactivate( $message, $use_prefix = true ) {
	if ( is_admin() && ( ! defined( 'IFRAME_REQUEST' ) || IFRAME_REQUEST === false ) ) {

		$prefix  = ( $use_prefix ) ? __( '(Re-)Activation of Video SEO failed: ', 'yoast-video-seo' ) : '';
		$file    = plugin_basename( WPSEO_VIDEO_FILE );
		$ms_hook = ( is_multisite() && is_network_admin() ) ? 'network_' : '';

		$function_code = <<<EO_FUNCTION
echo '<div class="error"><p>{$prefix}{$message}</p></div>';
EO_FUNCTION;

		add_action( $ms_hook . 'admin_notices', create_function( '', $function_code ) );

		deactivate_plugins( $file );

		// Add to recently active plugins list.
		if ( is_network_admin() ) {
			update_site_option( 'recently_activated', ( array( $file => time() ) + (array) get_site_option( 'recently_activated' ) ) );
		}
		else {
			update_option( 'recently_activated', ( array( $file => time() ) + (array) get_option( 'recently_activated' ) ) );
		}

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}


/**
 * Clear the sitemap index.
 *
 * @since 3.8.0
 */
function yoast_wpseo_video_clear_sitemap_cache() {
	if ( ! defined( 'WPSEO_VERSION' ) ) {
		return;
	}
	$sitemap_instance = new WPSEO_Video_Sitemap();
	$sitemap_basename = $sitemap_instance->video_sitemap_basename();

	WPSEO_Video_Wrappers::invalidate_sitemap( $sitemap_basename );
}


/**
 * Execute option cleanup actions on activate.
 *
 * There are a couple of things being done on activation:
 * - Clean up the options to be sure it's set well.
 * - Activating the license, because updating the plugin results in deactivating the license.
 * - Clear the sitemap cache to rebuild the sitemap.
 */
function yoast_wpseo_video_activate() {
	WPSEO_Video_Sitemap::load_textdomain();

	if ( ! defined( 'WPSEO_VERSION' ) ) {
		return;
	}

	$option_instance = WPSEO_Option_Video::get_instance();
	$option_instance->clean();

	yoast_wpseo_video_clear_sitemap_cache();

	if ( ! class_exists( 'Yoast_Plugin_License_Manager' ) ) {
		return;
	}

	// Activate the license.
	$license_manager = new Yoast_Plugin_License_Manager( new Yoast_Product_WPSEO_Video() );
	$license_manager->activate_license();
}


/**
 * Empty sitemap cache on plugin deactivate.
 *
 * @since 3.8.0
 */
function yoast_wpseo_video_deactivate() {
	yoast_wpseo_video_clear_sitemap_cache();
}

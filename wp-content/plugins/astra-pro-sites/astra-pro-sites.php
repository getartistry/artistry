<?php
/**
 * Plugin Name: Astra Premium Sites
 * Plugin URI: https://wpastra.com/
 * Description: This plugin is an add-on for the Astra WordPress Theme. It offers the premium library of ready sites that can be imported in your website easily.
 * Version: 1.2.2
 * Author: Brainstorm Force
 * Author URI: http://www.brainstormforce.com
 * Text Domain: astra-pro-sites
 *
 * @package Astra Pro Sites
 */

define( 'ASTRA_PRO_SITES_NAME', __( 'Astra Premium Sites', 'astra-sites' ) );
define( 'ASTRA_PRO_SITES_VER', '1.2.2' );
define( 'ASTRA_PRO_SITES_FILE', __FILE__ );
define( 'ASTRA_PRO_SITES_BASE', plugin_basename( ASTRA_PRO_SITES_FILE ) );
define( 'ASTRA_PRO_SITES_DIR', plugin_dir_path( ASTRA_PRO_SITES_FILE ) );
define( 'ASTRA_PRO_SITES_URI', plugins_url( '/', ASTRA_PRO_SITES_FILE ) );

if ( ! function_exists( 'astra_pro_sites_setup' ) ) :

	/**
	 * Astra Sites Setup
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function astra_pro_sites_setup() {

		require_once ASTRA_PRO_SITES_DIR . 'classes/class-astra-pro-sites.php';

		// Graupi.
		require_once 'class-brainstorm-update-astra-pro-sites.php';

		if ( ! class_exists( 'Astra_Sites' ) ) {
			require_once ASTRA_PRO_SITES_DIR . 'astra-sites.php';
		}
	}

	add_action( 'plugins_loaded', 'astra_pro_sites_setup', 11 );

endif;


if ( ! function_exists( 'astra_pro_sites_fetch_bundled_products' ) ) :

	/**
	 * Fetch Bundled Products
	 *
	 * @since 1.1.2 Checking required plugins on `register_activation_hook` hook instead of `admin_init`.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function astra_pro_sites_fetch_bundled_products() {
		update_site_option( 'bsf_force_check_extensions', true );
	}

	register_activation_hook( __FILE__, 'astra_pro_sites_fetch_bundled_products' );

endif;

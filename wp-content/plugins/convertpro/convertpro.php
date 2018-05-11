<?php
/**
 * Plugin Name: Convert Pro
 * Plugin URI: https://www.convertplug.com/pro
 * Author: Brainstorm Force
 * Author URI: https://www.brainstormforce.com
 * Version: 1.1.6
 * Description: Convert Pro is an advanced lead generation popup plugin with a drag and drop editor that helps you create beautiful popups and opt-in forms to boost your website conversions. With Convert Pro you can build email lists, drive traffic, promote videos, offer lead magnets and a lot more.
 * Text Domain: convertpro
 *
 * @package ConvertPro
 */

add_action( 'plugins_loaded', 'cp_load_convertpro', 1 );

// Activation.
register_activation_hook( __FILE__, 'activation' );

if ( ! function_exists( 'cp_load_convertpro' ) ) {

	/**
	 * Function to load packages
	 *
	 * @since 1.0
	 */
	function cp_load_convertpro() {
		require_once 'classes/class-cp-v2-loader.php';

	}
}

/**
 * Function for activation hook
 *
 * @since 1.0
 */
function activation() {

	update_option( 'convert_pro_redirect', true );
	update_site_option( 'bsf_force_check_extensions', true );

	delete_option( 'cpro_hide_branding' );

	global $wp_version;
	$wp  = '3.5';
	$php = '5.3.2';
	if ( version_compare( PHP_VERSION, $php, '<' ) ) {
		$flag = 'PHP';
	} elseif ( version_compare( $wp_version, $wp, '<' ) ) {
		$flag = 'WordPress';
	} else {
		return;
	}
	$version = 'PHP' == $flag ? $php : $wp;
	deactivate_plugins( CP_V2_DIR_NAME );
	wp_die(
		'<p><strong>' . CP_PRO_NAME . ' </strong> requires <strong>' . $flag . '</strong> version <strong>' . $version . '</strong> or greater. Please contact your host.</p>', 'Plugin Activation Error', array(
			'response'  => 200,
			'back_link' => true,
		)
	);
}

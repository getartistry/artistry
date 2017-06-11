<?php
/**
 * Plugin Name: WP Clips
 * Plugin URI: http://wpclips.net
 * Description: Protect your code customizations with an update-safe Clip.
 * Version: 2.0.2
 * Author: Krolyn Studios
 * Author URI: http://krolyn.com
 * License: GPLv2
 *
 * Text Domain: wp-clips
 * Domain Path: /languages/
 *
 * @package WP_Clips
 * @author Krolyn Studios
 * @license GPLv2
 * @link http://wpclips.net
 * @version 2.0.2
 */


if( ! defined( 'ABSPATH' ) ) exit;


// Define constants
define( 'WPCLIPS_ROOT', trailingslashit( dirname( __FILE__ ) ) );
define( 'WPCLIPS_VERSION', '2.0.2' );
define( 'WPCLIPS_CLIP', WPCLIPS_ROOT . 'clip_' );
define( 'WPCLIPS_PREC', WPCLIPS_ROOT . 'precoded/' );
define( 'WPCLIPS_UNCL', WPCLIPS_PREC . 'unclipped/' );

// Load textdomain
add_action( 'plugins_loaded', 'wp_clips_load_textdomain' );
function wp_clips_load_textdomain() {
	load_plugin_textdomain( 'wp-clips', false, WPCLIPS_ROOT . 'languages' );
}


/**
 * Includes
 *
 * @since 1.3.0
 */

// Add vitals check
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once( WPCLIPS_ROOT . 'includes/checks.php' );

// Add admin Control (if in admin)
if( is_admin() )
	require_once( WPCLIPS_ROOT . 'includes/admin.php' );

// Add uninstalls (if plugin delete)
register_uninstall_hook( __FILE__ , 'wp_clips_uninstall' );
function wp_clips_uninstall() {
	foreach( wp_clips_array() as $clipdir ) {
		$uninstall = $clipdir . '/uninstall.php';
		if( file_exists( $uninstall ) )
			include $uninstall;
	}
}


/**
 * Load all active Clips
 *
 * @since 2.0.1
 */

add_action( 'after_setup_theme', 'wp_clips_initialize', 5 );

function wp_clips_initialize() {

	// Check is not multisite
	if( is_multisite() ) return;

	// Load Precoded Clips (if any)
	$clipdirs = WPCLIPS_PREC . 'clip-*/';
	foreach( glob( $clipdirs ) as $clipdir ) {

		// Vitals check before loading
		if( file_exists( $clipdir . 'vitals.php' ) ) {
			$themes = $plugins = null;
			include_once( $clipdir . 'vitals.php' );
			if( ! wp_clips_vitals_check( $themes, $plugins, $clipdir ) )
				continue;
		}
		include_once( $clipdir . 'clip-functions.php' );
	}

	// Include core Clip functions
	$file = WPCLIPS_CLIP . 'core/core-functions.php';
	if( file_exists( $file ) ) include_once( $file );

	// Vitals check before loading custom Clip
	$file = WPCLIPS_CLIP . 'custom/vitals.php';
	if( file_exists( $file ) ) {
		$themes = $plugins = null;
		include_once( $file );
		$clipdir = WPCLIPS_CLIP . 'custom';
		if( ! wp_clips_vitals_check( $themes, $plugins, $clipdir ) )
			return;
	}

	// Load custom functions
	$file = WPCLIPS_CLIP . 'custom/custom-functions.php';
	if( file_exists( $file ) ) include_once( $file );

	// Enqueue custom jquery and stylesheet
	add_action( 'wp_enqueue_scripts', 'wp_clips_custom_scripts', 15 );
}


/**
 * Enqueue custom Clip jquery and stylesheet (if exists)
 *
 * @since 2.0.0
 */

function wp_clips_custom_scripts() {

	// Load custom jquery
	$file = 'clip_custom/custom-jquery.js';
	if( file_exists( WPCLIPS_ROOT . $file ) )
		wp_enqueue_script( 'clip-custom-jquery', plugins_url( $file, __FILE__ ),
							array( 'jquery' ), WPCLIPS_VERSION, true
		);

	// Load custom styles
	$file = 'clip_custom/custom-style.css';
	if( file_exists( WPCLIPS_ROOT . $file ) )
		wp_enqueue_style(  'clip-custom-style', plugins_url( $file, __FILE__ ),
							array(), WPCLIPS_VERSION
		);
}
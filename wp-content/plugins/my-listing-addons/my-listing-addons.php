<?php
/*
Plugin Name: MyListing Addons
Description: This plugin provides some of the functionality for the MyListing theme from 27collective.
Author: 27collective
Author URI: http://27collective.net/
Text Domain: my-listing
Domain Path: /languages/
Version: 1.0.40
*/

if ( ! defined( 'CASE27_PLUGIN_DIR' ) ) {
	define( 'CASE27_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CASE27_PLUGIN_URL' ) ) {
	define( 'CASE27_PLUGIN_URL', plugins_url( null, __FILE__ ) );
}

// Post Types.
require_once CASE27_PLUGIN_DIR . '/includes/post-types.php';

// Taxonomies.
require_once CASE27_PLUGIN_DIR . '/includes/taxonomies.php';

// Shortcodes.
require_once CASE27_PLUGIN_DIR . '/shortcodes/shortcodes.php';

// Widgets.
require_once CASE27_PLUGIN_DIR . '/widgets/widgets.php';

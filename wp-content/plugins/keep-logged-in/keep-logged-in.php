<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://artistry.ink/tony
 * @since             1.0.0
 * @package           Keep_Logged_In
 *
 * @wordpress-plugin
 * Plugin Name:       Keep Logged In
 * Plugin URI:        https://artistry.ink/plugins/keep-logged-in.zip
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Anthony O Connell
 * Author URI:        https://artistry.ink/tony
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       keep-logged-in
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-keep-logged-in-activator.php
 */
function activate_keep_logged_in() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-keep-logged-in-activator.php';
	Keep_Logged_In_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-keep-logged-in-deactivator.php
 */
function deactivate_keep_logged_in() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-keep-logged-in-deactivator.php';
	Keep_Logged_In_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_keep_logged_in' );
register_deactivation_hook( __FILE__, 'deactivate_keep_logged_in' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-keep-logged-in.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_keep_logged_in() {

	$plugin = new Keep_Logged_In();
	$plugin->run();

}
run_keep_logged_in();

<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              codepixelzmedia.com.np
 * @since             1.0.0
 * @package           Woodivi
 *
 * @wordpress-plugin
 * Plugin Name:       Woo-Divi
 * Plugin URI:        codepixelzmedia.com.np
 * Description:       Divi Extension for WooCommerce Product Add to Cart button for simple Product.
 * Version:           1.0.0
 * Author:            CodePixelzMedia, Bishal Basnet
 * Author URI:        codepixelzmedia.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woodivi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woodivi-activator.php
 */
function activate_woodivi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woodivi-activator.php';
	Woodivi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woodivi-deactivator.php
 */
function deactivate_woodivi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woodivi-deactivator.php';
	Woodivi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woodivi' );
register_deactivation_hook( __FILE__, 'deactivate_woodivi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woodivi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woodivi() {

	$plugin = new Woodivi();
	$plugin->run();

}
run_woodivi();

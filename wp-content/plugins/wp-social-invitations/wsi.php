<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://wp.timersys.com/wordpress-social-invitations/
 * @since             2.5
 * @package           Wsi
 *
 * @wordpress-plugin
 * Plugin Name:       Wordpress Social Invitations - Lite
 * Plugin URI:        http://wp.timersys.com/wordpress-social-invitations/
 * Description:       WSI let's you invite your social friends to the site
 * Version:           2.1.1
 * Author:            Damian Logghe
 * Author URI:        https://wp.timersys.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wsi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
global $blog_id;

define( 'WSI_VERSION'       , '2.1.1');
define( 'WSI_PLUGIN_FILE'   , __FILE__);
define( 'WSI_PLUGIN_DIR'    , plugin_dir_path(__FILE__) );
define( 'WSI_PLUGIN_URL'    , plugin_dir_url(__FILE__) );
define( 'WSI_PLUGIN_HOOK'   , basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'WSI_CRON_TOKEN'    , md5(__FILE__.$blog_id));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wsi-activator.php
 */
function activate_wsi($network_wide) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsi-activator.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsi-upgrader.php';
	Wsi_Activator::activate($network_wide);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wsi-deactivator.php
 */
function deactivate_wsi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wsi-deactivator.php';
	Wsi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wsi' );
register_deactivation_hook( __FILE__, 'deactivate_wsi' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wsi.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.5.0
 */
function WSI() {

	$plugin = Wsi::instance();
	$plugin->run();
	return $plugin;
}

$GLOBALS['wsi_plugin'] = WSI();


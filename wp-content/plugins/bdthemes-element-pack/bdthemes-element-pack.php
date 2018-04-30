<?php
/**
 * Plugin Name: Element Pack
 * Plugin URI: http://bdthemes.com/
 * Description: Element Pack is a packed of elementor widget. This plugin gives you extra widgets features for elementor page builder plugin.
 * Version: 1.12.0
 * Author: BdThemes
 * Author URI: https://bdthemes.com/
 * Text Domain: bdthemes-element-pack
 * Domain Path: /languages
 * License: GPL3
 * Elementor requires at least: 1.9.6
 * Elementor tested up to: 2.0.6
 */

// Some pre define value for easy use
define( 'BDTEP_VER', '1.12.0' );
define( 'BDTEP__FILE__', __FILE__ );
define( 'BDTEP_PNAME', basename( dirname(BDTEP__FILE__)) );
define( 'BDTEP_PBNAME', plugin_basename(BDTEP__FILE__) );
define( 'BDTEP_PATH', plugin_dir_path( BDTEP__FILE__ ) );
define( 'BDTEP_MODULES_PATH', BDTEP_PATH . 'modules/' );
define( 'BDTEP_URL', plugins_url( '/', BDTEP__FILE__ ) );
define( 'BDTEP_ASSETS_URL', BDTEP_URL . 'assets/' );
define( 'BDTEP_MODULES_URL', BDTEP_URL . 'modules/' );


// Helper function here
include(dirname(__FILE__).'/includes/helper.php');

/**
 * Plugin load here correctly
 * Also loaded the language file from here
 */
function bdthemes_element_pack_load_plugin() {
    load_plugin_textdomain( 'bdthemes-element-pack', false, basename( dirname( __FILE__ ) ) . '/languages' );

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'bdthemes_element_pack_fail_load' );
		return;
	}
    require( BDTEP_PATH . 'includes/class.settings-api.php' );
    require( BDTEP_PATH . 'includes/admin-settings.php' );
	// Element pack widget and assets loader
    require( BDTEP_PATH . 'loader.php' );
}
add_action( 'plugins_loaded', 'bdthemes_element_pack_load_plugin' );


/**
 * Check Elementor installed and activated correctly
 */
function bdthemes_element_pack_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) { return; }
		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		$admin_message = '<p>' . esc_html__( 'Ops! Element Pack not working because you need to activate the Elementor plugin first.', 'bdthemes-element-pack' ) . '</p>';
		$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'bdthemes-element-pack' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) { return; }
		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$admin_message = '<p>' . esc_html__( 'Ops! Element Pack not working because you need to install the Elementor plugin', 'bdthemes-element-pack' ) . '</p>';
		$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'bdthemes-element-pack' ) ) . '</p>';
	}

	echo '<div class="error">' . $admin_message . '</div>';
}

/**
 * Check the elementor installed or not
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {

    function _is_elementor_installed() {
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();

        return isset( $installed_plugins[ $file_path ] );
    }
}
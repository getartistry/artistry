<?php
/**
 * Plugin Name: Elementor Button Plus
 * Description: Additional Styles and Options for elementor Button Widget.
 * Plugin URI: https://flickdevs.com/elementor/elementor-button-plus/
 * Author: FlickDevs
 * Version: 1.2
 * Author URI: http://www.flickdevs.com/
 *
 * Text Domain: fd-elementor-button-plus
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

define('ELEMENTOR_ADVANCED_BUTTON_URL', plugins_url('/', __FILE__));  // Define Plugin URL
define('ELEMENTOR_ADVANCED_BUTTON_PATH', plugin_dir_path(__FILE__));  // Define Plugin Directory Path
define('FD_EBP', 'fd-elementor-button-plus');

// load the plugin category
require_once ELEMENTOR_ADVANCED_BUTTON_PATH . 'inc/elementor-helper.php';

/**
 * Load Elementor Button Plus element
 * @since 1.0.0
 *
 */
function fd_ele_btn_plus_element() {
    require_once ELEMENTOR_ADVANCED_BUTTON_PATH . 'elements/fd-elementor-button-plus.php';
}

add_action('elementor/widgets/widgets_registered', 'fd_ele_btn_plus_element');

/**
 * Define Elementor Button Plus element styles.
 * @since 1.0.0
 */
function fd_ele_btn_plus_script() {
    wp_enqueue_style('fd-btn-plus', ELEMENTOR_ADVANCED_BUTTON_URL . 'assets/css/fd-elementor-btn-plus.css', true);
}

add_action('wp_enqueue_scripts', 'fd_ele_btn_plus_script');

/**
 *   Check the elementor current version.
 */
function fd_ele_btn_plus_elementor_load_plugin() {
    load_plugin_textdomain('FD_EBP');
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'fd_ele_btn_plus_widgets_fail_load');
        return;
    }
    $required_elementor_version = '1.1.2';
    if (!version_compare(ELEMENTOR_VERSION, $required_elementor_version, '>=')) {
        add_action('admin_notices', 'fd_btn_plus_elementor_fail_load_out_of_date');
        return;
    }
}

add_action('plugins_loaded', 'fd_ele_btn_plus_elementor_load_plugin');

/**
 * Display admin notice about the plugin is not activated.
 */
function fd_ele_btn_plus_widgets_fail_load() {
    $screen = get_current_screen();
    if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
        return;
    }
    $plugin = 'elementor/elementor.php';
    if (_is_elementor_installed()) {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

        $admin_notice = '<p>' . __('<strong>Elementor Button Plus</strong> not working because you need to activate the Elementor plugin.', 'FD_EBP') . '</p>';
        $admin_notice .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Elementor Now', 'FD_EBP')) . '</p>';
    } else {
        if (!current_user_can('install_plugins')) {
            return;
        }
        $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

        $admin_notice = '<p>' . __('<strong>Elementor Button Plus</strong> not working because you need to install the Elemenor plugin', 'FD_EBP') . '</p>';
        $admin_notice .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, __('Install Elementor Now', 'FD_EBP')) . '</p>';
    }
    echo '<div class="error"><p>' . $admin_notice . '</p></div>';
}

/**
 * Display admin notice about the plugin is update elementor.
 */
function fd_btn_plus_elementor_fail_load_out_of_date() {
    if (!current_user_can('update_plugins')) {
        return;
    }
    $file_path = 'elementor/elementor.php';
    $upgrade_link = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
    $admin_notice = '<p>' . __('<strong>Elementor Button Plus</strong> not working because you are using an old version of Elementor.', 'FD_EBP') . '</p>';
    $admin_notice .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update Elementor Now', 'FD_EBP')) . '</p>';
    echo '<div class="error">' . $admin_notice . '</div>';
}

if (!function_exists('_is_elementor_installed')) {

    function _is_elementor_installed() {
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();
        return isset($installed_plugins[$file_path]);
    }

}

/**
 * Display admin notice on plugin activation about button plus review
 */
register_activation_hook(__FILE__, 'fd_btn_plus_plugin_activation');

function fd_btn_plus_plugin_activation() {
    $notices = get_option('button_plus_deferred_admin_notices', array());
    $notices[] = '<strong>Hi there! You have been using Elementor Button Plus on your site, I hope its been useful. If you are enjoying my plugin, would you mind rating it 5-star to help spread the word? It want take more than a minute. </strong><p><a href="https://wordpress.org/plugins/fd-elementor-button-plus/advanced/" target="_blank" class="rating-link"><strong> Yes, you deserv it </strong></a></p>';
    update_option('button_plus_deferred_admin_notices', $notices);
}

add_action('admin_notices', 'fd_btn_plus_user_review');

function fd_btn_plus_user_review() {
    if ($notices = get_option('button_plus_deferred_admin_notices')) {
        foreach ($notices as $notice) {
            echo "<div class='notice notice-success is-dismissible'><p>$notice</p></div>";
        }
        delete_option('button_plus_deferred_admin_notices');
    }
}

/**
 * Remove option button_plus_deferred_admin_notices on deactivate plugin
 */
register_deactivation_hook(__FILE__, 'button_plus_plugin_deactivation');

function button_plus_plugin_deactivation() {
    delete_option('button_plus_deferred_admin_notices');
}

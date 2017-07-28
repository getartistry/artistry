<?php

/**
 * Glossary plugin
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2016 GPL 2.0+
 * @license   GPL-2.0+
 * @link      http://codeat.co
 */
/*
 * Plugin Name:       Glossary
 * Plugin URI:        http://codeat.co/glossary
 * Description:       Easily add and manage a glossary with auto-link, tooltips and more. Improve your internal link building for a better SEO.
 * Version:           1.4.4
 * Author:            Codeat
 * Author URI:        http://codeat.co
 * Text Domain:       glossary-by-codeat
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * WordPress-Plugin-Boilerplate-Powered: v2.0.0
 *
 * @fs_premium_only admin/views/preview.php, admin/assets/js/customizer.js, admin/assets/js/preview.js, admin/includes/Glossary_Custom_Fields.php, admin/includes/Glossary_ACF_Admin.php, admin/includes/Glossary_ACF.php, public/assets/js, public/assets/css-pro, public/includes/Glossary_Css_Customizer.php, public/includes/Glossary_Term_Content.php, public/includes/Glossary_ACF.php, includes/load_textdomain.php, includes/Glossary_Rest.php, includes/media-functions.php, includes/widgets/search.php, /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
define( 'GT_VERSION', '1.4.4' );
define( 'GT_SETTINGS', 'glossary' );
define( 'GT_TEXTDOMAIN', 'glossary-by-codeat' );
/**
 * Create a helper function for easy SDK access.
 *
 * @global type $gt_fs
 * @return object
 */
function gt_fs()
{
    global  $gt_fs ;
    
    if ( !isset( $gt_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
        $gt_fs = fs_dynamic_init( array(
            'id'             => '594',
            'slug'           => 'glossary-by-codeat',
            'type'           => 'plugin',
            'public_key'     => 'pk_229177eead299a4c9212f5837675e',
            'is_premium'     => false,
            'has_addons'     => false,
            'has_paid_plans' => true,
            'menu'           => array(
            'slug'   => 'glossary',
            'parent' => array(
            'slug' => 'edit.php?post_type=glossary',
        ),
        ),
            'is_live'        => true,
        ) );
    }
    
    return $gt_fs;
}

// Init Freemius.
gt_fs();
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';
/*
 * Load library for simple and fast creation of Taxonomy and Custom Post Type
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/Taxonomy_Core/Taxonomy_Core.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/CPT_Core/CPT_Core.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/Glossary_Cron.php';
/*
 * Load Widgets
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/Widgets-Helper/wph-widget-class.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/widgets/last_glossary.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/widgets/categories.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/widgets/a2z.php';
/*
 * Load the plugin
 */
require_once plugin_dir_path( __FILE__ ) . 'public/class-glossary.php';
add_action( 'plugins_loaded', array( 'Glossary', 'get_instance' ), 9999 );

if ( is_admin() ) {
    // Load few libraries used in administration
    require_once plugin_dir_path( __FILE__ ) . 'admin/includes/WP-Dismissible-Notices-Handler/handler.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/includes/WP_Review_Me.php';
    // Load the admin part of the plugin
    require_once plugin_dir_path( __FILE__ ) . 'admin/class-glossary-admin.php';
    add_action( 'plugins_loaded', array( 'Glossary_Admin', 'get_instance' ) );
}

gt_fs()->add_action( 'after_uninstall', 'gt_uninstall' );
/**
 * Uninstall action
 *
 * @global object $wpdb
 * @return void
 */
function gt_uninstall()
{
    global  $wpdb ;
    
    if ( is_multisite() ) {
        $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
        if ( $blogs ) {
            foreach ( $blogs as $blog ) {
                switch_to_blog( $blog['blog_id'] );
                gt_remove_settings();
                restore_current_blog();
            }
        }
    } else {
        gt_remove_settings();
    }

}

/**
 * Remove all the settings of the plugin, used on the uninstall hook
 *
 * @return void
 */
function gt_remove_settings()
{
    delete_option( 'glossary-settings' );
}
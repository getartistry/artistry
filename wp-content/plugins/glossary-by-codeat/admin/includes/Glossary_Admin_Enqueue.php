<?php

/**
 * The Admin Enqueue
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @license   GPL-2.0+
 * @link      http://codeat.co
 * @copyright 2016 GPL 2.0+
 */
/**
 * Enqueue Admin stuff the right way
 */
class Glossary_Admin_Enqueue
{
    /**
     * Slug of the plugin screen.
     *
     * @var string
     */
    protected  $screen_hook_suffix = null ;
    /**
     * Hooks
     *
     * @since     1.0.0
     */
    public function __construct()
    {
        // Add the options page and menu item.
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        // Load admin style sheet and JavaScript.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        // Load admin style in dashboard for the At glance widget
        add_action( 'admin_head-index.php', array( $this, 'enqueue_admin_styles' ) );
    }
    
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function add_plugin_admin_menu()
    {
        $this->screen_hook_suffix = add_submenu_page(
            'edit.php?post_type=glossary',
            __( 'Settings', GT_TEXTDOMAIN ),
            __( 'Settings', GT_TEXTDOMAIN ),
            'manage_options',
            GT_SETTINGS,
            array( 'Glossary_Admin', 'display_plugin_admin_page' )
        );
    }
    
    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since 1.0.0
     *
     * @return void Return early if no settings page is registered.
     */
    public function enqueue_admin_styles()
    {
        $screen = '';
        
        if ( function_exists( 'get_current_screen' ) ) {
            $screen = get_current_screen();
            $screen = $screen->id;
        }
        
        if ( $this->screen_hook_suffix === $screen || strpos( $_SERVER['REQUEST_URI'], 'index.php' ) ) {
            wp_enqueue_style(
                GT_SETTINGS . '-admin-styles',
                plugins_url( 'admin/assets/css/admin.css', GT_PLUGIN_ABSOLUTE ),
                array( 'dashicons' ),
                GT_VERSION
            );
        }
    }
    
    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since 1.0.0
     *
     * @return void Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts()
    {
        $screen = '';
        wp_enqueue_script(
            GT_SETTINGS . '-admin-script',
            plugins_url( 'admin/assets/js/admin.js', GT_PLUGIN_ABSOLUTE ),
            array( 'jquery', 'jquery-ui-tabs' ),
            GT_VERSION
        );
        wp_enqueue_style( GT_SETTINGS . '-admin-single-style', plugins_url( 'admin/assets/css/glossary-admin.css', GT_PLUGIN_ABSOLUTE ) );
        
        if ( function_exists( 'get_current_screen' ) ) {
            $screen = get_current_screen();
            $posttype = $screen->post_type;
            $screen = $screen->id;
        }
        
        if ( $posttype === 'glossary' ) {
            wp_enqueue_script(
                GT_SETTINGS . '-admin-pt-script',
                plugins_url( 'admin/assets/js/pt.js', GT_PLUGIN_ABSOLUTE ),
                array( 'jquery' ),
                GT_VERSION
            );
        }
        if ( !isset( $this->plugin_screen_hook_suffix ) ) {
            return;
        }
        if ( $this->screen_hook_suffix === $screen ) {
        }
    }

}
new Glossary_Admin_Enqueue();
<?php
/*
Plugin Name: Premium Addons for Elementor
Description: Premium Addons Plugin Includes 20+ premium widgets for Elementor Page Builder.
Plugin URI: https://premiumaddons.com
Version: 2.7.2
Author: Leap13
Author URI: http://leap13.com/
Text Domain: premium-addons-for-elementor
Domain Path: /languages
License: GNU General Public License v3.0
*/


/**
* Checking if WordPress is installed
*/
if (! function_exists('add_action')) {
    die('WordPress not Installed'); // if WordPress not installed kill the page.
}

if ( ! defined('ABSPATH') ) exit; // No access of directly access


define('PREMIUM_ADDONS_VERSION', '2.7.2');
define('PREMIUM_ADDONS_URL', plugins_url('/', __FILE__));
define('PREMIUM_ADDONS_PATH', plugin_dir_path(__FILE__));
define('PREMIUM_ADDONS_FILE', __FILE__);
define('PREMIUM_ADDONS_BASENAME', plugin_basename(__FILE__));
define('PREMIUM_ADDONS_STABLE_VERSION', '2.7.1');

if( ! class_exists('Premium_Addons_Elementor') ) {
    /*
    * Intialize and Sets up the plugin
    */
    class Premium_Addons_Elementor {
        
        private static $instance = null;
        
        /**
        * Sets up needed actions/filters for the plug-in to initialize.
        * @since 1.0.0
        * @access public
        * @return void
        */
        public function __construct() {
            
            add_action('plugins_loaded', array( $this, 'premium_addons_elementor_setup') );
            
            register_activation_hook(__FILE__, array( $this, 'pa_activation') );
            add_action('admin_init', array( $this, 'pa_redirection' ) );
            
            add_action('elementor/init', array( $this, 'create_premium_category') );
            
            add_action( 'init', array( $this, 'init_addons' ), 0 );
 
            add_action( 'admin_post_premium_addons_rollback', 'post_premium_addons_rollback' );
            
        }
        
        public function pa_activation() {
            add_option('pa_activation_redirect', true);
        }

        /*
         * Redirects to Premium Widgets Settings settings after activation
         * @since 1.0.0
         * @return void
         */
        public function pa_redirection() {
    
            if ( get_option('pa_activation_redirect', false ) ) {
                
                delete_option('pa_activation_redirect');
                
                if ( ! is_network_admin() ) {
                    
                    wp_redirect("admin.php?page=premium-addons");
                    
                }
            }   
        }
        
        /**
        * Installs translation text domain and checks if Elementor is installed
        * @since 1.0.0
        * @access public
        * @return void
        */
        public function premium_addons_elementor_setup() {
            $this->load_domain();
            
            $this->init_files(); 
        }
        
        /**
         * Require initial necessary files
         * @since 2.6.8
         * @access public
         * @return void
         */
        public function init_files(){
            if ( is_admin() ) {
                require_once (PREMIUM_ADDONS_PATH . 'includes/system-info.php');
                require_once (PREMIUM_ADDONS_PATH . 'includes/maintenance.php');
                require_once (PREMIUM_ADDONS_PATH . 'includes/rollback.php');
                require_once (PREMIUM_ADDONS_PATH . 'includes/class-beta-testers.php');
                require_once (PREMIUM_ADDONS_PATH . 'plugin.php');
                require_once (PREMIUM_ADDONS_PATH . 'admin/includes/notices.php' );
                require_once (PREMIUM_ADDONS_PATH . 'admin/settings/about.php');
                require_once (PREMIUM_ADDONS_PATH . 'admin/settings/version-control.php');
                require_once (PREMIUM_ADDONS_PATH . 'admin/settings/sys-info.php');
                require_once (PREMIUM_ADDONS_PATH . 'admin/settings/gopro.php');
                $beta_testers = new Premium_Beta_Testers();
            }
    
            require_once (PREMIUM_ADDONS_PATH . 'includes/helper-functions.php');
            require_once (PREMIUM_ADDONS_PATH . 'admin/settings/gomaps.php');
            require_once (PREMIUM_ADDONS_PATH . 'admin/settings/elements.php');
            require_once (PREMIUM_ADDONS_PATH . 'elementor-helper.php');
            
        }
        
        /**
         * Load plugin translated strings using text domain
         * @since 2.6.8
         * @access public
         * @return void
         */
        public function load_domain() {
            load_plugin_textdomain('premium-addons-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
        }
        
        /**
         * Creates Premium Widgets category
         * @since 2.6.8
         * @access public
         * @return void
         */
        public function create_premium_category() {
            require_once ( PREMIUM_ADDONS_PATH . 'includes/class-addons-category.php' );
        }
        
        /**
        * Load required file for addons integration
        * @return void
        */
        public function init_addons() {
            require_once ( PREMIUM_ADDONS_PATH . 'includes/class-addons-integration.php' );
        }
        
        /**
         * Creates and returns an instance of the class
         * @since 2.6.8
         * @access public
         * return object
         */
        public static function get_instance(){
            if( self::$instance == null ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
    
    }
}

if ( ! function_exists( 'premium_addons' ) ) {
	/**
	 * Returns an instance of the plugin class.
	 * @since  1.0.0
	 * @return object
	 */
	function premium_addons() {
		return Premium_Addons_Elementor::get_instance();
	}
}
premium_addons();
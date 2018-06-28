<?php 
/*
Plugin Name: Premium Addons for Elementor
Description: Premium Addons Plugin Includes 20 premium widgets for Elementor Page Builder.
Plugin URI: https://premiumaddons.com
Version: 2.3.6
Author: Leap13
Author URI: http://leap13.com/
Text Domain: premium-addons-for-elementor
Domain Path: /languages
License: GNU General Public License v3.0
*/


/**
* Checking the set ups and the environment
*/

if( !function_exists('add_action') ) {
	die('WordPress not Installed'); // if WordPress not installed kill the page.
}

if( !defined( 'ABSPATH' ) ) exit; // No access of directly access

define( 'PREMIUM_ADDONS_VERSION', '2.3.6' );
define( 'PREMIUM_ADDONS_URL', plugins_url('/', __FILE__ ) );
define( 'PREMIUM_ADDONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PREMIUM_ADDONS_FILE', __FILE__ );
define( 'PREMIUM_ADDONS_BASENAME', plugin_basename(__FILE__));
define( 'PREMIUM_ADDONS_STABLE_VERSION', '2.3.5');


	/**
	* Translating the plugin and load some 
	* assets
	*/
	add_action( 'plugins_loaded', 'premium_addons_elementor_setup');
	function premium_addons_elementor_setup() {
		// Loading .mo and.po file from the lang folder
            load_plugin_textdomain( 'premium-addons-for-elementor', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
            
		// Requires System Info When on Dashboard
            if(is_admin()){
                require_once( PREMIUM_ADDONS_PATH . 'includes/system-info.php' );
                require_once( PREMIUM_ADDONS_PATH . 'includes/maintenance.php' );
                require_once( PREMIUM_ADDONS_PATH . 'includes/rollback.php' );
                require_once( PREMIUM_ADDONS_PATH . 'includes/beta-testers.php' );
                $beta_testers = new PA_Beta_Testers();
            }

		// load the template tags
		if( file_exists( PREMIUM_ADDONS_PATH.'elementor-helper.php' ) ) {
			require_once( PREMIUM_ADDONS_PATH.'elementor-helper.php' );
		}
        
        if( file_exists( PREMIUM_ADDONS_PATH.'admin/settings-page.php' ) ) {
            require_once( PREMIUM_ADDONS_PATH.'admin/settings-page.php' );
		}
        
		/*
		* Instantiate the premium Addons for the elementor page builder
		* Included 1. 'premium_addons_widget_register()' for the main method for initaite addons
		*          2. 'premium_addons_widgets_area()'  creating the widgets
		*          3. 'initiate_elementor_addons()' creating the category of the widgets
		*/
		
		new premium_Addon_Elementor();
	}
    
    // CSS THAT DISPLAYS ON EDITOR PANEL
    add_action( 'elementor/editor/before_enqueue_scripts', function() {
        wp_register_style( 'premium-elements-progression-admin-styles', PREMIUM_ADDONS_URL . 'admin/assets/pa-elements-font/css/pa-elements.css' );
        wp_enqueue_style( 'premium-elements-progression-admin-styles' );
    } );
    
    /*Automatic Redirection Upon Activation*/
    register_activation_hook(__FILE__, 'pa_activation');
    add_action('admin_init', 'pa_redirection');
    function pa_activation() {
        add_option('pa_activation_redirect', true);
    }
    function pa_redirection() {
        if (get_option('pa_activation_redirect', false)) {
            delete_option('pa_activation_redirect');
	    if (!is_network_admin()) {
		  wp_redirect("admin.php?page=pa-settings-page");
            }
        }
    };
    
	class premium_Addon_Elementor {

        protected $pa_elements_keys = ['premium-banner', 'premium-blog','premium-carousel', 'premium-countdown','premium-counter','premium-dual-header','premium-fancytext','premium-image-separator','premium-maps','premium-modalbox','premium-person','premium-progressbar','premium-testimonials','premium-title','premium-videobox', 'premium-pricing-table', 'premium-contactform', 'premium-button', 'premium-image-button','premium-grid'];

		/**
		* Load all the hooks here
		* @since 1.0
		*/
		public function __construct() {
            add_action('elementor/init', array( $this, 'initiate_elementor_addons' ) );
			add_action('elementor/widgets/widgets_registered', array( $this, 'premium_addons_widget_register') );
			add_action('wp_enqueue_scripts', array( $this, 'premium_maps_required_script') );
            add_action('elementor/frontend/after_register_scripts', array($this, 'premium_addons_register_scripts'));
            add_action('elementor/frontend/after_register_styles', array($this, 'premium_addons_register_styles'));
            add_action('elementor/frontend/after_enqueue_styles', array($this, 'premium_addons_enqueue_styles'));
            add_action('admin_post_premium_addons_rollback', 'post_premium_addons_rollback');
		}

        /**
        * Register all frontend stylesheets
        */
        public function premium_addons_register_styles(){
            wp_register_style('premium-addons', PREMIUM_ADDONS_URL . 'assets/css/premium-addons.css', array(), PREMIUM_ADDONS_VERSION, 'all');
            $check_grid_active = isset(get_option('pa_save_settings')['premium-grid']) ? get_option('pa_save_settings')['premium-grid']: true;
            if($check_grid_active){
                wp_register_style('pa-prettyphoto', PREMIUM_ADDONS_URL . 'assets/css/prettyphoto.css', array(), PREMIUM_ADDONS_VERSION, 'all');
            }
        }
    
        /*
         * Enqueue all frontend stylesheets
         */
        public function premium_addons_enqueue_styles(){
            wp_enqueue_style('premium-addons');
            $check_grid_active = isset(get_option('pa_save_settings')['premium-grid']) ? get_option('pa_save_settings')['premium-grid']: true;
            if($check_grid_active){
                wp_enqueue_style('pa-prettyphoto');
            }
        }

        /*
         * Enqueue Premium Maps API script
         */
        public function premium_maps_required_script() {
            $premium_maps_api = get_option( 'pa_save_settings' )['premium-map-api'];
            $premium_maps_disable_api = get_option( 'pa_save_settings' )['premium-map-disable-api'];
            $premium_maps_enabled = get_option( 'pa_save_settings' )['premium-maps'];
            if ( $premium_maps_enabled == 1 && $premium_maps_disable_api == 1 ) {
                wp_enqueue_script('google-maps-script','https://maps.googleapis.com/maps/api/js?key='.$premium_maps_api , array(), PREMIUM_ADDONS_VERSION, false);
            }
        }
            
        public function premium_addons_register_scripts(){
            $pa_default_settings = array_fill_keys($this->pa_elements_keys, true);
        
            $check_component_active = get_option('pa_save_settings', $pa_default_settings);

            if( $check_component_active['premium-progressbar'] ) {
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
                wp_register_script('waypoints', PREMIUM_ADDONS_URL . 'assets/js/lib/jquery.waypoints.js' , array('jquery'), PREMIUM_ADDONS_VERSION , true);
            }

            if( $check_component_active['premium-videobox'] ) {
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            }

            if( $check_component_active['premium-grid'] ) {
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
                wp_register_script('isotope-js', PREMIUM_ADDONS_URL . 'assets/js/lib/isotope.js',  array( 'jquery' ), PREMIUM_ADDONS_VERSION, true);
                wp_register_script('prettyPhoto-js', PREMIUM_ADDONS_URL . 'assets/js/lib/prettyPhoto.js',  array( 'jquery' ), PREMIUM_ADDONS_VERSION, true);
            }

            if( $check_component_active['premium-counter'] ) {
                wp_register_script( 'counter-up-js', PREMIUM_ADDONS_URL .'assets/js/lib/countUpmin.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
                wp_register_script('waypoints', PREMIUM_ADDONS_URL . 'assets/js/lib/jquery.waypoints.js' , array('jquery'), PREMIUM_ADDONS_VERSION , true);
            }

            if( $check_component_active['premium-fancytext'] ) {
                wp_register_script('vticker-js', PREMIUM_ADDONS_URL . 'assets/js/lib/Vticker.js',  array( 'jquery' ), PREMIUM_ADDONS_VERSION, true);
                wp_register_script('typed-js', PREMIUM_ADDONS_URL . 'assets/js/lib/typedmin.js',  array( 'jquery' ), PREMIUM_ADDONS_VERSION, true);
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            }

            if( $check_component_active['premium-countdown'] ) {
                wp_register_script( 'count-down-timer-js', PREMIUM_ADDONS_URL .'assets/js/lib/jquerycountdown.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, 
                    true );
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            }

            if( $check_component_active['premium-carousel'] ) {
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
                wp_register_script( 'slick-carousel-js', PREMIUM_ADDONS_URL . 'assets/js/lib/slickmin.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            }

            if( $check_component_active['premium-banner'] ) {
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            }

            if( $check_component_active['premium-modalbox'] ) {
                wp_register_script( 'modal-js', PREMIUM_ADDONS_URL .'assets/js/lib/modal.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
                wp_register_script( 'premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array( 'jquery' ), PREMIUM_ADDONS_VERSION, true );
            }

            if ($check_component_active['premium-maps']) {
                wp_register_script('premium-maps-js', PREMIUM_ADDONS_URL . 'assets/js/premium-maps.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            }
        }

		public function premium_addons_widget_register() {
			$this->initiate_elementor_addons();
			$this->premium_addons_widgets_area();
		}

		private function premium_addons_widgets_area() {
            $pa_default_settings = array_fill_keys( $this->pa_elements_keys, true );
            $check_component_active = get_option( 'pa_save_settings', $pa_default_settings );
            
            foreach($check_component_active as $element_name  => $element_active){
                if($element_active && $element_name != 'premium-contactform' && $element_name != 'premium-map-api' && $element_name != 'premium-map-disable-api' && $element_name != 'is-beta-tester' ){
                    if($element_name == 'premium-blog'){
                        require_once (PREMIUM_ADDONS_PATH . 'queries.php');
                    }
                    require_once (PREMIUM_ADDONS_PATH . 'widgets/' . $element_name . '.php');
                } elseif ($element_active && $element_name == 'premium-contactform' && function_exists('wpcf7')){
                    require_once (PREMIUM_ADDONS_PATH . 'widgets/' . $element_name . '.php');
                }
            }
		}

		public function initiate_elementor_addons() {
			Elementor\Plugin::instance()->elements_manager->add_category(
				'premium-elements',
				array(
					'title' => __( 'Premium Addons', 'premium-addons-for-elementor' )
				),
				1
			);
		}	
	}

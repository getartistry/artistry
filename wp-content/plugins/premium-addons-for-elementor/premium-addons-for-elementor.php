<?php 
/*
Plugin Name: Premium Addons for Elementor
Description: This Plugin Includes Elementor Page Builder’s Premium Addon Elements.
Plugin URI: https://premiumaddons.com
Version: 2.0.9
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

define( 'PREMIUM_ADDONS_URL', plugins_url('/', __FILE__ ) );
define( 'PREMIUM_ADDONS_PATH', plugin_dir_path( __FILE__ ) );
define( 'PREMIUM_ADDONS_FILE', __FILE__ );

	/**
	* Translating the plugins and load some 
	* assets
	*/
	add_action( 'plugins_loaded', 'premium_addons_elementor_setup');
	function premium_addons_elementor_setup() {
		// Loading .mo and.po file from the lang folder
            load_plugin_textdomain( 'premium-addons-for-elementor', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
            
		// Requires System Info When on Dashboard
            if(is_admin()){
                require_once(PREMIUM_ADDONS_PATH . 'includes/system-info.php');   
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
        if(!isset($_GET['activate-multi']))
            {   
                wp_redirect("admin.php?page=pa-settings-page");
            }
        }
    };

	class premium_Addon_Elementor {

		/**
		* Load all the hooks here
		* @since 1.0
		*/
        
		public function __construct() {
         add_action( 'elementor/init', array( $this, 'initiate_elementor_addons' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'premium_addons_widget_register') );
			add_action( 'wp_enqueue_scripts', array( $this, 'premium_addons_required_assets' ) );
            add_action( 'elementor/frontend/before_register_scripts', array($this, 'premium_addons_register_scripts'));
		}

		/**
		* Load all frontend assets file such as stylesheet & javascript files
		* @since 1.0
		*/
        
		public function premium_addons_required_assets() {
            wp_enqueue_style( 'premium-addons-css', PREMIUM_ADDONS_URL . 'assets/css/premium-addons.css', array(), '1.0', 'all' ); 
            $premium_maps_api = get_option( 'pa_save_settings' )['premium-map-api'];
            $premium_maps_disable_api = get_option( 'pa_save_settings' )['premium-map-disable-api'];
            $premium_maps_enabled = get_option( 'pa_save_settings' )['premium-maps'];
            if ( $premium_maps_enabled == 1 && $premium_maps_disable_api == 1 ) {
                wp_enqueue_script('google-maps-script','https://maps.googleapis.com/maps/api/js?key='.$premium_maps_api , array('jquery'), '1.0', false);
                } else {
                    wp_enqueue_script('jquery');
                }
            }
            
            public function premium_addons_register_scripts(){
                $check_component_active = get_option( 'pa_save_settings' );

                if( $check_component_active['premium-modalbox'] ) {
                    wp_register_script( 'modal-js', PREMIUM_ADDONS_URL .'assets/js/lib/modal.js', array( 'jquery' ), '3.3.7', true );
                }

                if( $check_component_active['premium-carousel'] ) {
                    wp_register_script( 'slick-carousel-js', PREMIUM_ADDONS_URL .'assets/js/lib/slickmin.js', array( 'jquery' ), '1.6.0', true );
                }
                if( $check_component_active['premium-countdown'] ) {
                    wp_register_script( 'count-down-timer-js', PREMIUM_ADDONS_URL .'assets/js/lib/jquerycountdown.js', array( 'jquery' ), '2.1.0', true );
                }       
                if( $check_component_active['premium-counter'] ) {
                    wp_register_script( 'counter-up-js', PREMIUM_ADDONS_URL .'assets/js/lib/countUpmin.js', array( 'jquery' ), '2.1.0', true );
                }
                if( $check_component_active['premium-fancytext'] ) {
                    wp_register_script('vticker-js', PREMIUM_ADDONS_URL .'assets/js/lib/Vticker.js',  array( 'jquery' ), '1.0', true);
                    wp_register_script('typed-js', PREMIUM_ADDONS_URL .'assets/js/lib/typedmin.js',  array( 'jquery' ), '1.0', true);
                }
            }

		
		public function premium_addons_widget_register() {
			$this->initiate_elementor_addons();
			$this->premium_addons_widgets_area();
		}

		private function premium_addons_widgets_area() {
            $pa_elements_keys = ['premium-banner', 'premium-blog','premium-carousel', 'premium-countdown','premium-counter','premium-dual-header','premium-fancytext','premium-image-separator','premium-maps','premium-modalbox','premium-person','premium-progressbar','premium-testimonials','premium-title','premium-videobox', 'premium-pricing-table', 'premium-contactform', 'premium-button', 'premium-image-button'];
            
            $pa_default_settings = array_fill_keys( $pa_elements_keys, true );
                
            $check_component_active = get_option( 'pa_save_settings', $pa_default_settings );
            
            if( $check_component_active['premium-banner'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-banner.php' );
            }
            if( $check_component_active['premium-carousel'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-carousel.php' );
            }
			
            if( $check_component_active['premium-countdown'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-countdown.php' );
            }
			
            if( $check_component_active['premium-counter'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-counter.php' );
            }
            
            if( $check_component_active['premium-image-separator'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-imageseparator.php' );
            }
            
            if( $check_component_active['premium-modalbox'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-modalbox.php' );
            }
            if( $check_component_active['premium-progressbar'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-progressbar.php' );
            }
            
            if( $check_component_active['premium-testimonials'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-testimonials.php' );
            }
            
            if( $check_component_active['premium-title'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-title.php' );
            }
            
            if( $check_component_active['premium-fancytext'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-fancytext.php' );
            }
            
            if( $check_component_active['premium-videobox'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-videobox.php' );
            }
            
            if( $check_component_active['premium-pricing-table'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-pricing-table.php' );
            }
            
            if( $check_component_active['premium-blog'] ) {
                require_once( PREMIUM_ADDONS_PATH.'queries.php' );
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-blog.php' );
            }
            
            if( $check_component_active['premium-person'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-person.php' );
            }
            
            if( $check_component_active['premium-maps'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-maps.php' );
            }
            
            if( $check_component_active['premium-dual-header'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-dual-header.php' );
            }
            if( $check_component_active['premium-button'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-button.php' );
            }
            if( function_exists('wpcf7') && $check_component_active['premium-contactform'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-contactform.php' );
            }
            if( $check_component_active['premium-image-button'] ) {
                require_once( PREMIUM_ADDONS_PATH. 'widgets/premium-image-button.php' );
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
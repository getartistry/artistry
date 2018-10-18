<?php

namespace PremiumAddons;

if( ! defined( 'ABSPATH' ) ) exit();

class Premium_Addons_Integration {
    
    private static $instance = null;
    
    /**
    * Initialize integration hooks
    *
    * @return void
    */
    public function __construct() {
        add_action( 'elementor/editor/before_enqueue_styles', array( $this, 'premium_font_setup' ) );

        add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'premium_required_assets' ) );
        
        add_action( 'elementor/widgets/widgets_registered', array( $this, 'premium_widgets_area' ) );
        
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'premium_register_scripts' ) );
        
        add_action('elementor/editor/before_enqueue_scripts', array($this,'premium_maps_get_address'));
            
        add_action('wp_enqueue_scripts', array($this, 'premium_maps_required_script'));
    }
    
    /**
    * Loads plugin icons font
    * @since 1.0.0
    * @access public
    * @return void
    */
    public function premium_font_setup() {
        wp_enqueue_style(
            'premium-addons-font',
            PREMIUM_ADDONS_URL . 'admin/assets/pa-elements-font/css/pa-elements.css'
        );
    }
    
    /** 
    * Enqueue required CSS files
    * @since 1.0.0
    * @access public
    */
    public function premium_required_assets() {
        wp_enqueue_style(
            'premium-addons',
            PREMIUM_ADDONS_URL . 'assets/css/premium-addons.css',
            array(),
            PREMIUM_ADDONS_VERSION,
            'all'
        );
        
        $check_grid_active = PA_admin_settings::get_enabled_keys()['premium-grid'];
        if( $check_grid_active ){
            wp_enqueue_style('pa-prettyphoto', PREMIUM_ADDONS_URL . 'assets/css/prettyphoto.css', array(), PREMIUM_ADDONS_VERSION, 'all');
        }
        
    }
    
    /** Load widgets require function
    * @since 1.0.0
    * @access public
    */
    public function premium_widgets_area() {
        $this->premium_widget_register();
    }
    
    /** Requires widgets files
    * @since 1.0.0
    * @access private
    */
    private function premium_widget_register() {

        $check_component_active = PA_admin_settings::get_enabled_keys();
        
        foreach ( glob( PREMIUM_ADDONS_PATH . 'widgets/' . '*.php' ) as $file ) {
            
            $slug = basename( $file, '.php' );
            
            $enabled = isset( $check_component_active[ $slug ] ) ? $check_component_active[ $slug ] : '';
            
            if ( filter_var( $enabled, FILTER_VALIDATE_BOOLEAN ) || ! $check_component_active ) {
                $this->register_addon( $file );
            }
        }

    }
    
    /** Registers required JS files
    * @since 1.0.0
    * @access public
    */
    public function premium_register_scripts() {

        $check_component_active = PA_admin_settings::get_enabled_keys();
        
        if ( $check_component_active['premium-progressbar'] || $check_component_active['premium-videobox'] || $check_component_active['premium-grid'] || $check_component_active['premium-fancytext'] || $check_component_active['premium-countdown'] || $check_component_active['premium-carousel'] || $check_component_active['premium-banner'] || $check_component_active['premium-maps'] || $check_component_active['premium-modalbox'] || $check_component_active['premium-blog'] || $check_component_active['premium-counter'] ) {
            wp_register_script('premium-addons-js', PREMIUM_ADDONS_URL . 'assets/js/premium-addons.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        
        if ( $check_component_active['premium-counter'] ) {
                wp_register_script('counter-up-js', PREMIUM_ADDONS_URL . 'assets/js/lib/countUpmin.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            }
        
        if ( $check_component_active['premium-grid']) {
            wp_register_script('isotope-js', PREMIUM_ADDONS_URL . 'assets/js/lib/isotope.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('prettyPhoto-js', PREMIUM_ADDONS_URL . 'assets/js/lib/prettyPhoto.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        
        if ( $check_component_active['premium-fancytext'] ) {
            wp_register_script('vticker-js', PREMIUM_ADDONS_URL . 'assets/js/lib/Vticker.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
            wp_register_script('typed-js', PREMIUM_ADDONS_URL . 'assets/js/lib/typedmin.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        
        if ( $check_component_active['premium-countdown'] ) {
            wp_register_script('count-down-timer-js', PREMIUM_ADDONS_URL . 'assets/js/lib/jquerycountdown.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        
        if ( $check_component_active['premium-carousel'] ) {
            wp_register_script('slick-carousel-js', PREMIUM_ADDONS_URL . 'assets/js/lib/slickmin.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
            
        if ( $check_component_active['premium-blog'] ) {
            wp_register_script('isotope-js', PREMIUM_ADDONS_URL . 'assets/js/lib/isotope.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        
        if ( $check_component_active['premium-modalbox'] ) {
            wp_register_script('modal-js', PREMIUM_ADDONS_URL . 'assets/js/lib/modal.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
        
        if ( $check_component_active['premium-maps'] ) {
            wp_register_script('premium-maps-js', PREMIUM_ADDONS_URL . 'assets/js/premium-maps.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);
        }
    }
    
    /*
    * Enqueue Premium Maps API script
    */
    public function premium_maps_required_script() {

        $premium_maps_api = PA_Gomaps::get_enabled_keys()['premium-map-api'];
        
        $premium_maps_disable_api = PA_Gomaps::get_enabled_keys()['premium-map-disable-api'];

        $premium_maps_enabled = PA_admin_settings::get_enabled_keys()['premium-maps'];

        if( $premium_maps_disable_api ) {
            
            wp_enqueue_script('premium-maps-api-js', 'https://maps.googleapis.com/maps/api/js?key=' . $premium_maps_api, array(), PREMIUM_ADDONS_VERSION, false);

        }

        if ( $premium_maps_enabled ) {

            wp_enqueue_script('google-maps-cluster', 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js', array(), PREMIUM_ADDONS_VERSION, false);

        }

    }
    
    public function premium_maps_get_address() {
        
        $premium_maps_api = PA_Gomaps::get_enabled_keys()['premium-map-api'];

        $premium_maps_disable_api = PA_Gomaps::get_enabled_keys()['premium-map-disable-api'];
        
        $map_enabled = PA_admin_settings::get_enabled_keys()['premium-maps'];
        
        $premium_maps_enabled = isset( $map_enabled ) ? $map_enabled : 1;

        if ( $premium_maps_disable_api ) {

            wp_enqueue_script('premium-maps-api-js', 'https://maps.googleapis.com/maps/api/js?key=' . $premium_maps_api, array(), PREMIUM_ADDONS_VERSION, false);

        }

        if( $premium_maps_enabled ) {

            wp_enqueue_script('premium-maps-address', PREMIUM_ADDONS_URL . 'assets/js/premium-maps-address.js', array('jquery'), PREMIUM_ADDONS_VERSION, true);

        }

    }
    
    /**
    * Register addon by file name
    *
    * @param  string $file            File name.
    * @param  object $widgets_manager Widgets manager instance.
    * @return void
    */
    public function register_addon( $file ) {
        $base  = basename( str_replace( '.php', '', $file ) );
        $class = ucwords( str_replace( '-', ' ', $base ) );
        $class = str_replace( ' ', '_', $class );
        $class = sprintf( 'Elementor\%s', $class );

        if( 'Elementor\Premium_Contactform' != $class ){
            require $file;
        } else {
            if( function_exists('wpcf7') ) {
                require $file;
            }
        }
        
        if ( 'Elementor\Premium_Blog' == $class ) {
            require_once ( PREMIUM_ADDONS_PATH . 'queries.php' );
        }

        if ( class_exists( $class ) ) {
            \Elementor\PLUGIN::instance()->widgets_manager->register_widget_type( new $class );
        }
    }
    
    /**
    * Creates and returns an instance of the class
    * @since 1.0.0
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
    

if ( ! function_exists( 'premium_addons_integration' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  1.0.0
	 * @return object
	 */
	function premium_addons_integration() {
		return Premium_Addons_Integration::get_instance();
	}
}
premium_addons_integration();
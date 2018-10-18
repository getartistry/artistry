<?php

namespace PremiumAddons;

if( ! defined( 'ABSPATH' ) ) exit();

class Premium_Addons_Category {
    
    private static $instance = null;
    
    public function __construct() {
        $this->create_premium_category();
    }
    
    public function create_premium_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'premium-elements',
            array(
                'title' => Helper_Functions::get_category()
            ),
        1);
    }
    /**
    * Creates and returns an instance of the class
    * @since  2.6.8
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
    

if ( ! function_exists( 'premium_addons_category' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 * @since  2.6.8
	 * @return object
	 */
	function premium_addons_category() {
		return Premium_Addons_Category::get_instance();
	}
}
premium_addons_category();
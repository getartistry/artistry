<?php
namespace PremiumAddons;

if ( ! defined( 'ABSPATH' ) ) exit;

class Plugin {

	public static $instance = null;

	
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
    
    public function init() {
		$this->init_components();
	}
    
    private function init_components() {
        new PA_About();
        new PA_Gomaps();
        new PA_Version_Control();
        new PA_System_Info();
    	$this->admin_settings = new PA_admin_settings();
		$this->settings = new Pro_Settings();
	}
    
    private function __construct() {
		add_action( 'init', array($this, 'init' ), 0 );
	}
}

if ( ! defined( 'ELEMENTOR_TESTS' ) ) {
	Plugin::instance();
}
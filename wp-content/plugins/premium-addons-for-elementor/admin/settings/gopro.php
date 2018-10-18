<?php
namespace PremiumAddons;

if ( ! defined( 'ABSPATH' ) ) exit;

class Pro_Settings {

    public function create_pro_menus(){
        
        add_submenu_page(
            'premium-addons',
            '',
            '<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . __( 'Get PRO Widgets & Addons', 'premium-addons-for-elementor' ),
            'manage_options',
            'premium-addons-pro',
            [ $this, 'handle_custom_redirects' ]
        );
        
    }


    public function handle_custom_redirects() {

        $theme_name = Premium_Admin_Notices::get_installed_theme();

        if ( empty( $_GET['page'] ) ) {
            return;
        }

        if ( 'premium-addons-pro' === $_GET['page'] ) {

            $url = sprintf('https://premiumaddons.com/pro/?utm_source=wp-menu&utm_medium=wp-dash&utm_campaign=get-pro&utm_term=%s', $theme_name );
            
            wp_redirect( $url );

            die();
        }
    }
    

	public function change_admin_menu_name(){
        global $submenu;
        if(isset($submenu['premium-addons'])){
            $submenu['premium-addons'][0][0] = __( 'Widgets Settings', 'premium-addons-for-elementor' );
        }
    }

    public function on_admin_init() {

        $this->handle_custom_redirects();

    }

	public function __construct() {

        add_action( 'admin_init', [ $this, 'on_admin_init' ] );

        add_action( 'admin_menu', array ($this,'create_pro_menus' ), 100 );

		add_action( 'admin_menu', array ($this, 'change_admin_menu_name'), 100);

	}    
}
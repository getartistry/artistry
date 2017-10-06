<?php
/*
Plugin Name: Frontend admin menu
Plugin URI: http://www.studiosweb.es/
Description: With this plugin you can create menus for administration and assign roles and then display them in the frontend / backend of the website.
Version: 2.8
Author: Alberto Pérez
Author URI: http://www.studiosweb.es
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UJ7J929GYWKLY
License: A "Slug" license name e.g. GPL2
*/

define( 'FRONTEND_ADMIN_MENU_VERSION', '2.8' );
define( 'FRONTEND_ADMIN_MENU_DIR', plugin_dir_path(__FILE__) );
define( 'FRONTEND_ADMIN_MENU_URL', plugin_dir_url(__FILE__) );

add_action( 'admin_enqueue_scripts', 'frontend_admin_menu_admin_resources' );

function frontend_admin_menu_admin_resources() {
    
    wp_register_style( 'backend_admin_menu_css', FRONTEND_ADMIN_MENU_URL . 'admin/css/styles.css' );
    wp_enqueue_style( 'backend_admin_menu_css' );
    
    wp_register_style( 'frontend_admin_menu_css', FRONTEND_ADMIN_MENU_URL . 'css/styles.css' );
    wp_enqueue_style( 'frontend_admin_menu_css' );
    
    wp_register_script( 'frontend_admin_menu_js', FRONTEND_ADMIN_MENU_URL . 'js/frontend-admin-menu.js', array( 'jquery' ), false, true  );
    wp_enqueue_script( 'frontend_admin_menu_js' );

}

add_action( 'wp_enqueue_scripts', 'frontend_admin_menu_frontend_resources' );

function frontend_admin_menu_frontend_resources() {
    
    wp_register_style( 'frontend_admin_menu_css', FRONTEND_ADMIN_MENU_URL . 'css/styles.css' );
    wp_enqueue_style( 'frontend_admin_menu_css' );
    
    wp_register_script( 'frontend_admin_menu_js', FRONTEND_ADMIN_MENU_URL . 'js/frontend-admin-menu.js', array( 'jquery' ), false, true  );
    wp_enqueue_script( 'frontend_admin_menu_js' );

}

add_filter( 'plugin_row_meta', 'frontend_admin_menu_row_meta', 10, 2 );

function frontend_admin_menu_row_meta( $links, $file ) {
    
    if ( strpos( $file, 'frontend-admin-menu.php') !== false ) {
        $new_links = 
        array(
        '<a href="admin.php?page=frontend_admin_menu_options_page">Settings</a>',
        '<b><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UJ7J929GYWKLY">Donate</a></b>'
        );	
        $links = array_merge( $links, $new_links );
    }
    return $links;
    
}

add_action( 'admin_menu', 'frontend_admin_menu_menu' );
function frontend_admin_menu_menu() {
    add_menu_page( 'Admin menu', 'Admin menu', 'manage_options', 'frontend_admin_menu_options_page', 'frontend_admin_menu_import_options_page', FRONTEND_ADMIN_MENU_URL.'admin/images/frontend_admin_menu-icon.png' );
}

function frontend_admin_menu_import_options_page() {
  require_once( FRONTEND_ADMIN_MENU_DIR . "admin/options_admin_page.php" );
}


register_activation_hook(__FILE__, 'frontend_admin_menu_activation');

function frontend_admin_menu_activation() {
    
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    
}

register_deactivation_hook( __FILE__, 'frontend_admin_menu_deactivation' );

function frontend_admin_menu_deactivation() {
    
    flush_rewrite_rules();
    
}

function frontend_admin_menu_menus() {
    
    $menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
    
    return $menus; 
    
}


add_action( 'wp_footer', 'frontend_admin_menu' );
add_action( 'admin_footer', 'frontend_admin_menu' );

function frontend_admin_menu() {
    
    ob_start();
    
    if ( is_user_logged_in() ) {
        
        global $current_user;
        
        $enabled = get_option( 'frontend_admin_menu_status' );
        $backend_admin = get_option( 'frontend_admin_menu_admin_menu_backend' );
        
        $rol = $current_user->roles[0]; 
        
        if ( isset( $rol ) ) {
            
            $menu = get_option( 'frontend_admin_menu_mapping_' . $rol );
            
        }

        if ( $enabled > 0 && ! empty( $menu ) ) {
            
            if ( ! is_admin() ) {
                
                print frontend_admin_menu_render( $menu );
                
            } elseif ($backend_admin > 0 && $rol !== 'administrator' ) {
                
                print frontend_admin_menu_render( $menu );
                
            }
        }
    }
  
}

add_action( 'wp_head', 'frontend_admin_menu_admin_bar' );

add_action( 'admin_head', 'frontend_admin_menu_admin_bar' );

function frontend_admin_menu_admin_bar() { 
    
    if ( is_user_logged_in() ) {
        
        global $current_user;
        
        $rol = $current_user->roles[0]; 

        $disabled_admin_bar = get_option( 'frontend_admin_menu_mapping_admin_bar_' . $rol );

        if ( $disabled_admin_bar > 0 ) {
            
            print '
            <style type="text/css">
                    #wpadminbar {
                        display:none;
                    }
                    html.wp-toolbar {
                        padding:0;
                    }
            </style>';
            
        } 
    }
}


add_action( 'admin_head', 'frontend_admin_menu_hide_items_backend_menu' );

function frontend_admin_menu_hide_items_backend_menu () {

    if ( is_user_logged_in() ) {
        
        global $current_user;

        $backend_admin = get_option( 'frontend_admin_menu_admin_menu_backend' );
        
        $rol = $current_user->roles[0]; 
  
        if ( is_admin() && $backend_admin > 0 && $rol !== 'administrator' ) {
            
            echo '<style type="text/css">#collapse-menu { display: none; visibility: hidden; }</style>';
                
            print '
            <style type="text/css">
                    .wp-admin #adminmenuback, .wp-admin #adminmenuwrap{
                        display:none;
                    }
                    .wp-admin #wpcontent, .wp-admin #wpfooter{
                        margin-left:0;
                    }
            </style>';
                
        }
    }
}


function frontend_admin_menu_render( $menu ) {
    
    $defaults = array(
	'menu'            => $menu,
	'container'       => 'div',
	'menu_class'      => 'frontend-admin-menu-menu',
	'echo'            => false,
	'fallback_cb'     => 'wp_page_menu',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
        'before'     => '<span class="expand"></span>',

    );
    
    $frontend_admin_menu_color = get_option( 'frontend_admin_menu_color' );
    
    if ( !empty( $frontend_admin_menu_color ) ) {
        
        $style_menu_icon = 'class="frontend-admin-menu-menu-'.$frontend_admin_menu_color.'"';
        
    }else{
        
        $style_menu_icon = 'class="frontend-admin-menu-menu-orange"';
        
    }

    $output = '<div id="frontend-admin-menu-menu" '.$style_menu_icon.'>';
        $output .= '<div id="frontend-admin-menu-icon">';
            $output .= '<img src="' . FRONTEND_ADMIN_MENU_URL . 'images/icon-settings.png" />';
        $output .= '</div>';
        $output .= '<div id="frontend-admin-menu-items">';
            $output .= wp_nav_menu( $defaults );
        $output .= '</div>';
    $output .= '</div>';
    
    return $output;
    
}
?>

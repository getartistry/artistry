<?php
/*
Plugin Name: Admin Menu
Plugin URI: https://dev.wall-f.com/admin_menu/wp-admin/admin.php?page=admin-menu-pro
Description: You can show/hide specific items, change icon, title, reorder the menus for users
Author: Rednumber
Version: 1.1
Author URI: https://dev.wall-f.com
*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
define( 'ADMIN_MENU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ADMIN_MENU_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ADMIN_MENU_TEXT_DOMAIN', "admin_menu_pro" );

include ADMIN_MENU_PLUGIN_PATH."class/settings.php";
include ADMIN_MENU_PLUGIN_PATH."class/admin_menu.php";
<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$roles = get_editable_roles();

foreach ( $roles as $key => $rol ) {
    delete_option( 'frontend_admin_menu_mapping_' . $key);
    delete_option( 'frontend_admin_menu_mapping_admin_bar_' . $key);
}

delete_option( 'frontend_admin_menu_status' );
delete_option( 'frontend_admin_menu_admin_bar' );
delete_option( 'frontend_admin_menu_admin_menu_backend' );
delete_option( 'frontend_admin_menu_color' );
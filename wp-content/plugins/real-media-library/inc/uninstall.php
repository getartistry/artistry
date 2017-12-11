<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
    exit();

function rml_uninstall() {
        $table_name = RML_Core::getInstance()->getTableName();
        
        global $wpdb;
        $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
?>
<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function rml_install() {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name = RML_Core::getInstance()->getTableName();
	$blog_id = get_current_blog_id();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		parent mediumint(9) DEFAULT '-1' NOT NULL,
		name tinytext NOT NULL,
		bid mediumint(10) DEFAULT $blog_id NOT NULL,
		ord mediumint(10) DEFAULT 999 NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'rml_db_version', RML_VERSION );
}

/*
function jal_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
	$table_name = $wpdb->prefix . 'liveshoutbox';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $welcome_name, 
			'text' => $welcome_text, 
		) 
	);
}
*/
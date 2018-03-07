<?php
/**
 * @class AW_System_Check_Database_Tables_Exist
 * @since 2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit;


class AW_System_Check_Database_Tables_Exist extends AutomateWoo\Base_System_Check {


	function __construct() {
		$this->title = __( 'Database Tables Installed', 'automatewoo' );
		$this->description = __( 'Checks the AutomateWoo custom database tables have been installed.', 'automatewoo' );
		$this->high_priority = true;
	}


	/**
	 * Perform the check
	 */
	function run() {

		global $wpdb;

		$tables = $wpdb->get_results( "SHOW TABLES LIKE '{$wpdb->prefix}automatewoo_%'", ARRAY_N );

		// currently 6 tables in core
		if ( count( $tables ) >= 8 ) {
			return $this->success();
		}

		return $this->error( __( 'Tables could not be installed.', 'automatewoo' ) );
	}

}

return new AW_System_Check_Database_Tables_Exist();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Database_Table_Logs
 * @since 2.8.2
 */
class Database_Table_Logs extends Database_Table {

	function __construct() {
		global $wpdb;

		$this->name = $wpdb->prefix . 'automatewoo_logs';
		$this->primary_key = 'id';
	}


	/**
	 * @return array
	 */
	function get_columns() {
		return [
			'id' => '%d',
			'workflow_id' => '%d',
			'date' => '%s',
			'tracking_enabled' => '%d',
			'conversion_tracking_enabled' => '%d',
			'has_errors' => '%d',
			'has_blocked_emails' => '%d',
		];
	}


	/**
	 * @return string
	 */
	function get_install_query() {
		return "CREATE TABLE {$this->name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			workflow_id bigint(20) NULL,
			date datetime NULL,
			tracking_enabled int(1) NOT NULL DEFAULT 0,
			conversion_tracking_enabled int(1) NOT NULL DEFAULT 0,
			has_errors int(1) NOT NULL DEFAULT 0,
			has_blocked_emails int(1) NOT NULL DEFAULT 0,
			PRIMARY KEY  (id),
			KEY workflow_id (workflow_id),
			KEY date (date),
			KEY workflow_id_date (workflow_id, date)
			) {$this->get_collate()};";
	}
}

return new Database_Table_Logs();

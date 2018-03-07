<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Database_Table_Queue
 * @since 2.8.2
 */
class Database_Table_Queue extends Database_Table {

	function __construct() {
		global $wpdb;

		$this->name = $wpdb->prefix . 'automatewoo_queue';
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
			'created' => '%s',
			'failed' => '%s',
			'failure_code' => '%d'
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
			created datetime NULL,
			data_items longtext NOT NULL default '',
			failed int(1) NOT NULL DEFAULT 0,
			failure_code int(3) NOT NULL DEFAULT 0,
			PRIMARY KEY  (id),
			KEY workflow_id (workflow_id),
			KEY date (date),
			KEY created (created)
			) {$this->get_collate()};";
	}
}

return new Database_Table_Queue();

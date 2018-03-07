<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Database_Table_Unsubscribes
 * @since 2.8.2
 */
class Database_Table_Unsubscribes extends Database_Table {

	function __construct() {
		global $wpdb;

		$this->name = $wpdb->prefix . 'automatewoo_unsubscribes';
		$this->primary_key = 'id';
	}


	/**
	 * @return array
	 */
	function get_columns() {
		return [
			'id' => '%d',
			'workflow_id' => '%d',
			'customer_id' => '%d',
			'date' => '%s',
		];
	}


	/**
	 * @return string
	 */
	function get_install_query() {
		return "CREATE TABLE {$this->name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			workflow_id bigint(20) NULL,
			customer_id bigint(20) NOT NULL default 0,
			date datetime NULL,
			PRIMARY KEY  (id),
			KEY workflow_id (workflow_id),
			KEY customer_id (customer_id),
			KEY date (date)
			) {$this->get_collate()};";
	}
}

return new Database_Table_Unsubscribes();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Database_Table_Guests
 * @since 2.8.2
 */
class Database_Table_Guests extends Database_Table {

	function __construct() {
		global $wpdb;

		$this->name = $wpdb->prefix . 'automatewoo_guests';
		$this->primary_key = 'id';
	}


	/**
	 * @return array
	 */
	function get_columns() {
		return [
			'id' => '%d',
			'email' => '%s',
			'tracking_key' => '%s',
			'created' => '%s',
			'last_active' => '%s',
			'language' => '%s',
			'ip' => '%s',
		];
	}


	/**
	 * @return string
	 */
	function get_install_query() {
		return "CREATE TABLE {$this->name} (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			email varchar(255) NOT NULL default '',
			tracking_key varchar(32) NOT NULL default '',
			created datetime NULL,
			last_active datetime NULL,
			language varchar(10) NOT NULL default '',
			ip tinytext NOT NULL,
			PRIMARY KEY  (id),
			KEY tracking_key (tracking_key),
			KEY email (email({$this->max_index_length}))
			) {$this->get_collate()};";
	}

}

return new Database_Table_Guests();

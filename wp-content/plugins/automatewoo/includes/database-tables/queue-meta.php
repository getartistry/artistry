<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Database_Table_Queue_Meta
 * @since 2.9.7
 */
class Database_Table_Queue_Meta extends Database_Table {

	function __construct() {
		global $wpdb;

		$this->name = $wpdb->prefix . 'automatewoo_queue_meta';
		$this->primary_key = 'meta_id';
		$this->object_id_column = 'event_id';
	}


	/**
	 * @return array
	 */
	function get_columns() {
		return [
			'meta_id' => '%d',
			'event_id' => '%d',
			'meta_key' => '%s',
			'meta_value' => '%s',
		];
	}


	/**
	 * @return string
	 */
	function get_install_query() {
		return "CREATE TABLE {$this->name} (
			meta_id bigint(20) NOT NULL AUTO_INCREMENT,
			event_id bigint(20) NULL,
			meta_key varchar(255) NULL,
			meta_value longtext NOT NULL default '',
			PRIMARY KEY  (meta_id),
			KEY event_id (event_id),
			KEY meta_key (meta_key({$this->max_index_length}))
			) {$this->get_collate()};";
	}
}

return new Database_Table_Queue_Meta();

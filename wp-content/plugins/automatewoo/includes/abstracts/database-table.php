<?php

namespace AutomateWoo;

/**
 * @class Database_Table
 * @since 2.8.2
 */
abstract class Database_Table {

	/** @var string */
	public $name;

	/** @var string - deprecated */
	public $version;

	/** @var string */
	public $primary_key;

	/** @var string (only for meta tables) */
	public $object_id_column;

	/** @var int */
	public $max_index_length = 191;


	/**
	 * @return array
	 */
	abstract function get_columns();


	/**
	 * @return string
	 * @since 2.9.9
	 */
	function get_install_query() {
		return '';
	}


	/**
	 * Install the database table
	 */
	function install() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $this->get_install_query() );
	}


	/**
	 * @return string
	 */
	function get_collate() {
		global $wpdb;
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		return $collate;
	}

}

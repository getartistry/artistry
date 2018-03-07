<?php

namespace AutomateWoo;

/**
 * @class Database_Tables
 * @since 2.8.2
 */
class Database_Tables {

	/** @var array */
	private $includes;

	/** @var Database_Table[] */
	private $tables;


	/**
	 * Init custom database tables
	 */
	function init() {
		$this->load_tables();
	}


	/**
	 * Updates any tables as required
	 */
	function install_tables() {
		global $wpdb;

		$wpdb->hide_errors();

		foreach( $this->get_tables() as $table ) {
			$table->install();
		}
	}


	/**
	 * @return array
	 */
	function get_includes() {
		if ( ! isset( $this->includes ) ) {

			$path = AW()->path( '/includes/database-tables/' );

			$this->includes = [
				'guests' => $path . 'guests.php',
				'guest-meta' => $path . 'guest-meta.php',
				'carts' => $path . 'carts.php',
				'queue' => $path . 'queue.php',
				'queue-meta' => $path . 'queue-meta.php',
				'logs' => $path . 'logs.php',
				'log-meta' => $path . 'log-meta.php',
				'unsubscribes' => $path . 'unsubscribes.php',
				'customers' => $path . 'customers.php',
				'events' => $path . 'events.php',
			];

			$this->includes = apply_filters( 'automatewoo/database_tables', $this->includes );
		}

		return $this->includes;
	}


	/**
	 * @return Database_Table[]
	 */
	function get_tables() {
		$this->check_loaded();
		return $this->tables;
	}


	/**
	 * @param $table_id
	 * @return Database_Table
	 */
	function get_table( $table_id ) {
		$this->check_loaded();
		return $this->tables[ $table_id ];
	}


	/**
	 * Check if database tables are loaded, will die if
	 */
	function check_loaded() {
		if ( ! isset( $this->tables ) ) {
			trigger_error( 'AutomateWoo - Tried to use database table before fully loaded.', E_USER_ERROR );
		}
	}


	/**
	 * Load all the table classes
	 *
	 * @return void
	 */
	private function load_tables() {

		if ( isset( $this->tables ) )
			return;

		$includes = $this->get_includes();
		$this->tables = [];

		foreach ( $includes as $table_id => $path ) {
			if ( file_exists( $path ) ) {
				$this->tables[ $table_id ] = include_once $path;
			}
		}
	}

}

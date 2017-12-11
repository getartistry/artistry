<?php
/**
 * Model class <i>SIB_Model_Contact</i> represents account
 *
 * @package SIB_Model
 */
class SIB_Model_Contact {

	/**
	 * Tab table name
	 */
	const TABLE_NAME = 'sib_model_contact';

	/**
	 * Holds found campaign count
     *
     * @var int
	 */
	static $found_count;

	/**
	 * Holds all campaign count
     *
     * @var int
	 */
	static $all_count;

	/** Create Table */
	public static function create_table() {
		global $wpdb;
		// create list table
		$creation_query =
			'CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`email` varchar(255),
            `info` TEXT,
            `code` varchar(100),
            `is_activate` int(2),
			`extra` TEXT,
			PRIMARY KEY (`id`)
			);';
		$wpdb->query( $creation_query );
	}

	/**
	 * Remove table
	 */
	public static function remove_table() {
		global $wpdb;
		$query = 'DROP TABLE IF EXISTS ' . self::TABLE_NAME . ';';
		$wpdb->query( $query );
	}

	/**
	 * Get data by id
	 *
	 * @param $id
	 */
	public static function get_data( $id ) {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME . ' where id=' . $id . ';';
		$results = $wpdb->get_results( $query, ARRAY_A );

		if ( is_array( $results ) ) {
			return $results[0];
		} else {
			return false;
		}
	}

	/**
	 * Get data by code
	 */
	public static function get_data_by_code( $code ) {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME . ' where code like "' . $code . '";';
		$results = $wpdb->get_results( $query, ARRAY_A );

		if ( is_array( $results ) && count( $results ) > 0 ) {
			return $results[0];
		} else {
			return false;
		}
	}

	/**
	 * Get code by email
	 */
	public static function get_data_by_email( $email ) {
		global $wpdb;
		$query = 'select * from ' . self::TABLE_NAME . ' where email like "' . $email . '";';
		$results = $wpdb->get_results( $query, ARRAY_A );

		if ( is_array( $results ) && count( $results ) > 0 ) {
			return $results[0];
		} else {
			return false;
		}
	}

	/** Add record */
	public static function add_record( $data ) {
		global $wpdb;

		if ( self::is_exist_same_email( $data['email'] ) == true ) {
			return false;
		}

		$query = 'INSERT INTO ' . self::TABLE_NAME . ' ';
		$query .= '(email,info,code,is_activate,extra) ';
		$query .= "VALUES ('{$data['email']}','{$data['info']}','{$data['code']}','{$data['is_activate']}','{$data['extra']}');";

		$wpdb->query( $query );

		$index = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' );

		return $index;

	}

	public static function is_exist_same_email( $email, $id = '' ) {
		global $wpdb;

		$query = 'select * from ' . self::TABLE_NAME . ' ';
		$query .= "where email like '" . $email . "' ;";

		$results = $wpdb->get_results( $query, ARRAY_A );

		if ( is_array( $results ) && (count( $results ) > 0) ) {
			if ( $id == '' ) {
				return true;
			}
			if ( isset( $results ) && is_array( $results ) ) {
				foreach ( $results as $result ) {
					if ( $result['id'] != $id ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/** Remove guest */
	public static function remove_record( $id ) {
		global $wpdb;

		$query = 'delete from ' . self::TABLE_NAME . ' ';
		$query .= 'where id=' . $id . ';';

		$wpdb->query( $query );
	}

	/** Get all guests by pagenum, per_page*/
	public static function get_all( $orderby = 'email', $order = 'asc', $pagenum = 1, $per_page = 15 ) {
		global $wpdb;

		$limit = ($pagenum - 1) * $per_page;
		$query = 'SELECT * FROM ' . self::TABLE_NAME . ' ';
		$query .= 'ORDER BY ' . $orderby . ' ' . $order . ' ';
		$query .= 'LIMIT ' . $limit . ',' . $per_page . ';';

		$results = $wpdb->get_results( $query, ARRAY_A );
		self::$found_count = self::get_count_element();

		if ( ! is_array( $results ) ) {
			$results = array();
			return $results;
		}

		return $results;
	}

	/** get all records of table */
	public static function get_all_records() {
		global $wpdb;

		$query = 'select * from ' . self::TABLE_NAME . ' order by email asc;';

		$results = $wpdb->get_results( $query, ARRAY_A );

		if ( ! is_array( $results ) ) {
			$results = array();
			return $results;
		}

		return $results;
	}

	/** get count of row */
	public static function get_count_element() {
		global $wpdb;

		$query = 'Select count(*) from ' . self::TABLE_NAME . ';';

		$count = $wpdb->get_var( $query );

		return $count;
	}

	/** update record */
	public static function update_element( $id, $data ) {
		global $wpdb;

		if ( self::is_exist_same_email( $data['email'], $id ) == true ) {
			return false;
		}

		$query = 'update ' . self::TABLE_NAME . ' ';
		$query .= "set email='{$data['email']}',info='{$data['info']}',code='{$data['code']}',is_activate='{$data['is_activate']}',extra='{$data['extra']}' ";
		$query .= 'where id=' . $id . ';';

		$wpdb->query( $query );

		return true;
	}

}

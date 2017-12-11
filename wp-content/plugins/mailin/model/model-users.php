<?php
/**
 * Model class <i>SIB_Model_Users</i> represents account
 *
 * @package SIB_Model
 */
class SIB_Model_Users {

	/**
	 * Tab table name
	 */
	const TABLE_NAME = 'sib_model_users';

	/**
	 * Holds found campaign count
	 *
	 * @var $found_count
	 */
	static $found_count;

	/**
	 * Holds all campaign count
	 *
	 * @var $all_count
	 */
	static $all_count;

	/** Create Table */
	public static function createTable() {
		global $wpdb;
		// create list table.
		$creation_query =
			'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . self::TABLE_NAME . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`email` varchar(255),
            `code` varchar(100),
            `listIDs` longtext,
            `redirectUrl` varchar(255),
            `info` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
            `frmid` int(2),
			PRIMARY KEY (`id`)
			);';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $creation_query );
	}

	/**
	 * Remove table
	 */
	public static function removeTable() {
		global $wpdb;
		$query = 'DROP TABLE IF EXISTS ' . $wpdb->prefix . self::TABLE_NAME . ';';
		$wpdb->query( $query ); // db call ok; no-cache ok.
	}

	/**
	 * Get data by id
	 *
	 * @param int $id - user id.
	 * @return bool|mixed
	 */
	public static function get_data( $id ) {
		global $wpdb;
		$query = 'select * from ' . $wpdb->prefix . self::TABLE_NAME . ' where id=' . $id . ';';
		$results = $wpdb->get_results( $query, ARRAY_A ); // db call ok; no-cache ok.

		if ( is_array( $results ) ) {
			return $results[0];
		} else {
			return false;
		}
	}

	/**
	 * Get data by code
	 *
	 * @param string $code - code.
	 * @return array|bool|null|object|void
	 */
	public static function get_data_by_code( $code ) {
		global $wpdb;
		$query = 'select * from ' . $wpdb->prefix . self::TABLE_NAME . ' where code like "' . $code . '";';
		$results = $wpdb->get_row( $query,ARRAY_A ); // db call ok; no-cache ok.

		if ( is_array( $results ) && count( $results ) > 0 ) {
			return $results;
		} else {
			return false;
		}
	}

	/**
	 * Get code by email.
	 *
	 * @param string $email - email.
	 * @param int    $formID - form ID.
	 * @return array|bool|null|object|void
	 */
	public static function get_data_by_email( $email, $formID ) {
		global $wpdb;
		$query = 'select * from ' . $wpdb->prefix . self::TABLE_NAME . ' where email = "' . $email . '" and frmid = "' . $formID . '";';
		$results = $wpdb->get_row( $query,ARRAY_A ); // db call ok; no-cache ok.

		if ( is_array( $results ) && count( $results ) > 0 ) {
			return $results;
		} else {
			return false;
		}
	}

	/**
	 * Add record
	 *
	 * @param array $data - record data.
	 * @return null|string
	 */
	public static function add_record( $data ) {
		global $wpdb;

		$query = 'INSERT INTO ' . $wpdb->prefix . self::TABLE_NAME . ' ';
		$query .= '(email,code,info,frmid,listIDs,redirectUrl) ';
		$query .= "VALUES ('{$data['email']}','{$data['code']}','{$data['info']}','{$data['frmid']}','{$data['listIDs']}','{$data['redirectUrl']}');";
		$wpdb->query( $query ); // db call ok; no-cache ok.
		$index = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' ); // db call ok; no-cache ok.
		return $index;
	}

	/**
	 * Check email exist
	 *
	 * @param string $email - email.
	 * @param string $id - id.
	 * @return bool
	 */
	public static function is_exist_same_email( $email, $id = '' ) {
		global $wpdb;

		$query = 'select * from ' . $wpdb->prefix . self::TABLE_NAME . ' ';
		$query .= "where email like '" . $email . "' ;";

		$results = $wpdb->get_results( $query, ARRAY_A ); // db call ok; no-cache ok.

		if ( is_array( $results ) && (count( $results ) > 0) ) {
			if ( '' === $id ) {
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

	/**
	 * Remove guest
	 *
	 * @param int $id - id.
	 */
	public static function remove_record( $id ) {
		global $wpdb;

		$query = 'delete from ' . $wpdb->prefix . self::TABLE_NAME . ' ';
		$query .= 'where id=' . $id . ';';

		$wpdb->query( $query ); // db call ok; no-cache ok.
	}

	/**
	 * Get all guests by pagenum, per_page
	 *
	 * @param string $orderby - ORDER BY.
	 * @param string $order - sort order.
	 * @param int    $pagenum - page number.
	 * @param int    $per_page - count per page.
	 * @return array|null|object
	 */
	public static function get_all( $orderby = 'email', $order = 'asc', $pagenum = 1, $per_page = 15 ) {
		global $wpdb;

		$limit = ($pagenum - 1) * $per_page;
		$query = 'SELECT * FROM ' . $wpdb->prefix . self::TABLE_NAME . ' ';
		$query .= 'ORDER BY ' . $orderby . ' ' . $order . ' ';
		$query .= 'LIMIT ' . $limit . ',' . $per_page . ';';

		$results = $wpdb->get_results( $query, ARRAY_A ); // db call ok; no-cache ok.
		self::$found_count = self::get_count_element();

		if ( ! is_array( $results ) ) {
			$results = array();
			return $results;
		}

		return $results;
	}

	/** Get all records of table */
	public static function get_all_records() {
		global $wpdb;

		$query = 'select * from ' . $wpdb->prefix . self::TABLE_NAME . ' order by email asc;';

		$results = $wpdb->get_results( $query, ARRAY_A ); // db call ok; no-cache ok.

		if ( ! is_array( $results ) ) {
			$results = array();
			return $results;
		}

		return $results;
	}

	/** Get count of row */
	public static function get_count_element() {
		global $wpdb;

		$query = 'Select count(*) from ' . $wpdb->prefix . self::TABLE_NAME . ';';

		$count = $wpdb->get_var( $query ); // db call ok; no-cache ok.

		return $count;
	}

	/**
	 * Update record
	 *
	 * @param int   $id - id.
	 * @param array $data - record data.
	 * @return bool
	 */
	public static function update_element( $id, $data ) {
		global $wpdb;

		if ( self::is_exist_same_email( $data['email'], $id ) == true ) {
			return false;
		}

		$query = 'update ' . $wpdb->prefix . self::TABLE_NAME . ' ';
		$query .= "set email='{$data['email']}',info='{$data['info']}',code='{$data['code']}',is_activate='{$data['is_activate']}',extra='{$data['extra']}' ";
		$query .= 'where id=' . $id . ';';

		$wpdb->query( $query ); // db call ok; no-cache ok.

		return true;
	}

	/** Add prefix to the table */
	public static function add_prefix() {
		global $wpdb;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . self::TABLE_NAME . "'" ) == self::TABLE_NAME ) {
			$query = 'ALTER TABLE ' . self::TABLE_NAME . ' RENAME TO ' . $wpdb->prefix . self::TABLE_NAME . ';';
			$wpdb->query( $query ); // db call ok; no-cache ok.
		}
	}

}

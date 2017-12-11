<?php
/**
 * Model class <i>SIB_Forms_Lang</i> represents forms language
 *
 * @package SIB_Model
 */

if ( ! class_exists( 'SIB_Forms_Lang' ) ) {
	/**
	 * Class SIB_Forms_Lang
	 */
	class SIB_Forms_Lang {

		/**
		 * Tab table name
		 */
		const TABLE_NAME = 'sib_model_lang';

		/** Create Table */
		public static function createTable() {
			global $wpdb;
			// create list table.
			$creation_query =
				'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . self::TABLE_NAME . ' (
                `id` int(20) NOT NULL AUTO_INCREMENT,
                `frmID` int(20) NOT NULL DEFAULT -1,
                `pID` int(20) NOT NULL DEFAULT -1,
                `lang` varchar(120),
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
		 * Get form ID by pid and language.
		 *
		 * @param int    $pID - parent form ID.
		 * @param string $lang - language.
		 * @return null
		 */
		public static function get_form_ID( $pID, $lang ) {
			global $wpdb;
			$query = "SHOW TABLES LIKE '" . $wpdb->prefix . self::TABLE_NAME . "'; ";
			if ( $wpdb->get_var( $query ) == $wpdb->prefix . self::TABLE_NAME ) {
				$sql = 'SELECT * FROM ' . $wpdb->prefix . self::TABLE_NAME . " WHERE pID ='" . $pID . "' AND lang='" . $lang . "'";
				$results = $wpdb->get_row( $sql ); // db call ok; no-cache ok.
				if ( ! empty( $results ) ) {
					return $results->frmID;
				} else {
					return null;
				}
			} else {
				return null;
			}
		}

		/**
		 * Get form language by form id and parent id.
		 *
		 * @param int $frmID - form ID.
		 * @param int $pID - parent form ID.
		 * @return null
		 */
		public static function get_lang( $frmID, $pID ) {
			global $wpdb;
			$query = "SHOW TABLES LIKE '" . $wpdb->prefix . self::TABLE_NAME . "'; ";
			if ( $wpdb->get_var( $query ) == $wpdb->prefix . self::TABLE_NAME ) {
				$sql = 'SELECT * FROM ' . $wpdb->prefix . self::TABLE_NAME . " WHERE frmID ='" . $frmID . "' AND pID='" . $pID . "'";
				$results = $wpdb->get_row( $sql ); // db call ok; no-cache ok.
				if ( ! empty( $results ) ) {
					return $results->lang;
				} else {
					return null;
				}
			} else {
				return null;
			}
		}

		/**
		 *  Add form
		 *
		 * @param int    $frmID - form ID.
		 * @param int    $pid - parent form ID.
		 * @param string $lang - language.
		 * @return null|string
		 */
		public static function add_form_ID( $frmID, $pid, $lang ) {
			// insert.
			global $wpdb;
			$query = 'INSERT INTO ' . $wpdb->prefix . self::TABLE_NAME . ' ';
			$query .= '(frmID,pID,lang) ';
			$query .= "VALUES ('{$frmID}','{$pid}','{$lang}')";
			$wpdb->query( $query ); // db call ok; no-cache ok.
			$index = $wpdb->get_var( 'SELECT LAST_INSERT_ID();' ); // db call ok; no-cache ok.
			return $index;
		}

		/**
		 * Check if origin form or translated form
		 *
		 * @param int $frmID - form ID.
		 * @return bool
		 */
		public static function check_form_trans( $frmID ) {
			global $wpdb;
			$query = "SHOW TABLES LIKE '" . $wpdb->prefix . self::TABLE_NAME . "'; ";
			if ( $wpdb->get_var( $query ) == $wpdb->prefix . self::TABLE_NAME ) {
				$sql = 'SELECT * FROM ' . $wpdb->prefix . self::TABLE_NAME . " WHERE frmID ='" . $frmID . "'";
				$results = $wpdb->get_row( $sql ); // db call ok; no-cache ok.
				if ( ! empty( $results ) ) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}

		}

		/**
		 * Remove forms
		 *
		 * @param int $pID - parent form ID.
		 */
		public static function remove_trans( $pID ) {
			global $wpdb;
			$query = "SHOW TABLES LIKE '" . $wpdb->prefix . self::TABLE_NAME . "'; ";
			if ( $wpdb->get_var( $query ) == $wpdb->prefix . self::TABLE_NAME ) {
				$query_forms = 'SELECT * FROM ' . $wpdb->prefix . self::TABLE_NAME . " WHERE pID='" . $pID . "'";
				$trans = $wpdb->get_results( $query_forms ); // db call ok; no-cache ok.
				if ( $trans ) {
					foreach ( $trans as $tran ) {
						SIB_Forms::deleteForm( $tran->frmID );
					}
				}
				$wpdb->delete(
					$wpdb->prefix . self::TABLE_NAME,
					array(
						'pID' => $pID,
					)
				);
			}

		}

		/**
		 * Remove all translated forms
		 */
		public static function remove_all_trans() {
			global $wpdb;
			$query = "SHOW TABLES LIKE '" . $wpdb->prefix . self::TABLE_NAME . "'; ";
			if ( $wpdb->get_var( $query ) == $wpdb->prefix . self::TABLE_NAME ) {
				$wpdb->query( 'TRUNCATE TABLE ' . $wpdb->prefix . self::TABLE_NAME );
			}
		}
	}
}

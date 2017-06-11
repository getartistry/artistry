<?php
/**
 * Model class <i>SIB_Model_Users</i> represents account
 * @package SIB_Model
 */

class SIB_Model_Users
{
    /**
     * Tab table name
     */
    const table_name = 'sib_model_users';

    /**
     * Holds found campaign count
     */
    static $found_count;

    /**
     * Holds all campaign count
     */
    static $all_count;

    /** Create Table */
    public static function createTable()
    {
        global $wpdb;
        // create list table
        $creation_query =
            'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix.self::table_name . ' (
			`id` int(20) NOT NULL AUTO_INCREMENT,
			`email` varchar(255),
            `code` varchar(100),
            `listIDs` longtext,
            `redirectUrl` varchar(255),
            `info` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
            `frmid` int(2),
			PRIMARY KEY (`id`)
			);';
        //$wpdb->query( $creation_query );
        require_once ( ABSPATH. 'wp-admin/includes/upgrade.php');
        dbDelta($creation_query);
    }

    /**
     * Remove table
     */
    public static function removeTable()
    {
        global $wpdb;
        $query = 'DROP TABLE IF EXISTS ' . $wpdb->prefix.self::table_name . ';';
        $wpdb->query($query);
    }

    /**
     * Get data by id
     * @param $id
     */
    public static function get_data($id)
    {
        global $wpdb;
        $query = 'select * from ' . $wpdb->prefix.self::table_name . ' where id=' . $id . ';';
        $results = $wpdb->get_results($query, ARRAY_A);

        if(is_array($results))
            return $results[0];
        else
            return false;
    }

    /**
     * Get data by code
     */
    public static function get_data_by_code($code)
    {
        global $wpdb;
        $query = 'select * from ' . $wpdb->prefix.self::table_name . ' where code like "' . $code . '";';
        $results = $wpdb->get_row($query,ARRAY_A);

        if(is_array($results) && count($results) > 0)
            return $results;
        else
            return false;
    }

    /**
     * Get code by email
     */
    public static function get_data_by_email($email,$formID)
    {
        global $wpdb;
        $query = 'select * from ' . $wpdb->prefix.self::table_name . ' where email = "' . $email . '" and frmid = "' . $formID . '";';
        $results = $wpdb->get_row($query,ARRAY_A);

        if(is_array($results) && count($results)>0)
            return $results;
        else
            return false;
    }

    /** Add record */
    public static function add_record($data)
    {
        global $wpdb;

        //if(self::is_exist_same_email($data['email']) == true) {
            //return false;
        //}

        $query = 'INSERT INTO ' .  $wpdb->prefix.self::table_name  . ' ';
        $query .= '(email,code,info,frmid,listIDs,redirectUrl) ';
        $query .= "VALUES ('{$data['email']}','{$data['code']}','{$data['info']}','{$data['frmid']}','{$data['listIDs']}','{$data['redirectUrl']}');";

        $wpdb->query( $query );

        $index = $wpdb->get_var('SELECT LAST_INSERT_ID();');

        return $index;

    }

    public static function is_exist_same_email($email, $id='')
    {
        global $wpdb;

        $query = 'select * from ' . $wpdb->prefix.self::table_name . ' ';
        $query .= "where email like '" . $email . "' ;";

        $results = $wpdb->get_results($query, ARRAY_A);

        if(is_array($results) && (count($results) > 0)) {
            if($id == '')
                return true;
            if (isset($results) && is_array($results)) {
                foreach($results as $result)
                {
                    if($result['id'] != $id) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /** Remove guest */
    public static function remove_record($id)
    {
        global $wpdb;

        $query = 'delete from ' . $wpdb->prefix.self::table_name . ' ';
        $query .= 'where id=' . $id . ';';

        $wpdb->query($query);
    }

    /** Get all guests by pagenum, per_page*/
    public static function get_all($orderby='email', $order='asc', $pagenum=1, $per_page=15)
    {
        global $wpdb;

        $limit = ($pagenum - 1) * $per_page;
        $query = 'SELECT * FROM ' . $wpdb->prefix.self::table_name . ' ';
        $query .= 'ORDER BY ' . $orderby . ' ' . $order . ' ';
        $query .= 'LIMIT ' . $limit . ',' . $per_page . ';';

        $results = $wpdb->get_results($query, ARRAY_A);
        self::$found_count =  self::get_count_element();

        if(!is_array($results)) {
            $results = array();
            return $results;
        }

        return $results;
    }

    /** get all records of table */
    public static function get_all_records()
    {
        global $wpdb;

        $query = 'select * from ' . $wpdb->prefix.self::table_name . ' order by email asc;';

        $results = $wpdb->get_results($query, ARRAY_A);

        if(!is_array($results)) {
            $results = array();
            return $results;
        }

        return $results;
    }

    /** get count of row */
    public static function get_count_element()
    {
        global $wpdb;

        $query = 'Select count(*) from ' . $wpdb->prefix.self::table_name . ';';

        $count = $wpdb->get_var($query);

        return $count;
    }

    /** update record */
    public static function update_element($id, $data)
    {
        global $wpdb;

        if(self::is_exist_same_email($data['email'], $id) == true) {
            return false;
        }

        $query = "update " . $wpdb->prefix.self::table_name . " ";
        $query .= "set email='{$data['email']}',info='{$data['info']}',code='{$data['code']}',is_activate='{$data['is_activate']}',extra='{$data['extra']}' ";
        $query .= "where id=" . $id . ";";

        $wpdb->query($query);

        return true;
    }

    /** add prefix to the table */
    public static function add_prefix()
    {
        global $wpdb;
        if($wpdb->get_var("SHOW TABLES LIKE '".self::table_name."'") == self::table_name) {
            $query = "ALTER TABLE ".self::table_name." RENAME TO ".$wpdb->prefix.self::table_name.";";
            $wpdb->query($query);
        }
    }

}

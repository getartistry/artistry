<?php
if (!defined('DUPLICATOR_PRO_VERSION')) exit; // Exit if accessed directly

/**
 * Lightweight abstraction layer for common simple database routines
 *
 * Standard: PSR-2
 *
 * @package SC\DupPro\DB
 *
 */

class DUP_PRO_DB extends wpdb
{

    /**
     * Get the requested MySQL system variable
     *
     * @param string $variable The database variable name to lookup
     *
     * @return string the server variable to query for
     */
    public static function getVariable($variable)
    {
        global $wpdb;
        $row = $wpdb->get_row("SHOW VARIABLES LIKE '{$variable}'", ARRAY_N);
        return isset($row[1]) ? $row[1] : null;
    }

    /**
     * Gets the MySQL database version number
     *
     * @param bool $full    True:  Gets the full version if available (i.e 10.2.3-MariaDB)
     *                      False: Gets only the numeric portion i.e. (5.5.6 -or- 10.1.2)
     *
     * @return false|string 0 on failure, version number on success
     */
    public static function getVersion($full = false)
    {
        if ($full) {
            $version = self::getVariable('version');
        } else {
            $version = preg_replace('/[^0-9.].*/', '', self::getVariable('version'));
        }

        return empty($version) ? 0 : $version;
    }


        /**
     * Returns the mysqldump path if the server is enabled to execute it
     * @return boolean|string
     */
    public static function getMySqlDumpPath()
    {
        $global = DUP_PRO_Global_Entity::get_instance();

        //Is shell_exec possible
        if (!DUP_PRO_Shell_U::isShellExecEnabled()) {
            return false;
        }

        $custom_mysqldump_path = (strlen($global->package_mysqldump_path)) ? $global->package_mysqldump_path : '';

        //Common Windows Paths
        if (DUP_PRO_U::isWindows()) {
            $paths = array(
                $custom_mysqldump_path,
                'C:/xampp/mysql/bin/mysqldump.exe',
                'C:/Program Files/xampp/mysql/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 6.0/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.5/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.4/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.1/bin/mysqldump',
                'C:/Program Files/MySQL/MySQL Server 5.0/bin/mysqldump',
            );
        }
        //Common Linux Paths
        else {
            $path1     = '';
            $path2     = '';
            $mysqldump = `which mysqldump`;
            if (@is_executable($mysqldump)) $path1     = (!empty($mysqldump)) ? $mysqldump : '';

            $mysqldump = dirname(`which mysql`)."/mysqldump";
            if (@is_executable($mysqldump)) $path2     = (!empty($mysqldump)) ? $mysqldump : '';

            $paths = array(
                $custom_mysqldump_path,
                $path1,
                $path2,
                '/usr/local/bin/mysqldump',
                '/usr/local/mysql/bin/mysqldump',
                '/usr/mysql/bin/mysqldump',
                '/usr/bin/mysqldump',
                '/opt/local/lib/mysql6/bin/mysqldump',
                '/opt/local/lib/mysql5/bin/mysqldump',
                '/opt/local/lib/mysql4/bin/mysqldump',
            );
        }

        // Find the one which works
        foreach ($paths as $path) {
            if (@is_executable($path)) return $path;
        }

        return false;
    }

    
}
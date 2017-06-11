<?php
if (!defined('DUPLICATOR_PRO_VERSION')) exit; // Exit if accessed directly

require_once DUPLICATOR_PRO_PLUGIN_PATH.'/classes/entities/class.global.entity.php';

class DUP_PRO_Database
{
    //PUBLIC
    public $Type = 'MySQL';
    public $Size;
    public $File;
    public $Path;
    public $FilterTables;
    public $FilterOn;
    public $Name;
    public $DBMode;
    public $Compatible;
    public $Comments;
    //PROTECTED
    protected $Package;
    //PRIVATE
    private $dbStorePath;
    private $EOFMarker;

    //CONSTRUCTOR
    function __construct($package)
    {
        $global = DUP_PRO_Global_Entity::get_instance();

        $this->Package   = $package;
        $this->EOFMarker = '';
        $this->Comments  = '';
    }

    public function get_safe_filepath()
    {
        return DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$this->File}");
    }

    public function build($package)//, $sql_build_progress)
    {
        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("Building database");
        try {
            $global = DUP_PRO_Global_Entity::get_instance();

            $this->Package = $package;

            $time_start = DUP_PRO_U::getMicrotime();
            $this->Package->set_status(DUP_PRO_PackageStatus::DBSTART);

            $this->dbStorePath = "{$this->Package->StorePath}/{$this->File}";
            $mysqlDumpPath     = DUP_PRO_DB::getMySqlDumpPath();
            $mode              = ($mysqlDumpPath && $global->package_mysqldump) ? 'MYSQLDUMP' : 'PHP';

            if (($mysqlDumpPath === false) && ($global->package_mysqldump)) {
                DUP_PRO_LOG::trace("Forcing into PHP mode - the mysqldump executable wasn't found!");
            }

            $mysqlDumpSupport = ($mysqlDumpPath) ? 'Is Supported' : 'Not Supported';

            $log = "\n********************************************************************************\n";
            $log .= "DATABASE:\n";
            $log .= "********************************************************************************\n";
            $log .= "BUILD MODE:   {$mode} ";

            if (($mode == 'MYSQLDUMP') && strlen($this->Compatible)) {
                $log.= " (Legacy SQL)";
            }

            $log .= ($mode == 'PHP') ? "(query limit - {$global->package_phpdump_qrylimit})\n" : "\n";
            $log .= "MYSQLDUMP:    {$mysqlDumpSupport}\n";
            $log .= "MYSQLTIMEOUT: ".DUPLICATOR_PRO_DB_MAX_TIME;
            DUP_PRO_Log::info($log);
            $log = null;


            switch ($mode) {
                case 'MYSQLDUMP': $this->mysql_dump($mysqlDumpPath);
                    break;
                case 'PHP' : $this->php_dump();
                    break;
            }

            DUP_PRO_Log::info("SQL CREATED: {$this->File}");
            $time_end = DUP_PRO_U::getMicrotime();
            $time_sum = DUP_PRO_U::elapsedTime($time_end, $time_start);

            $sql_file_size = filesize($this->dbStorePath);
            if ($sql_file_size <= 0) {
                DUP_PRO_Log::error("SQL file generated zero bytes.", "No data was written to the sql file.  Check permission on file and parent directory at [{$this->dbStorePath}]");
            }
            DUP_PRO_Log::info("SQL FILE SIZE: ".DUP_PRO_U::byteSize($sql_file_size));
            DUP_PRO_Log::info("SQL FILE TIME: ".date("Y-m-d H:i:s"));
            DUP_PRO_Log::info("SQL RUNTIME: {$time_sum}");
            DUP_PRO_Log::info("MEMORY STACK: ".DUP_PRO_Server::getPHPMemory());

            $this->Size = @filesize($this->dbStorePath);
            $this->Package->set_status(DUP_PRO_PackageStatus::DBDONE);
        } catch (Exception $e) {
            DUP_PRO_Log::error("Runtime error in DUP_PRO_Database::Build", "Exception: {$e}");
        }

        DUP_PRO_LOG::trace("Done building database");
    }

    /**
     *  Get the database stats
     */
    public function get_scan_data()
    {

        global $wpdb;
        $filterTables = isset($this->FilterTables) ? explode(',', $this->FilterTables) : null;
        $tblCount     = 0;

        $tables                    = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);
        $info                      = array();
        $info['Status']['Success'] = is_null($tables) ? false : true;
        $info['Status']['Size']    = 'Good';
        $info['Status']['Rows']    = 'Good';

        $info['Size']       = 0;
        $info['Rows']       = 0;
        $info['TableCount'] = 0;
        $info['TableList']  = array();

        //Only return what we really need
        foreach ($tables as $table) {

            $name = $table["Name"];
            if ($this->FilterOn && is_array($filterTables)) {
                if (in_array($name, $filterTables)) {
                    continue;
                }
            }
            $size = ($table["Data_length"] + $table["Index_length"]);

            $info['Size'] += $size;
            $info['Rows'] += ($table["Rows"]);
            $info['TableList'][$name]['Rows'] = empty($table["Rows"]) ? '0' : number_format($table["Rows"]);
            $info['TableList'][$name]['Size'] = DUP_PRO_U::byteSize($size);
            $tblCount++;
        }

        $info['Status']['Size'] = ($info['Size'] > 100000000) ? 'Warn' : 'Good';
        $info['Status']['Rows'] = ($info['Rows'] > 1000000) ? 'Warn' : 'Good';
        $info['TableCount']     = $tblCount;

        return $info;
    }



    private function mysql_dump($exePath)
    {

        global $wpdb;

        $host           = explode(':', DB_HOST);
        $host           = reset($host);
        $port           = strpos(DB_HOST, ':') ? end(explode(':', DB_HOST)) : '';
        $name           = DB_NAME;
        $mysqlcompat_on = isset($this->Compatible) && strlen($this->Compatible);

        //Build command
        $cmd = escapeshellarg($exePath);
        $cmd .= ' --no-create-db';
        $cmd .= ' --single-transaction';
        $cmd .= ' --hex-blob';
        $cmd .= ' --skip-add-drop-table';

        //Compatibility mode
        if ($mysqlcompat_on) {
            DUP_PRO_Log::info("COMPATIBLE: [{$this->Compatible}]");
            $cmd .= " --compatible={$this->Compatible}";
        }

        //Filter tables
        $tables       = $wpdb->get_col('SHOW TABLES');
        $filterTables = isset($this->FilterTables) ? explode(',', $this->FilterTables) : null;
        $tblAllCount  = count($tables);
        $tblFilterOn  = ($this->FilterOn) ? 'ON' : 'OFF';

        if (is_array($filterTables) && $this->FilterOn) {
            foreach ($tables as $key => $val) {
                if (in_array($tables[$key], $filterTables)) {
                    $cmd .= " --ignore-table={$name}.{$tables[$key]} ";
                    unset($tables[$key]);
                }
            }
        }
        $tblCreateCount = count($tables);
        $tblFilterCount = $tblAllCount - $tblCreateCount;

        $cmd .= ' -u '.escapeshellarg(DB_USER);
        $cmd .= (DB_PASSWORD) ?
            ' -p'.DUP_PRO_Shell_U::escapeshellargWindowsSupport(DB_PASSWORD) : '';
        $cmd .= ' -h '.escapeshellarg($host);
        $cmd .= (!empty($port) && is_numeric($port) ) ?
            ' -P '.$port : '';
        $cmd .= ' -r '.escapeshellarg($this->dbStorePath);
        $cmd .= ' '.escapeshellarg(DB_NAME);
        $cmd .= ' 2>&1';

        DUP_PRO_LOG::trace("Executing mysql dump command $cmd");
        $output = shell_exec($cmd);

        // Password bug > 5.6 (@see http://bugs.mysql.com/bug.php?id=66546)
        if (trim($output) === 'Warning: Using a password on the command line interface can be insecure.') {
            $output = '';
        }
        $output = (strlen($output)) ? $output : "Ran from {$exePath}";

        DUP_PRO_Log::info("TABLES: total:{$tblAllCount} | filtered:{$tblFilterCount} | create:{$tblCreateCount}");
        DUP_PRO_Log::info("FILTERED: [{$this->FilterTables}]");
        DUP_PRO_Log::info("RESPONSE: {$output}");

        $sql_footer = "\n\n/* Duplicator WordPress Timestamp: ".date("Y-m-d H:i:s")."*/\n";
        $sql_footer .= "/* ".DUPLICATOR_PRO_DB_EOF_MARKER." */\n";
        file_put_contents($this->dbStorePath, $sql_footer, FILE_APPEND);

        return ($output) ? false : true;
    }

    private function php_dump()
    {

        global $wpdb;

        $global = DUP_PRO_Global_Entity::get_instance();

        $wpdb->query("SET session wait_timeout = ".DUPLICATOR_PRO_DB_MAX_TIME);
        $handle = fopen($this->dbStorePath, 'w+');
        $tables = $wpdb->get_col('SHOW TABLES');

        $filterTables = isset($this->FilterTables) ? explode(',', $this->FilterTables) : null;
        $tblAllCount  = count($tables);
        $tblFilterOn  = ($this->FilterOn) ? 'ON' : 'OFF';

        if (is_array($filterTables) && $this->FilterOn) {
            foreach ($tables as $key => $val) {
                if (in_array($tables[$key], $filterTables)) {
                    unset($tables[$key]);
                }
            }
        }
        $tblCreateCount = count($tables);
        $tblFilterCount = $tblAllCount - $tblCreateCount;

        DUP_PRO_Log::info("TABLES: total:{$tblAllCount} | filtered:{$tblFilterCount} | create:{$tblCreateCount}");
        DUP_PRO_Log::info("FILTERED: [{$this->FilterTables}]");

        $sql_header = "/* DUPLICATOR MYSQL SCRIPT CREATED ON : ".@date("Y-m-d H:i:s")." */\n\n";
        $sql_header .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        fwrite($handle, $sql_header);

        //BUILD CREATES:
        //All creates must be created before inserts do to foreign key constraints
        foreach ($tables as $table) {
            $create = $wpdb->get_row("SHOW CREATE TABLE `{$table}`", ARRAY_N);
            @fwrite($handle, "{$create[1]};\n\n");
        }

        //BUILD INSERTS: 
        //Create Insert in 100 row increments to better handle memory
        foreach ($tables as $table) {

            $row_count = $wpdb->get_var("SELECT Count(*) FROM `{$table}`");
            //DUP_PRO_Log::info("{$table} ({$row_count})");

            if ($row_count > $global->package_phpdump_qrylimit) {
                $row_count = ceil($row_count / $global->package_phpdump_qrylimit);
            } else if ($row_count > 0) {
                $row_count = 1;
            }

            if ($row_count >= 1) {
                fwrite($handle, "\n/* INSERT TABLE DATA: {$table} */\n");
            }

            for ($i = 0; $i < $row_count; $i++) {
                $sql   = "";
                $limit = $i * $global->package_phpdump_qrylimit;
                $query = "SELECT * FROM `{$table}` LIMIT {$limit}, {$global->package_phpdump_qrylimit}";
                $rows  = $wpdb->get_results($query, ARRAY_A);
                if (is_array($rows)) {
                    foreach ($rows as $row) {
                        $sql .= "INSERT INTO `{$table}` VALUES(";
                        $num_values  = count($row);
                        $num_counter = 1;
                        foreach ($row as $value) {
                            if (is_null($value) || !isset($value)) {
                                ($num_values == $num_counter) ? $sql .= 'NULL' : $sql .= 'NULL, ';
                            } else {
                                ($num_values == $num_counter) ? $sql .= '"'.@esc_sql($value).'"' : $sql .= '"'.@esc_sql($value).'", ';
                            }
                            $num_counter++;
                        }
                        $sql .= ");\n";
                    }
                    fwrite($handle, $sql);
                }
            }

            $sql  = null;
            $rows = null;
        }

        $sql_footer = "\nSET FOREIGN_KEY_CHECKS = 1; \n\n";
        $sql_footer .= "/* Duplicator WordPress Timestamp: ".date("Y-m-d H:i:s")."*/\n";
        $sql_footer .= "/* ".DUPLICATOR_PRO_DB_EOF_MARKER." */\n";
        fwrite($handle, $sql_footer);
        $wpdb->flush();
        fclose($handle);
    }
}
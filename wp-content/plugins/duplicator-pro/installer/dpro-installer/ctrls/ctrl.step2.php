<?php
//-- START OF ACTION STEP 2
require_once($GLOBALS['DUPX_INIT'] . '/api/class.cpnl.ctrl.php');

//BASIC
if ($_POST['view_mode'] == 'basic') {
	$_POST['dbaction']	 = isset($_POST['dbaction']) ? $_POST['dbaction'] : 'create';
	$_POST['dbhost']	 = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : null;
	$_POST['dbname']	 = isset($_POST['dbname']) ? trim($_POST['dbname']) : null;
	$_POST['dbuser']	 = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : null;
	$_POST['dbpass']	 = isset($_POST['dbpass']) ? trim($_POST['dbpass']) : null;
	$_POST['dbport']	 = isset($_POST['dbhost']) ? parse_url($_POST['dbhost'], PHP_URL_PORT) : 3306;
	$_POST['dbport']	 = (!empty($_POST['dbport'])) ? $_POST['dbport'] : 3306;
}
//CPANEL
else {
	$_POST['dbaction']	 = isset($_POST['cpnl-dbaction']) ? $_POST['cpnl-dbaction'] : 'create';
	$_POST['dbhost']	 = isset($_POST['cpnl-dbhost']) ? trim($_POST['cpnl-dbhost']) : null;
	$_POST['dbname']	 = isset($_POST['cpnl-dbname-result']) ? trim($_POST['cpnl-dbname-result']) : null;
	$_POST['dbuser']	 = isset($_POST['cpnl-dbuser-result']) ? trim($_POST['cpnl-dbuser-result']) : null;
	$_POST['dbpass']	 = isset($_POST['cpnl-dbpass']) ? trim($_POST['cpnl-dbpass']) : null;
	$_POST['dbport']	 = isset($_POST['cpnl-dbhost']) ? parse_url($_POST['cpnl-dbhost'], PHP_URL_PORT) : 3306;
	$_POST['dbport']	 = (!empty($_POST['cpnl-dbport'])) ? $_POST['cpnl-dbport'] : 3306;
	$cpnl_dbcreateuser	 = isset($_POST['cpnl-dbuser-chk']) ? true : false;
}

$_POST['dbnbsp']	= (isset($_POST['dbnbsp']) && $_POST['dbnbsp'] == '1') ? true : false;
$_POST['dbcharset']	= isset($_POST['dbcharset']) ? trim($_POST['dbcharset']) : $GLOBALS['DBCHARSET_DEFAULT'];
$_POST['dbcollate']	= isset($_POST['dbcollate']) ? trim($_POST['dbcollate']) : $GLOBALS['DBCOLLATE_DEFAULT'];

$ajax2_start	 = DUPX_U::getMicrotime();
$root_path		 = $GLOBALS['DUPX_ROOT'];
$JSON			 = array();
$JSON['pass']	 = 0;

/**
JSON RESPONSE: Most sites have warnings turned off by default, but if they're turned on the warnings
cause errors in the JSON data Here we hide the status so warning level is reset at it at the end */
$ajax2_error_level = error_reporting();
error_reporting(E_ERROR);
($GLOBALS['LOG_FILE_HANDLE'] != false) or DUPX_Log::error(ERR_MAKELOG);

//===============================================
//DATABASE TEST: From Postback
//===============================================
if (isset($_GET['dbtest'])) {
	require_once($GLOBALS['DUPX_INIT'] . '/ctrls/inc.db-test.php');
}

//===============================================
//CPANEL LOGIC: From Postback
//===============================================
$cpnllog = "";
if ($_POST['view_mode'] == 'cpnl') {
	try {
		$json['message'] = '';
		$json['status']	 = false;

		$CPNL		 = new DUPX_cPanel_Controller();
		$cpnlToken	 = $CPNL->create_token($_POST['cpnl-host'], $_POST['cpnl-user'], $_POST['cpnl-pass']);
		$cpnlHost	 = $CPNL->connect($cpnlToken);
		$cpnllog	 = "\nCPANEL API:\tUsed to connect to existing database\n";

		//CREATE DB USER: Attempt to create user should happen first in the case that the
		//user passwords requirements are not met.
		if ($cpnl_dbcreateuser) {
			$result = $CPNL->create_db_user($cpnlToken, $_POST['dbuser'], $_POST['dbpass']);
			if ($result['status'] !== true) {
				DUPX_Log::info('CPANEL API: create_db_user ' . print_r($result['cpnl_api'], true), 2);
				DUPX_Log::error(sprintf(ERR_CPNL_API, $result['status']));
			} else {
				$cpnllog .= "CPANEL API:\tA new database user was created\n";
			}
		}

		//CREATE NEW DB
		if ($_POST['dbaction'] == 'create') {
			$result = $CPNL->create_db($cpnlToken, $_POST['dbname']);
			if ($result['status'] !== true) {
				DUPX_Log::info('CPANEL API: create_db '.print_r($result['cpnl_api'], true), 2);
				DUPX_Log::error(sprintf(ERR_CPNL_API, $result['status']));
			} else {
				$cpnllog .= "CPANEL API:\tA new database was created\n";
			}
		}

		//ASSIGN USER TO DB IF NOT ASSIGNED
		$result = $CPNL->is_user_in_db($cpnlToken, $_POST['dbname'], $_POST['dbuser']);
		if (!$result['status']) {
			$permissions = 'ALL';
			$result		 = $CPNL->assign_db_user($cpnlToken, $_POST['dbname'], $_POST['dbuser']);
			if ($result['status'] !== true) {
				DUPX_Log::info('CPANEL API: assign_db_user '.print_r($result['cpnl_api'], true), 2);
				DUPX_Log::error(sprintf(ERR_CPNL_API, $result['status']));
			} else {
				$cpnllog .= "CPANEL API:\tDatabase user was assigned to database";
			}
		}
	} catch (Exception $ex) {
		DUPX_Log::error($ex);
	}
}


//===============================================
//DB ERROR MESSAGES
//===============================================
$dbh = DUPX_DB::connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass']);
($dbh) or DUPX_Log::error(ERR_DBCONNECT.mysqli_connect_error());
if ($_POST['dbaction'] == 'empty' || $_POST['dbaction'] == 'rename') {
	mysqli_select_db($dbh, $_POST['dbname'])
		or DUPX_Log::error(sprintf(ERR_DBCREATE, $_POST['dbname']));
}
//ERROR: Create new database, existing tables found.
if ($_POST['dbaction'] == 'create') {
	$tblcount = DUPX_DB::countTables($dbh, $_POST['dbname']);
	if ($tblcount > 0) {
		DUPX_Log::error(sprintf(ERR_DBEMPTY, $_POST['dbname'], $tblcount));
	}
}

//ERROR: Manual Mode - Core WP has 12 tables. Check to make
//sure at least 10 are present otherwise present an error message
if ($_POST['dbaction'] == 'manual') {
	$tblcount = DUPX_DB::countTables($dbh, $_POST['dbname']);
	if ($tblcount < 10) {
		DUPX_Log::error(sprintf(ERR_DBMANUAL, $_POST['dbname'], $tblcount));
	}
}

DUPX_Log::info("\n\n\n********************************************************************************");
DUPX_Log::info('* DUPLICATOR PRO INSTALL-LOG');
DUPX_Log::info('* STEP-2 START @ '.@date('h:i:s'));
DUPX_Log::info('* NOTICE: Do NOT post to public sites or forums!!');
DUPX_Log::info("********************************************************************************");
if (! empty($cpnllog)) {
	DUPX_Log::info($cpnllog);
}

$POST_LOG = $_POST;
unset($POST_LOG['dbpass']);
ksort($POST_LOG);
$log = "--------------------------------------\n";
$log .= "POST DATA\n";
$log .= "--------------------------------------\n";
$log .= print_r($POST_LOG, true);
DUPX_Log::info($log, 2);


//===============================================
//DATABASE ROUTINES
//===============================================
if ($_POST['dbaction'] != 'manual') {
	$faq_url		 = $GLOBALS['FAQ_URL'];
	$db_file_size	 = @filesize('database.sql');
	$php_mem		 = $GLOBALS['PHP_MEMORY_LIMIT'];
	$php_mem_range	 = DUPX_U::returnBytes($GLOBALS['PHP_MEMORY_LIMIT']);
	$php_mem_range	 = $php_mem_range == null ? 0 : $php_mem_range - 5000000; //5 MB Buffer
	
	//Fatal Memory errors from file_get_contents is not catchable.
	//Try to warn ahead of time with a buffer in memory difference
	if ($db_file_size >= $php_mem_range && $php_mem_range != 0) {
		$db_file_size	 = DUPX_U::readableByteSize($db_file_size);
		$msg  = "\nWARNING: The database script is '{$db_file_size}' in size.  The PHP memory allocation is set\n";
		$msg .= "at '{$php_mem}'.  There is a high possibility that the installer script will fail with\n";
		$msg .= "a memory allocation error when trying to load the database.sql file.  It is\n";
		$msg .= "recommended to increase the 'memory_limit' setting in the php.ini config file.\n";
		$msg .= "see: {$faq_url}#faq-trouble-056-q \n";
		DUPX_Log::info($msg);
	}

	@chmod("{$root_path}/database.sql", 0777);
	$sql_file = file_get_contents("{$root_path}/database.sql", true);

	//ERROR: Reading database.sql file
	if ($sql_file === FALSE || strlen($sql_file) < 10) {
		$spacer = str_repeat("&nbsp;", 5);
$msg = <<<EOT
<b>Unable to read/find the database.sql file from the archive.</b><br/>
Please check these items: <br/><br/>
1. Validate permissions and/or group-owner rights on these items: <br/>
{$spacer}- File: database.sql <br/>
{$spacer}- Directory: [{$root_path}] <br/>
{$spacer}<small>See: <a href='{$faq_url}#faq-trouble-055-q' target='_blank'>{$faq_url}#faq-trouble-055-q</a></small><br/><br/>
2. Validate the database.sql file exists and is in the root of the archive.zip file <br/>
{$spacer}<small>See: <a href='{$faq_url}#faq-installer-020-q' target='_blank'>{$faq_url}#faq-installer-020-q</a></small><br/><br/>
EOT;
		DUPX_Log::error($msg);
	}

	//Removes invalid space characters
	//Complex Subject See: http://webcollab.sourceforge.net/unicode.html
	if ($_POST['dbnbsp']) {
		DUPX_Log::info("ran fix non-breaking space characters\n");
		$sql_file = preg_replace('/\xC2\xA0/', ' ', $sql_file);
	}

	//Write new contents to install-data.sql
	$sql_result_file_path	 = "{$root_path}/{$GLOBALS['SQL_FILE_NAME']}";
	@chmod($sql_result_file_path, 0777);
	$sql_file_copy_status	 = file_put_contents($sql_result_file_path, $sql_file);
	$sql_result_file_data	 = explode(";\n", $sql_file);
	$sql_result_file_length	 = count($sql_result_file_data);
	$sql_file = null;

	//WARNING: Create installer-data.sql failed
	if ($sql_file_copy_status === FALSE || filesize($sql_result_file_path) == 0 || !is_readable($sql_result_file_path)) {
		$sql_file_size	 = DUPX_U::readableByteSize(filesize('database.sql'));
		$msg  = "\nWARNING: Unable to properly copy database.sql ({$sql_file_size}) to {$GLOBALS['SQL_FILE_NAME']}.  Please check these items:\n";
		$msg .= "- Validate permissions and/or group-owner rights on database.sql and directory [{$root_path}] \n";
		$msg .= "- see: {$faq_url}#faq-trouble-055-q \n";
		DUPX_Log::info($msg);
	}


	//RUN DATABASE SCRIPT
	@mysqli_query($dbh, "SET wait_timeout = {$GLOBALS['DB_MAX_TIME']}");
	@mysqli_query($dbh, "SET max_allowed_packet = {$GLOBALS['DB_MAX_PACKETS']}");
	DUPX_DB::setCharset($dbh, $_POST['dbcharset'], $_POST['dbcollate']);

	//Will set mode to null only for this db handle session
	//sql_mode can cause db create issues on some systems
	switch ($_POST['dbmysqlmode']) {
		case 'DISABLE':
			@mysqli_query($dbh, "SET SESSION sql_mode = ''");
			break;
		case 'CUSTOM':
			$dbmysqlmode_opts	 = $_POST['dbmysqlmode_opts'];
			$qry_session_custom	 = @mysqli_query($dbh, "SET SESSION sql_mode = '{$dbmysqlmode_opts}'");
			if ($qry_session_custom == false) {
				$sql_error	 = mysqli_error($dbh);
				$log		 = "WARNING: A custom sql_mode setting issue has been detected:\n{$sql_error}.\n";
				$log .= "For more details visit: http://dev.mysql.com/doc/refman/5.7/en/sql-mode.html\n";
			}
			break;
	}


	//Set defaults incase the variable could not be read
	$dbvar_maxtime	 = DUPX_DB::getVariable($dbh, 'wait_timeout');
	$dbvar_maxpacks	 = DUPX_DB::getVariable($dbh, 'max_allowed_packet');
	$dbvar_sqlmode	 = DUPX_DB::getVariable($dbh, 'sql_mode');
	$dbvar_version	 = DUPX_DB::getVersion($dbh);
	$dbvar_maxtime	 = is_null($dbvar_maxtime) ? 300 : $dbvar_maxtime;
	$dbvar_maxpacks	 = is_null($dbvar_maxpacks) ? 1048576 : $dbvar_maxpacks;
	$dbvar_sqlmode	 = empty($dbvar_sqlmode) ? 'NOT_SET' : $dbvar_sqlmode;
	$drop_tbl_log	 = 0;
	$rename_tbl_log	 = 0;
	$sql_file_size1		= DUPX_U::readableByteSize(@filesize("{$root_path}/database.sql"));
	$sql_file_size2		= DUPX_U::readableByteSize(@filesize("{$root_path}/{$GLOBALS['SQL_FILE_NAME']}"));

	DUPX_Log::info("--------------------------------------");
	DUPX_Log::info('DATABASE-ENVIRONMENT');
	DUPX_Log::info("--------------------------------------");
	DUPX_Log::info("MYSQL VERSION:\tThis Server: {$dbvar_version} -- Build Server: {$GLOBALS['FW_VERSION_DB']}");
	DUPX_Log::info("FILE SIZE:\tdatabase.sql ({$sql_file_size1}) - installer-data.sql ({$sql_file_size2})");
	DUPX_Log::info("TIMEOUT:\t{$dbvar_maxtime}");
	DUPX_Log::info("MAXPACK:\t{$dbvar_maxpacks}");
	DUPX_Log::info("SQLMODE:\t{$dbvar_sqlmode}");
	DUPX_Log::info("NEW SQL FILE:\t[{$sql_result_file_path}]");

	if (version_compare($dbvar_version, $GLOBALS['FW_VERSION_DB']) < 0) {
		DUPX_Log::info("\nNOTICE: This servers version [{$dbvar_version}] is less than the build version [{$GLOBALS['FW_VERSION_DB']}].  \n"
		. "If you find issues after testing your site please referr to this FAQ item.\n"
		. "https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-260-q");
	}

	//CREATE DB
	switch ($_POST['dbaction']) {
		case "create":
			if ($_POST['view_mode'] == 'basic') {
				mysqli_query($dbh, "CREATE DATABASE IF NOT EXISTS `{$_POST['dbname']}`");
			}
			mysqli_select_db($dbh, $_POST['dbname'])
				or DUPX_Log::error(sprintf(ERR_DBCONNECT_CREATE, $_POST['dbname']));
			break;
		case "empty":
			//DROP DB TABLES:  DROP TABLE statement does not support views
			$sql			 = "SHOW FULL TABLES WHERE Table_Type != 'VIEW'";
			$found_tables	 = null;
			if ($result			 = mysqli_query($dbh, $sql)) {
				while ($row = mysqli_fetch_row($result)) {
					$found_tables[] = $row[0];
				}
				if (count($found_tables) > 0) {
					foreach ($found_tables as $table_name) {
						$sql	 = "DROP TABLE `{$_POST['dbname']}`.`{$table_name}`";
						if (!$result	 = mysqli_query($dbh, $sql)) {
							DUPX_Log::error(sprintf(ERR_DBTRYCLEAN, "{$_POST['dbname']}.{$table_name}")."<br/>ERROR MESSAGE:{$err}");
						}
					}
					$drop_tbl_log = count($found_tables);
				}
			}
			break;
		case "rename" :
			//RENAME DB TABLES
			$sql			 = "SHOW TABLES FROM `{$_POST['dbname']}` WHERE  `Tables_in_{$_POST['dbname']}` NOT LIKE '{$GLOBALS['DB_RENAME_PREFIX']}%'";
			$found_tables	 = null;
			if ($result	 = mysqli_query($dbh, $sql)) {
				while ($row = mysqli_fetch_row($result)) {
					$found_tables[] = $row[0];
				}
				if (count($found_tables) > 0) {
					foreach ($found_tables as $table_name) {
						$sql	 = "RENAME TABLE `{$_POST['dbname']}`.`{$table_name}` TO  `{$_POST['dbname']}`.`{$GLOBALS['DB_RENAME_PREFIX']}{$table_name}`";
						if (!$result	 = mysqli_query($dbh, $sql)) {
							DUPX_Log::error(sprintf(ERR_DBTRYRENAME, "{$_POST['dbname']}.{$table_name}"));
						}
					}
					$rename_tbl_log = count($found_tables);
				}
			}
			break;
	}
}


DUPX_Log::info("--------------------------------------");
DUPX_Log::info("DATABASE RESULTS");
DUPX_Log::info("--------------------------------------");

if ($_POST['dbaction'] == 'manual') {
	DUPX_Log::info("\n** SQL EXECUTION IS IN MANUAL MODE **");
	DUPX_Log::info("- No SQL script has been ran -");

	$JSON['table_count'] = 0;
	$JSON['table_rows']	 = 0;
	$JSON['query_errs']	 = 0;
} else {
	//WRITE DATA
	$profile_start		 = DUPX_U::getMicrotime();
	$fcgi_buffer_pool	 = 5000;
	$fcgi_buffer_count	 = 0;
	$dbquery_rows		 = 0;
	$dbtable_rows		 = 1;
	$dbquery_errs		 = 0;
	$counter			 = 0;
	@mysqli_autocommit($dbh, false);

	while ($counter < $sql_result_file_length) {

		$query_strlen = strlen(trim($sql_result_file_data[$counter]));
		if ($dbvar_maxpacks < $query_strlen) {
			DUPX_Log::info("**ERROR** Query size limit [length={$query_strlen}] [sql=".substr($sql_result_file_data[$counter], 75)."...]");
			$dbquery_errs++;
		} elseif ($query_strlen > 0) {
			@mysqli_free_result(@mysqli_query($dbh, ($sql_result_file_data[$counter])));
			$err = mysqli_error($dbh);
			//Check to make sure the connection is alive
			if (!empty($err)) {
				if (!mysqli_ping($dbh)) {
					mysqli_close($dbh);
					$dbh = DUPX_DB::connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], $_POST['dbname']);
					// Reset session setup
					@mysqli_query($dbh, "SET wait_timeout = {$GLOBALS['DB_MAX_TIME']}");
					DUPX_DB::setCharset($dbh, $_POST['dbcharset'], $_POST['dbcollate']);
				}
				DUPX_Log::info("**ERROR** database error write '{$err}' - [sql=".substr($sql_result_file_data[$counter], 0, 75)."...]");

				if (DUPX_U::contains($err, 'Unknown collation')) {
					DUPX_Log::info('RECOMMENDATION: Try resolutions found at https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-110-q');
				}

				$dbquery_errs++;

				//Buffer data to browser to keep connection open
			} else {
				if ($fcgi_buffer_count++ > $fcgi_buffer_pool) {
					$fcgi_buffer_count = 0;
				}
				$dbquery_rows++;
			}
		}
		$counter++;
	}
	@mysqli_commit($dbh);
	@mysqli_autocommit($dbh, true);

	DUPX_Log::info("ERRORS FOUND:\t{$dbquery_errs}");
	DUPX_Log::info("DROPPED TABLES:\t{$drop_tbl_log}");
	DUPX_Log::info("RENAMED TABLES:\t{$rename_tbl_log}");
	DUPX_Log::info("QUERIES RAN:\t{$dbquery_rows}\n");

	$dbtable_count	 = 0;
	if ($result = mysqli_query($dbh, "SHOW TABLES")) {
		while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
			$table_rows = DUPX_DB::countTableRows($dbh, $row[0]);
			$dbtable_rows += $table_rows;
			DUPX_Log::info("{$row[0]}: ({$table_rows})");
			$dbtable_count++;
		}
		@mysqli_free_result($result);
	}

	if ($dbtable_count == 0) {
		DUPX_Log::info("NOTICE: You may have to manually run the installer-data.sql to validate data input. Also check to make sure your installer file is correct and the
			table prefix '{$GLOBALS['FW_TABLEPREFIX']}' is correct for this particular version of WordPress. \n");
	}

	//DATA CLEANUP: Perform Transient Cache Cleanup
	//Remove all duplicator entries and record this one since this is a new install.
	$dbdelete_count	 = 0;
	$dbdelete_count1 = 0;
	$dbdelete_count2 = 0;

	@mysqli_query($dbh, "DELETE FROM `{$GLOBALS['FW_TABLEPREFIX']}duplicator_pro_packages`");
	$dbdelete_count1 = @mysqli_affected_rows($dbh);

	@mysqli_query($dbh, "DELETE FROM `{$GLOBALS['FW_TABLEPREFIX']}options` WHERE `option_name` LIKE ('_transient%') OR `option_name` LIKE ('_site_transient%')");
	$dbdelete_count2 = @mysqli_affected_rows($dbh);

	$dbdelete_count = (abs($dbdelete_count1) + abs($dbdelete_count2));

	DUPX_Log::info("Removed '{$dbdelete_count}' cache/transient rows");
	//Reset Duplicator Options
	foreach ($GLOBALS['FW_OPTS_DELETE'] as $value) {
		mysqli_query($dbh, "DELETE FROM `{$GLOBALS['FW_TABLEPREFIX']}options` WHERE `option_name` = '{$value}'");
	}

	@mysqli_close($dbh);

	$JSON['table_count'] = $dbtable_count;
	$JSON['table_rows']	 = $dbtable_rows;
	$JSON['query_errs']	 = $dbquery_errs;
	$profile_end		 = DUPX_U::getMicrotime();
	DUPX_Log::info("\nINSERT DATA RUNTIME: ".DUPX_U::elapsedTime($profile_end, $profile_start));
}

//FINAL RESULTS
$ajax1_sum	 = DUPX_U::elapsedTime(DUPX_U::getMicrotime(), $ajax2_start);
DUPX_Log::info('STEP-2 COMPLETE @ '.@date('h:i:s')." - RUNTIME: {$ajax1_sum}");

$JSON['pass'] = 1;
error_reporting($ajax2_error_level);
die(json_encode($JSON));
<?php
//-- START OF ACTION STEP 3: Update the database
require_once($GLOBALS['DUPX_INIT'].'/classes/config/class.archive.config.php');
require_once($GLOBALS['DUPX_INIT'].'/classes/utilities/class.u.multisite.php');

/** JSON RESPONSE: Most sites have warnings turned off by default, but if they're turned on the warnings
  cause errors in the JSON data Here we hide the status so warning level is reset at it at the end */
$ajax3_start		 = DUPX_U::getMicrotime();
$ajax3_error_level	 = error_reporting();
error_reporting(E_ERROR);

//POST PARAMS
$_POST['blogname']				 = isset($_POST['blogname']) ? $_POST['blogname'] : 'No Blog Title Set';
$_POST['postguid']				 = isset($_POST['postguid']) && $_POST['postguid'] == 1 ? 1 : 0;
$_POST['fullsearch']			 = isset($_POST['fullsearch']) && $_POST['fullsearch'] == 1 ? 1 : 0;
$_POST['path_old']				 = isset($_POST['path_old']) ? trim($_POST['path_old']) : null;
$_POST['path_new']				 = isset($_POST['path_new']) ? trim($_POST['path_new']) : null;
$_POST['siteurl']				 = isset($_POST['siteurl']) ? rtrim(trim($_POST['siteurl']), '/') : null;
$_POST['tables']				 = isset($_POST['tables']) && is_array($_POST['tables']) ? array_map('stripcslashes', $_POST['tables']) : array();
$_POST['url_old']				 = isset($_POST['url_old']) ? trim($_POST['url_old']) : null;
$_POST['url_new']				 = isset($_POST['url_new']) ? rtrim(trim($_POST['url_new']), '/') : null;
$_POST['subsite-id']			 = isset($_POST['subsite-id']) ? $_POST['subsite-id'] : -1;
$_POST['ssl_admin']				 = (isset($_POST['ssl_admin'])) ? true : false;
$_POST['ssl_login']				 = (isset($_POST['ssl_login'])) ? true : false;
$_POST['cache_wp']				 = (isset($_POST['cache_wp'])) ? true : false;
$_POST['cache_path']			 = (isset($_POST['cache_path'])) ? true : false;
$_POST['empty_schedule_storage'] = (isset($_POST['empty_schedule_storage']) && $_POST['empty_schedule_storage'] == '1') ? true : false;

$subsite_id	 = $_POST['subsite-id'];


//MYSQL CONNECTION
$dbh		 = DUPX_DB::connect($_POST['dbhost'], $_POST['dbuser'], html_entity_decode($_POST['dbpass']), $_POST['dbname'], $_POST['dbport']);
$dbConnError = (mysqli_connect_error()) ? 'Error: '.mysqli_connect_error() : 'Unable to Connect';

if (!$dbh) {
	$msg = "Unable to connect with the following parameters: <br/> <b>HOST:</b> {$_POST['dbhost']}<br/> <b>DATABASE:</b> {$_POST['dbname']}<br/>";
	$msg .= "<b>Connection Error:</b> {$dbConnError}";
	DUPX_Log::error($msg);
}

$charset_server	 = @mysqli_character_set_name($dbh);
@mysqli_query($dbh, "SET wait_timeout = {$GLOBALS['DB_MAX_TIME']}");
DUPX_DB::setCharset($dbh, $_POST['dbcharset'], $_POST['dbcollate']);
$charset_client	 = @mysqli_character_set_name($dbh);

//LOGGING
$date = @date('h:i:s');
$log  = <<<LOG
\n\n
********************************************************************************
DUPLICATOR PRO INSTALL-LOG
STEP-3 START @ {$date}
NOTICE: Do NOT post to public sites or forums
********************************************************************************
CHARSET SERVER:\t{$charset_server}
CHARSET CLIENT:\t{$charset_client}\n
LOG;
DUPX_Log::info($log);

$POST_LOG = $_POST;
unset($POST_LOG['tables']);
unset($POST_LOG['plugins']);
unset($POST_LOG['dbpass']);
ksort($POST_LOG);

//Detailed logging
$log = "--------------------------------------\n";
$log .= "POST DATA\n";
$log .= "--------------------------------------\n";
$log .= print_r($POST_LOG, true);
$log .= "--------------------------------------\n";
$log .= "TABLES TO SCAN\n";
$log .= "--------------------------------------\n";
$log .= (isset($_POST['tables']) && count($_POST['tables'] > 0)) ? print_r($_POST['tables'], true) : 'No tables selected to update';
$log .= "--------------------------------------\n";
$log .= "KEEP PLUGINS ACTIVE\n";
$log .= "--------------------------------------\n";
$log .= (isset($_POST['plugins']) && count($_POST['plugins'] > 0)) ? print_r($_POST['plugins'], true) : 'No plugins selected for activation';
DUPX_Log::info($log, 2);


//===============================================
//UPDATE ENGINE
//===============================================
$log = "--------------------------------------\n";
$log .= "SERIALIZER ENGINE\n";
$log .= "[*] scan every column\n";
$log .= "[~] scan only text columns\n";
$log .= "[^] no searchable columns\n";
$log .= "--------------------------------------";
DUPX_Log::info($log);

//MULTI-SITE SEARCH AND REPLACE LIST
// -1: Means network install so skip this
//  1: Root subsite so don't do this swap
DUPX_Log::info("Subsite id={$subsite_id}");

if ($subsite_id > 1) {
	DUPX_Log::info("####1");
	$ac = DUPX_ArchiveConfig::getInstance();

	foreach ($ac->subsites as $subsite) {
		DUPX_Log::info("####2");
		if ($subsite->id == $subsite_id) {
			DUPX_Log::info("####3");
			if ($GLOBALS['MU_MODE'] == DUPX_MultisiteMode::Subdomain) {

				DUPX_Log::info("#### subdomain mode");
				$old_subdomain = $subsite->name;
				$newval	 = $_POST['url_new'];
				$newval	 = preg_replace('#^https?://#', '', rtrim($newval, '/'));

				array_push($GLOBALS['REPLACE_LIST'], 
					array('search' => $old_subdomain, 'replace' => $newval),
					array('search' => urlencode($old_subdomain), 'replace' => urlencode($newval)));
			} else if ($GLOBALS['MU_MODE'] == DUPX_MultisiteMode::Subdirectory) {

				DUPX_Log::info("#### subdirectory mode");
				$old_subdirectory_url = $_POST['url_old'].$subsite->name;

				DUPX_Log::info("#### trying to replace $old_subdirectory_url ({$_POST['url_old']},{$subsite->name}) { with {$_POST['url_new']}");
				array_push($GLOBALS['REPLACE_LIST'], 
					array('search' => $old_subdirectory_url, 'replace' => $_POST['url_new']),
					array('search' => urlencode($old_subdirectory_url), 'replace' => urlencode($_POST['url_new'])));
			} else {
				DUPX_Log::info("#### neither mode {$GLOBALS['MU_MODE']}");
			}

			// Need to swap the subsite prefix for the main table prefix
			$subsite_uploads_dir = "/uploads/sites/{$subsite_id}";
			$subsite_prefix		 = "{$GLOBALS['FW_TABLEPREFIX']}{$subsite_id}_";
			array_push($GLOBALS['REPLACE_LIST'],
				array('search' => $subsite_uploads_dir, 'replace' => '/uploads'),
				array('search' => $subsite_prefix, 'replace' => $GLOBALS['FW_TABLEPREFIX']));

			break;
		}
	}

	DUPX_Log::info("####4");
	$new_content_dir = "{$_POST['path_new']}/{$GLOBALS['RELATIVE_CONTENT_DIR']}";

	try {
		DUPX_Log::info("####5");
		DUPX_MU::convertSubsiteToStandalone($_POST['subsite-id'], $dbh, $GLOBALS['FW_TABLEPREFIX'], $new_content_dir);
	} catch (Exception $ex) {
		DUPX_Log::info("####6");
		DUPX_Log::error("Problem with core logic of converting subsite into a standalone site.<br/>".$ex->getMessage().'<br/>'.$ex->getTraceAsString());
	}

	// Since we are converting subsite to multisite consider this a standalone site
	$GLOBALS['MU_MODE'] = DUPX_MultisiteMode::Standalone;
	DUPX_Log::info("####7");
}


//MULTI-SITE -> REPLACE LIST
//$mu_mode:
//0=(no multisite);
//1=(multisite subdomain);
//2=(multisite subdirectory)
if ($GLOBALS['MU_MODE'] == 1) {
	$mu_newDomain		 = parse_url($_POST['url_new']);
	$mu_oldDomain		 = parse_url($_POST['url_old']);
	$mu_newDomainHost	 = $mu_newDomain['host'];
	$mu_oldDomainHost	 = $mu_oldDomain['host'];

	array_push($GLOBALS['REPLACE_LIST'], array('search' => ('.'.$mu_oldDomainHost), 'replace' => ('.'.$mu_newDomainHost)));
}

//GENERAL -> REPLACE LIST
$url_old_json	 = str_replace('"', "", json_encode($_POST['url_old']));
$url_new_json	 = str_replace('"', "", json_encode($_POST['url_new']));
$path_old_json	 = str_replace('"', "", json_encode($_POST['path_old']));
$path_new_json	 = str_replace('"', "", json_encode($_POST['path_new']));

array_push($GLOBALS['REPLACE_LIST'], 
	array('search' => $_POST['url_old'], 'replace' => $_POST['url_new']),
	array('search' => $_POST['path_old'], 'replace' => $_POST['path_new']),
	array('search' => $url_old_json, 'replace' => $url_new_json),
	array('search' => $path_old_json, 'replace' => $path_new_json),
	array('search' => urlencode($_POST['path_old']), 'replace' => urlencode($_POST['path_new'])),
	array('search' => urlencode($_POST['url_old']), 'replace' => urlencode($_POST['url_new'])),
	array('search' => rtrim(DUPX_U::unsetSafePath($_POST['path_old']), '\\'), 'replace' => rtrim($_POST['path_new'], '/'))
);

//CUSTOM REPLACE -> REPLACE LIST
if (isset($_POST['search'])) {
	$search_count = count($_POST['search']);
	if ($search_count > 0) {
		for ($search_index = 0; $search_index < $search_count; $search_index++) {
			$search_for		 = $_POST['search'][$search_index];
			$replace_with	 = $_POST['replace'][$search_index];
			if (trim($search_for) != '') {
				array_push($GLOBALS['REPLACE_LIST'], array('search' => $search_for, 'replace' => $replace_with));
			}
		}
	}
}

//Remove trailing slashes
function _dupx_array_rtrim(&$value)
{
	$value = rtrim($value, '\/');
}
array_walk_recursive($GLOBALS['REPLACE_LIST'], _dupx_array_rtrim);

DUPX_Log::info("Final replace list: ", print_r($GLOBALS['REPLACE_LIST'], true));
$report = DUPX_UpdateEngine::load($dbh, $GLOBALS['REPLACE_LIST'], $_POST['tables'], $_POST['fullsearch']);

//BUILD JSON RESPONSE
$JSON						 = array();
$JSON['step1']				 = json_decode(urldecode($_POST['json']));
$JSON['step3']				 = $report;
$JSON['step3']['warn_all']	 = 0;
$JSON['step3']['warnlist']	 = array();

DUPX_UpdateEngine::logStats($report);
DUPX_UpdateEngine::logErrors($report);


//===============================================
//CREATE NEW ADMIN USER
//===============================================
if (strlen($_POST['wp_username']) >= 4 && strlen($_POST['wp_password']) >= 6) {

	$newuser_check	 = mysqli_query($dbh, "SELECT COUNT(*) AS count FROM `{$GLOBALS['FW_TABLEPREFIX']}users` WHERE user_login = '{$_POST['wp_username']}' ");
	$newuser_row	 = mysqli_fetch_row($newuser_check);
	$newuser_count	 = is_null($newuser_row) ? 0 : $newuser_row[0];

	if ($newuser_count == 0) {

		$newuser_datetime	 = @date("Y-m-d H:i:s");
		$newuser_security	 = mysqli_real_escape_string($dbh, 'a:1:{s:13:"administrator";s:1:"1";}');

		$newuser1 = @mysqli_query($dbh,
				"INSERT INTO `{$GLOBALS['FW_TABLEPREFIX']}users`
				(`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_registered`, `user_activation_key`, `user_status`, `display_name`)
				VALUES ('{$_POST['wp_username']}', MD5('{$_POST['wp_password']}'), '{$_POST['wp_username']}', '', '{$newuser_datetime}', '', '0', '{$_POST['wp_username']}')");

		$newuser1_insert_id = mysqli_insert_id($dbh);

		$newuser2 = @mysqli_query($dbh,
				"INSERT INTO `{$GLOBALS['FW_TABLEPREFIX']}usermeta`
				(`user_id`, `meta_key`, `meta_value`) VALUES ('{$newuser1_insert_id}', '{$GLOBALS['FW_TABLEPREFIX']}capabilities', '{$newuser_security}')");

		$newuser3 = @mysqli_query($dbh,
				"INSERT INTO `{$GLOBALS['FW_TABLEPREFIX']}usermeta`
				(`user_id`, `meta_key`, `meta_value`) VALUES ('{$newuser1_insert_id}', '{$GLOBALS['FW_TABLEPREFIX']}user_level', '10')");

		//Misc Meta-Data Settings:
		@mysqli_query($dbh, "INSERT INTO `{$GLOBALS['FW_TABLEPREFIX']}usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('{$newuser1_insert_id}', 'rich_editing', 'true')");
		@mysqli_query($dbh, "INSERT INTO `{$GLOBALS['FW_TABLEPREFIX']}usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('{$newuser1_insert_id}', 'admin_color',  'fresh')");
		@mysqli_query($dbh, "INSERT INTO `{$GLOBALS['FW_TABLEPREFIX']}usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('{$newuser1_insert_id}', 'nickname', '{$_POST['wp_username']}')");

		DUPX_Log::info("\nNEW WP-ADMIN USER:");
		if ($newuser1 && $newuser_test2 && $newuser3) {
			DUPX_Log::info("- New username '{$_POST['wp_username']}' was created successfully allong with MU usermeta.");
		} elseif ($newuser1) {
			DUPX_Log::info("- New username '{$_POST['wp_username']}' was created successfully.");
		} else {
			$newuser_warnmsg = "- Failed to create the user '{$_POST['wp_username']}' \n ";
			$JSON['step3']['warnlist'][] = $newuser_warnmsg;
			DUPX_Log::info($newuser_warnmsg);
		}
	} else {
		$newuser_warnmsg = "\nNEW WP-ADMIN USER:\n - Username '{$_POST['wp_username']}' already exists in the database.  Unable to create new account.\n";
		$JSON['step3']['warnlist'][] = $newuser_warnmsg;
		DUPX_Log::info($newuser_warnmsg);
	}
}


//===============================================
//CONFIGURATION FILE UPDATES
//===============================================
DUPX_Log::info("\n====================================");
DUPX_Log::info('CONFIGURATION FILE UPDATES:');
DUPX_Log::info("====================================\n");

$mu_newDomain		 = parse_url($_POST['url_new']);
$mu_oldDomain		 = parse_url($_POST['url_old']);
$mu_newDomainHost	 = $mu_newDomain['host'];
$mu_oldDomainHost	 = $mu_oldDomain['host'];
$mu_newUrlPath		 = parse_url($_POST['url_new'], PHP_URL_PATH);
$mu_oldUrlPath		 = parse_url($_POST['url_old'], PHP_URL_PATH);

if (empty($mu_newUrlPath) || ($mu_newUrlPath == '/')) {
	$mu_newUrlPath = '/';
} else {
	$mu_newUrlPath = rtrim($mu_newUrlPath, '/').'/';
}

if (empty($mu_oldUrlPath) || ($mu_oldUrlPath == '/')) {
	$mu_oldUrlPath = '/';
} else {
	$mu_oldUrlPath = rtrim($mu_oldUrlPath, '/').'/';
}

// UPDATE WP-CONFIG FILE
$patterns = array("/('|\")WP_HOME.*?\)\s*;/",
	"/('|\")WP_SITEURL.*?\)\s*;/",
	"/('|\")DOMAIN_CURRENT_SITE.*?\)\s*;/",
	"/('|\")PATH_CURRENT_SITE.*?\)\s*;/");

$replace = array("'WP_HOME', '{$_POST['url_new']}');",
	"'WP_SITEURL', '{$_POST['url_new']}');",
	"'DOMAIN_CURRENT_SITE', '{$mu_newDomainHost}');",
	"'PATH_CURRENT_SITE', '{$mu_newUrlPath}');");

if ($subsite_id != -1) {
	DUPX_Log::info("####10");

	array_push($patterns, "/('|\")WP_ALLOW_MULTISITE.*?\)\s*;/");
	array_push($patterns, "/('|\")MULTISITE.*?\)\s*;/");
	array_push($replace, "'ALLOW_MULTISITE', false);");
	array_push($replace, "'MULTISITE', false);");

	DUPX_Log::info('####patterns');
	DUPX_Log::info(print_r($patterns, true));
	DUPX_Log::info('####replace');
	DUPX_Log::info(print_r($replace, true));
}

if ($GLOBALS['MU_MODE'] !== DUPX_MultisiteMode::Standalone) {
	array_push($patterns, "/('|\")NOBLOGREDIRECT.*?\)\s*;/");
	array_push($replace, "'NOBLOGREDIRECT', '{$_POST['url_new']}');");
}

$root_path		 = $GLOBALS['DUPX_ROOT'];
$wpconfig_path	 = "{$root_path}/wp-config.php";
$config_file	 = @file_get_contents($wpconfig_path, true);
$defines		= DUPX_WPConfig::tokenParser($wpconfig_path);

// Tweak WP_CONTENT_DIR and WP_CONTENT_URL
if (array_key_exists('WP_CONTENT_DIR', $defines)) {
	$new_content_dir = str_replace($_POST['path_old'], $_POST['path_new'], DUPX_U::setSafePath($defines['WP_CONTENT_DIR']));
	array_push($patterns, "/('|\")WP_CONTENT_DIR.*?\)\s*;/");
	array_push($replace, "'WP_CONTENT_DIR', '{$new_content_dir}');");
}

if (array_key_exists('WP_CONTENT_URL', $defines)) {
	$new_content_url = str_replace($_POST['url_old'], $_POST['url_new'], $defines['WP_CONTENT_URL']);
	array_push($patterns, "/('|\")WP_CONTENT_URL.*?\)\s*;/");
	array_push($replace, "'WP_CONTENT_URL', '{$new_content_url}');");
}

$config_file = preg_replace($patterns, $replace, $config_file);
file_put_contents($wpconfig_path, $config_file);
DUPX_WPConfig::updateStandard();
DUPX_Log::info("UPDATED WP-CONFIG FILE:\n - '{$root_path}/wp-config.php'");

//Web Server Config Updates
if (!isset($_POST['url_new']) || $_POST['retain_config']) {
	DUPX_Log::info("\nNOTICE: Retaining the original .htaccess, .user.ini and web.config files may cause");
	DUPX_Log::info("issues with the initial setup of your site.  If you run into issues with your site or");
	DUPX_Log::info("during the install process please uncheck the 'Config Files' checkbox labeled:");
	DUPX_Log::info("'Retain original .htaccess, .user.ini and web.config' and re-run the installer.");
} else {
	DUPX_ServerConfig::setup($GLOBALS['MU_MODE'], $dbh, $root_path);
}



//===============================================
//GENERAL UPDATES & CLEANUP
//===============================================
DUPX_Log::info("\n====================================");
DUPX_Log::info('GENERAL UPDATES & CLEANUP:');
DUPX_Log::info("====================================\n");

$blog_name			 = mysqli_real_escape_string($dbh, $_POST['blogname']);
$plugin_list		 = (isset($_POST['plugins'])) ? $_POST['plugins'] : array();
$serial_plugin_list	 = @serialize($plugin_list);

// Force Duplicator Pro active so we the security cleanup will be available
if (!in_array('duplicator-pro/duplicator-pro.php', $plugin_list)) {
	$plugin_list[] = 'duplicator-pro/duplicator-pro.php';
}

/** FINAL UPDATES: Must happen after the global replace to prevent double pathing
  http://xyz.com/abc01 will become http://xyz.com/abc0101  with trailing data */
mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}options` SET option_value = '{$blog_name}' WHERE option_name = 'blogname' ");
mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}options` SET option_value = '{$serial_plugin_list}'  WHERE option_name = 'active_plugins' ");
mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}options` SET option_value = '{$_POST['url_new']}'  WHERE option_name = 'home' ");
mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}options` SET option_value = '{$_POST['siteurl']}'  WHERE option_name = 'siteurl' ");

//Reset the postguid data
if ($_POST['postguid']) {
	mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}posts` SET guid = REPLACE(guid, '{$_POST['url_new']}', '{$_POST['url_old']}')");
	$update_guid = @mysqli_affected_rows($dbh) or 0;
	DUPX_Log::info("Reverted '{$update_guid}' post guid columns back to '{$_POST['url_old']}'");
}


$mu_updates = @mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}blogs` SET domain = '{$mu_newDomainHost}' WHERE domain = '{$mu_oldDomainHost}'");
if ($mu_updates) {
	DUPX_Log::info("- Update MU table blogs: domain {$mu_newDomainHost} ");
}

if ($GLOBALS['MU_MODE'] == 2) {
	// _blogs update path column to replace /oldpath/ with /newpath/ */
	$result = @mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}blogs` SET path = CONCAT('$mu_newUrlPath', SUBSTRING(path, LENGTH('$mu_oldUrlPath') + 1))");
	if ($result === false) {
		DUPX_Log::error("Update to blogs table failed\n".mysqli_error($dbh));
	}
}

if (($GLOBALS['MU_MODE'] == 1) || ($GLOBALS['MU_MODE'] == 2)) {
	// _site update path column to replace /oldpath/ with /newpath/ */
	$result = @mysqli_query($dbh, "UPDATE `{$GLOBALS['FW_TABLEPREFIX']}site` SET path = CONCAT('$mu_newUrlPath', SUBSTRING(path, LENGTH('$mu_oldUrlPath') + 1)), domain = '$mu_newDomainHost'");
	if ($result === false) {
		DUPX_Log::error("Update to site table failed\n".mysqli_error($dbh));
	}
}

//SCHEDULE STORAGE CLEANUP
if (($_POST['empty_schedule_storage']) == true || (DUPX_U::$on_php_53_plus == false)) {

	$dbdelete_count	 = 0;
	$dbdelete_count1 = 0;
	$dbdelete_count2 = 0;

	@mysqli_query($dbh, "DELETE FROM `{$GLOBALS['FW_TABLEPREFIX']}duplicator_pro_entities` WHERE `type` = 'DUP_PRO_Storage_Entity'");
	$dbdelete_count1 = @mysqli_affected_rows($dbh);

	@mysqli_query($dbh, "DELETE FROM `{$GLOBALS['FW_TABLEPREFIX']}duplicator_pro_entities` WHERE `type` = 'DUP_PRO_Schedule_Entity'");
	$dbdelete_count2 = @mysqli_affected_rows($dbh);

	$dbdelete_count = (abs($dbdelete_count1) + abs($dbdelete_count2));
	DUPX_Log::info("- Removed '{$dbdelete_count}' schedule storage items");
}


//===============================================
//NOTICES TESTS
//===============================================
DUPX_Log::info("\n====================================");
DUPX_Log::info("NOTICES");
DUPX_Log::info("====================================\n");
$config_vars	= array('WPCACHEHOME', 'COOKIE_DOMAIN', 'WP_SITEURL', 'WP_HOME', 'WP_TEMP_DIR');
$config_found	= DUPX_U::getListValues($config_vars, $config_file);

//Files
if (! empty($config_found)) {
	$msg   = "WP-CONFIG NOTICE: The wp-config.php has following values set [".implode(", ", $config_found)."].  \n";
	$msg  .= "Please validate these values are correct by opening the file and checking the values.\n";
	$msg  .= "See the codex link for more details: https://codex.wordpress.org/Editing_wp-config.php";
	$JSON['step3']['warnlist'][] = $msg;
	DUPX_Log::info($msg);
}

//Database
$result = @mysqli_query($dbh, "SELECT option_value FROM `{$GLOBALS['FW_TABLEPREFIX']}options` WHERE option_name IN ('upload_url_path','upload_path')");
if ($result) {
	while ($row = mysqli_fetch_row($result)) {
		if (strlen($row[0])) {
			$msg  = "MEDIA SETTINGS NOTICE: The table '{$GLOBALS['FW_TABLEPREFIX']}options' has at least one the following values ['upload_url_path','upload_path'] \n";
			$msg .=	"set please validate settings. These settings can be changed in the wp-admin by going to /wp-admin/options.php'";
			$JSON['step3']['warnlist'][] = $msg;
			DUPX_Log::info($msg);
			break;
		}
	}
}

if (empty($JSON['step3']['warnlist'])) {
	DUPX_Log::info("No General Notices Found\n");
}

$JSON['step3']['warn_all'] = empty($JSON['step3']['warnlist']) ? 0 : count($JSON['step3']['warnlist']);

mysqli_close($dbh);
$ajax3_sum = DUPX_U::elapsedTime(DUPX_U::getMicrotime(), $ajax3_start);
DUPX_Log::info("\nSTEP-3 COMPLETE @ ".@date('h:i:s')." - RUNTIME: {$ajax3_sum} \n\n");

$JSON['step3']['pass'] = 1;
error_reporting($ajax3_error_level);
die(json_encode($JSON));

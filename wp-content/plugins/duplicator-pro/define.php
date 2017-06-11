<?php
//Prevent directly browsing to the file
if (function_exists('plugin_dir_url'))
{    
	define('DUPLICATOR_PRO_VERSION', '3.4.1');
    define('DUPLICATOR_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
    define('DUPLICATOR_PRO_SITE_URL', get_site_url());
	define('DUPLICATOR_PRO_IMG_URL', DUPLICATOR_PRO_PLUGIN_URL . '/assets/img');

    /* Paths should ALWAYS read "/"
      uni: /home/path/file.txt
      win:  D:/home/path/file.txt
      SSDIR = SnapShot Directory */
    if (!defined('ABSPATH'))
    {
        define('ABSPATH', dirname(__FILE__));
    }

    //PATH CONSTANTS
	if (! defined('DUPLICATOR_PRO_WPROOTPATH')) {
		define('DUPLICATOR_PRO_WPROOTPATH', str_replace('\\', '/', ABSPATH));
	}
    define("DUPLICATOR_PRO_SSDIR_NAME", 'backups-dup-pro');
    define('DUPLICATOR_PRO_PLUGIN_PATH',	 str_replace("\\", "/", plugin_dir_path(__FILE__)));
    define("DUPLICATOR_PRO_SSDIR_PATH", str_replace("\\", "/", WP_CONTENT_DIR . '/' . DUPLICATOR_PRO_SSDIR_NAME));
    define("DUPLICATOR_PRO_SSDIR_PATH_TMP", DUPLICATOR_PRO_SSDIR_PATH . '/tmp');
    define("DUPLICATOR_PRO_SSDIR_URL", content_url() . "/" . DUPLICATOR_PRO_SSDIR_NAME);
    define("DUPLICATOR_PRO_INSTALL_PHP", 'installer.php');
    define("DUPLICATOR_PRO_INSTALL_SQL", 'installer-data.sql');
    define("DUPLICATOR_PRO_INSTALL_LOG", 'installer-log.txt');
	define("DUPLICATOR_PRO_INSTALL_BOOT_LOG", 'installer-bootlog.txt');
    define("DUPLICATOR_PRO_DUMP_PATH", DUPLICATOR_PRO_SSDIR_PATH . '/dump');
	define('DUPLICATOR_PRO_EMBEDDED_SCAN_FILENAME', 'scan.json');
	define("DUPLICATOR_PRO_ENHANCED_INSTALLER_DIRECTORY", DUPLICATOR_PRO_WPROOTPATH . 'dpro-installer');
    
    //RESTRAINT CONSTANTS
    define("DUPLICATOR_PRO_PHP_MAX_MEMORY", '5000M');
    define("DUPLICATOR_PRO_DB_MAX_TIME", 5000);
    define("DUPLICATOR_PRO_DB_EOF_MARKER", 'DUPLICATOR_PRO_MYSQLDUMP_EOF');
    define("DUPLICATOR_PRO_SCAN_SITE_WARNING_SIZE", 524288000); //500MB
	define("DUPLICATOR_PRO_SCAN_SITE_SHELL_EXEC_WARNING_SIZE", 5368709120); //5 GB
    define("DUPLICATOR_PRO_SCAN_WARNFILESIZE", 6291456); //6MB
    define("DUPLICATOR_PRO_SCAN_CACHESIZE", 524288); //512K
    define("DUPLICATOR_PRO_SCAN_DBSIZE", 104857600); //100MB
    define("DUPLICATOR_PRO_SCAN_DBROWS", 250000);
    define("DUPLICATOR_PRO_SCAN_TIMEOUT", 25);   //Seconds
    define("DUPLICATOR_PRO_SCAN_MIN_WP", "3.7.0");
    $GLOBALS['DUPLICATOR_PRO_SERVER_LIST'] = array('Apache', 'LiteSpeed', 'Nginx', 'Lighttpd', 'IIS', 'WebServerX', 'uWSGI');
    $GLOBALS['DUPLICATOR_PRO_OPTS_DELETE'] = array('duplicator_pro_ui_view_state', 'duplicator_pro_package_active', 'duplicator_pro_settings');   
	
	//GLOBAL FILTERS: Prevent backups of non essential data
	// - To include a specific path just comment out the path
	// - Future plans to build UI around these settings
	$_dup_pro_upload_dir = wp_upload_dir();
	$_dup_pro_upload_dir = isset($_duplicator_pro_upload_dir['basedir']) ? basename($_duplicator_pro_upload_dir['basedir']) : 'uploads';
	$_dup_pro_wp_root = rtrim(DUPLICATOR_PRO_WPROOTPATH, '/');
	$_dup_pro_wp_content = str_replace("\\", "/", WP_CONTENT_DIR);
	$_dup_pro_wp_content_upload = "{$_dup_pro_wp_content}/{$_dup_pro_upload_dir}";
	$GLOBALS['DUPLICATOR_PRO_GLOBAL_FILE_FILTERS_ON'] = true;
	$GLOBALS['DUPLICATOR_PRO_GLOBAL_FILE_FILTERS'] = array(
		'error_log',
		'error.log',
		'debug_log',
		'ws_ftp.log',
		'dbcache',
		'pgcache',
		'objectcache'
	);
	
	$GLOBALS['DUPLICATOR_PRO_GLOBAL_DIR_FILTERS_ON'] = true;
	$GLOBALS['DUPLICATOR_PRO_GLOBAL_DIR_FILTERS'] = array(
		//WP-ROOT
		$_dup_pro_wp_root . '/wp-snapshots',
		
		//WP-CONTENT
		$_dup_pro_wp_content . '/ai1wm-backups',
		$_dup_pro_wp_content . '/backupwordpress',
		$_dup_pro_wp_content . '/content/cache',
		$_dup_pro_wp_content . '/contents/cache',
		$_dup_pro_wp_content . '/infinitewp/backups',
		$_dup_pro_wp_content . '/managewp/backups',
		$_dup_pro_wp_content . '/old-cache',
		$_dup_pro_wp_content . '/plugins/all-in-one-wp-migration/storage',
		$_dup_pro_wp_content . '/updraft',
		$_dup_pro_wp_content . '/wishlist-backup',
		$_dup_pro_wp_content . '/wfcache',		
		
				
		//WP-CONTENT-UPLOADS
		$_dup_pro_wp_content_upload . '/aiowps_backups',
		$_dup_pro_wp_content_upload . '/backupbuddy_temp',
		$_dup_pro_wp_content_upload . '/backupbuddy_backups',
		$_dup_pro_wp_content_upload . '/ithemes-security/backups',
		$_dup_pro_wp_content_upload . '/mainwp/backup',
		$_dup_pro_wp_content_upload . '/pb_backupbuddy',
		$_dup_pro_wp_content_upload . '/snapshots',
		$_dup_pro_wp_content_upload . '/sucuri',
		$_dup_pro_wp_content_upload . '/wp-clone',
		$_dup_pro_wp_content_upload . '/wp_all_backup',
		$_dup_pro_wp_content_upload . '/wpbackitup_backups'
	);
}
else
{
    error_reporting(0);
    $port = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ? "https://" : "http://";
    $url = $port . $_SERVER["HTTP_HOST"];
    header("HTTP/1.1 404 Not Found", true, 404);
    header("Status: 404 Not Found");
    exit();
}
?>

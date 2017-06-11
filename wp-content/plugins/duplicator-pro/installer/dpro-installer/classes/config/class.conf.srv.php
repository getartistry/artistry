<?php

/**
 * Class used to update and edit web server configuration files
 * for .htaccess, web.config and user.ini
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\ServerConfig
 *
 */
class DUPX_ServerConfig
{
	/**
	 *  Common timestamp of all memebers of this class
	 */
	public static $timestamp;

	/**
	 *  Setup this classes properties
	 */
	public static function init()
	{
		self::$timestamp = date("ymdHis");
	}

	/**
	 * Creates a copy of the orginal server config file and resets the orginal to blank
	 *
	 * @param string $path		The root path to the location of the server config files
	 *
	 * @return null
	 */
	public static function reset($path)
	{
		$time = self::$timestamp;
		DUPX_Log::info("\nWEB SERVER CONFIGURATION FILE STATUS:");

		//Apache
		if (self::runReset($path, '.htaccess')) {
			file_put_contents("{$path}/.htaccess", "#This file has been reset by Duplicator Pro. See .htaccess-{$time}.orig for the original file");
			@chmod("{$path}/.htaccess", 0644);
		}
		
		//.user.ini - For WordFence
		self::runReset($path, '.user.ini');

		//IIS
		self::runReset($path, 'web.config');

	}

	/**
	 * Sets up the web config file based on the inputs from the installer forms.
	 *
	 * @param int $mu_mode		Is this site a specific mutltisite mode
	 * @param object $dbh		The database connection handle for this request
	 * @param string $path		The path to the config file
	 *
	 * @return null
	 */
	public static function setup($mu_mode, $dbh, $path)
	{
		DUPX_Log::info("\nWEB SERVER CONFIGURATION FILE UPDATED:");

		$timestamp = date("Y-m-d H:i:s");
		$newdata = parse_url($_POST['url_new']);
		$newpath = DUPX_U::addSlash(isset($newdata['path']) ? $newdata['path'] : "");
		$update_msg  = "# This file was updated by Duplicator Pro on {$timestamp}.\n";
		$update_msg .= (file_exists("{$path}/.htaccess")) ? "# See .htaccess.orig for the .htaccess original file."	: "";


		//===============================================
		//BASIC SITE NO MU
		//===============================================
		if ($mu_mode == 0) {
			// no multisite
			$empty_htaccess	 = false;
			$query_result	 = @mysqli_query($dbh, "SELECT option_value FROM `{$GLOBALS['FW_TABLEPREFIX']}options` WHERE option_name = 'permalink_structure' ");

			if ($query_result) {
				$row = @mysqli_fetch_array($query_result);
				if ($row != null) {
					$permalink_structure = trim($row[0]);
					$empty_htaccess		 = empty($permalink_structure);
				}
			}


			if ($empty_htaccess) {
				$tmp_htaccess = '';
			} else {
				$tmp_htaccess = <<<HTACCESS
{$update_msg}
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$newpath}
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . {$newpath}index.php [L]
</IfModule>
# END WordPress
HTACCESS;
				DUPX_Log::info("- Preparing .htaccess file with basic setup.");
			}


		//===============================================
		//MULTISITE WITH MU
		//===============================================
		} else if ($mu_mode == 1) {

			// multisite subdomain
			$tmp_htaccess = <<<HTACCESS
{$update_msg}
# BEGIN WordPress
RewriteEngine On
RewriteBase {$newpath}
RewriteRule ^index\.php$ - [L]

# add a trailing slash to /wp-admin
RewriteRule ^wp-admin$ wp-admin/ [R=301,L]

RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^(wp-(content|admin|includes).*) $1 [L]
RewriteRule ^(.*\.php)$ $1 [L]
RewriteRule . index.php [L]
# END WordPress
HTACCESS;
			DUPX_Log::info("- Preparing .htaccess file with multisite subdomain setup.");

		} else {
			// multisite subdirectory
			$tmp_htaccess = <<<HTACCESS
{$update_msg}
# BEGIN WordPress
RewriteEngine On
RewriteBase {$newpath}
RewriteRule ^index\.php$ - [L]

# add a trailing slash to /wp-admin
RewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]

RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]
RewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]
RewriteRule . index.php [L]
# END WordPress
HTACCESS;
			DUPX_Log::info("- Preparing .htaccess file with multisite subdirectory setup.");

		}

		if (@file_put_contents("{$path}/.htaccess", $tmp_htaccess) === FALSE) {
			DUPX_Log::info("WARNING: Unable to update the .htaccess file! Please check the permission on the root directory and make sure the .htaccess exists.");
		} else {
			DUPX_Log::info("- Successfully the .htaccess file setting.");
		}
		@chmod('.htaccess', 0644);
		
	}


	/**
	 * Creates a copy of the orginal server config file and resets the orginal to blank per file
	 *
	 * @param string $path		The root path to the location of the server config file
	 * @param string $file_name	The file name of the config file
	 *
	 * @return bool		Returns true if the file was backedup and reset.
	 */
	private static function runReset($path, $file_name)
	{
		$status = false;
		$file	= "{$path}/{$file_name}";
		$time	= self::$timestamp;

		if (file_exists($file)) {
			if (copy($file, "{$file}-{$time}.orig")) {
				$status = @unlink("{$path}/{$file_name}");
			}
		}
		
		($status)
			? DUPX_Log::info("- {$file_name} was reset and a backup made to {$file_name}-{$time}.orig.")
			: DUPX_Log::info("- {$file_name} file was not reset or backed up.");

		return $status;
	}
}

DUPX_ServerConfig::init();
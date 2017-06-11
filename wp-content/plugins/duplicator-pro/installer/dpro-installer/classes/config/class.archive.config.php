<?php
/**
 * Class used to control values about the package meta data
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\ArchiveConfig
 *
 */
abstract class DUPX_LicenseType
{
	const Unlicensed	 = 0;
	const Personal	 = 1;
	const Freelancer	 = 2;
	const BusinessGold = 3;

}

class DUPX_ArchiveConfig
{
	const Config_Filename = 'archive.cfg';

	public $created;
	public $version_dup;
	public $version_wp;
	public $version_db;
	public $version_php;
	public $version_os;
	//GENERAL
	public $secure_on;
	public $secure_pass;
	public $skipscan;
	public $package_name;
	public $package_notes;
	public $wp_tableprefix;
	public $blogname;
	public $relative_content_dir;
	//STEP1
	//BASIC DB
	public $dbhost;
	public $dbname;
	public $dbuser;
	public $dbpass;
	//CPANEL: Login
	public $cpnl_host;
	public $cpnl_user;
	public $cpnl_pass;
	public $cpnl_enable;
	public $cpnl_connect;
	//CPANEL: DB
	public $cpnl_dbaction;
	public $cpnl_dbhost;
	public $cpnl_dbname;
	public $cpnl_dbuser;
	//ADV OPTS
	public $ssl_admin;
	public $ssl_login;
	public $cache_wp;
	public $cache_path;
	public $wproot;
	public $url_old;
	public $url_new;
	public $opts_delete;
	//MULTISITE
	public $mu_mode;
	public $subsites;
	//LICENSING
	public $license_limit;
	public $debug_mode = false;
	private static $instance = null;

	/**
	 * Loads a useable object from the archive.cfg file found in the dpro-installer root
	 *
	 * @param string $path		The root path to the location of the server config files
	 *
	 * @return obj	Returns an instance of DUPX_ArchiveConfig
	 */
	public static function getInstance()
	{
		if (self::$instance == null) {
			$config_filepath = realpath(dirname(__FILE__).'/../../'.self::Config_Filename);

			if (file_exists($config_filepath)) {
				self::$instance = new DUPX_ArchiveConfig();

				$file_contents = file_get_contents($config_filepath);
				$ac_data = json_decode($file_contents);

				foreach ($ac_data as $key => $value) {
					self::$instance->{$key} = $value;
				}

				if (isset($_GET['debug']) && ($_GET['debug'] == 1)) {
					self::$instance->debug_mode = true;
				}
			} else {
				echo "$config_filepath doesn't exist<br/>";
			}
		}

		return self::$instance;
	}

	/**
	 * Loads data from the archive.cfg file found into a set of $GLOBALS
	 *
	 * @todo: The $GLOBALS vars should be removed in favor of just
	 * using only the class properties, thus removing the need for this method
	 *
	 * @return bool	Returns true if the $GLOBALS got inited
	 */
	public static function initConfigGlobals()
	{
		/* @var $ac DUPX_ArchiveConfig */
		$ac = self::getInstance();

		if ($ac != null) {
			//COMPARE VALUES
			$GLOBALS['FW_CREATED']		 = $ac->created; // '%fwrite_created%';
			$GLOBALS['FW_VERSION_DUP']	 = $ac->version_dup; // '%fwrite_version_dup%';
			$GLOBALS['FW_VERSION_WP']	 = $ac->version_wp; // '%fwrite_version_wp%';
			$GLOBALS['FW_VERSION_DB']	 = $ac->version_db; // '%fwrite_version_db%';
			$GLOBALS['FW_VERSION_PHP']	 = $ac->version_php; // '%fwrite_version_php%';
			$GLOBALS['FW_VERSION_OS']	 = $ac->version_os; //'%fwrite_version_os%';
			//GENERAL
			$GLOBALS['FW_SECUREON']		 = $ac->secure_on; // '%fwrite_secure_on%';
			$GLOBALS['FW_SECUREPASS']	 = $ac->secure_pass; // '%fwrite_secure_pass%';
			$GLOBALS['FW_SKIPSCAN']		 = $ac->skipscan; // '%fwrite_skipscan%';

			$GLOBALS['FW_PACKAGE_NOTES'] = $ac->package_notes; // '%fwrite_package_notes%';
			$GLOBALS['FW_TABLEPREFIX']	 = $ac->wp_tableprefix; // '%fwrite_wp_tableprefix%';
			$GLOBALS['FW_BLOGNAME']		 = $ac->blogname; // '%fwrite_blogname%';

			$GLOBALS['DBSAFE_BLOGNAME'] = preg_replace("/[^A-Za-z0-9?!]/", '', $GLOBALS['FW_BLOGNAME']);

			$GLOBALS['RELATIVE_CONTENT_DIR'] = $ac->relative_content_dir;

			//STEP1
			//BASIC DB
			$GLOBALS['FW_DBHOST']	 = $ac->dbhost; // '%fwrite_dbhost%';
			$GLOBALS['FW_DBHOST']	 = empty($GLOBALS['FW_DBHOST']) ? 'localhost' : $GLOBALS['FW_DBHOST'];

			$GLOBALS['FW_DBNAME']	 = $ac->dbname; // '%fwrite_dbname%';
			$GLOBALS['FW_DBUSER']	 = $ac->dbuser; // '%fwrite_dbuser%';
			$GLOBALS['FW_DBPASS']	 = $ac->dbpass; // '%fwrite_dbpass%';
			//CPANEL: Login
			$GLOBALS['FW_CPNL_HOST'] = $ac->cpnl_host; // '%fwrite_cpnl_host%';
			$GLOBALS['FW_CPNL_HOST'] = empty($GLOBALS['FW_CPNL_HOST']) ? "https://{$GLOBALS['HOST_NAME']}:2083" : $GLOBALS['FW_CPNL_HOST'];

			$GLOBALS['FW_CPNL_USER']	 = $ac->cpnl_user; // '%fwrite_cpnl_user%';
			$GLOBALS['FW_CPNL_PASS']	 = $ac->cpnl_pass; // '%fwrite_cpnl_pass%';
			$GLOBALS['FW_CPNL_ENABLE']	 = $ac->cpnl_enable; // '%fwrite_cpnl_enable%';
			$GLOBALS['FW_CPNL_CONNECT']	 = $ac->cpnl_connect; // '%fwrite_cpnl_connect%';
			//CPANEL: DB
			$GLOBALS['FW_CPNL_DBACTION'] = $ac->cpnl_dbaction; // '%fwrite_cpnl_dbaction%';
			$GLOBALS['FW_CPNL_DBHOST']	 = $ac->cpnl_dbhost; // '%fwrite_cpnl_dbhost%';
			$GLOBALS['FW_CPNL_DBHOST']	 = empty($GLOBALS['FW_CPNL_DBHOST']) ? 'localhost' : $GLOBALS['FW_CPNL_DBHOST'];

			$GLOBALS['FW_CPNL_DBNAME']	 = strlen($ac->cpnl_dbname /* '%fwrite_cpnl_dbname%' */) ? $ac->cpnl_dbname /* '%fwrite_cpnl_dbname%' */ : '';
			$GLOBALS['FW_CPNL_DBUSER']	 = $ac->cpnl_dbuser /* '%fwrite_cpnl_dbuser%' */;

			//ADV OPTS
			$GLOBALS['FW_SSL_ADMIN']	 = $ac->ssl_admin; // '%fwrite_ssl_admin%';
			$GLOBALS['FW_SSL_LOGIN']	 = $ac->ssl_login; // '%fwrite_ssl_login%';
			$GLOBALS['FW_CACHE_WP']		 = $ac->cache_wp; // '%fwrite_cache_wp%';
			$GLOBALS['FW_CACHE_PATH']	 = $ac->cache_path; // '%fwrite_cache_path%';
			$GLOBALS['FW_WPROOT']		 = $ac->wproot; // '%fwrite_wproot%';
			$GLOBALS['FW_URL_OLD']		 = $ac->url_old; // '%fwrite_url_old%';
			$GLOBALS['FW_URL_NEW']		 = $ac->url_new; // '%fwrite_url_new%';
			$GLOBALS['MU_MODE']			 = $ac->mu_mode; // '%mu_mode%';
			$GLOBALS['FW_OPTS_DELETE']	 = json_decode($ac->opts_delete /* "%fwrite_opts_delete%" */, true);
		}

		return ($ac != null);
	}

	/**
	 * Returns the licence type this installer file is made of.
	 *
	 * @return obj	Returns an enum type of DUPX_LicenseType
	 */
	public function getLicenseType()
	{
		$license_type = DUPX_LicenseType::Personal;

		if ($this->license_limit < 0) {
			$license_type = DUPX_LicenseType::Unlicensed;
		} else if ($this->license_limit < 15) {
			$license_type = DUPX_LicenseType::Personal;
		} else if ($this->license_limit < 500) {
			$license_type = DUPX_LicenseType::Freelancer;
		} else if ($this->license_limit >= 500) {
			$license_type = DUPX_LicenseType::BusinessGold;
		}

		return $license_type;
	}
}
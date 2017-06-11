<?php
if (!defined('DUPLICATOR_PRO_VERSION')) exit; // Exit if accessed directly

require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/entities/class.system.global.entity.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/utilities/class.u.shell.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/class.archive.config.php');

class DUP_PRO_Installer
{
    public $File;
    public $Size             = 0;
    //SETUP
    public $OptsSecureOn;
    public $OptsSecurePass;
    public $OptsSkipScan;
    //BASIC
    public $OptsDBHost;
    public $OptsDBName;
    public $OptsDBUser;
    //CPANEL
    public $OptsCPNLHost     = '';
    public $OptsCPNLUser     = '';
    public $OptsCPNLPass     = '';
    public $OptsCPNLEnable   = false;
    public $OptsCPNLConnect  = false;
    //CPANEL DB
    //1 = Create New, 2 = Connect Remove
    public $OptsCPNLDBAction = 'create';
    public $OptsCPNLDBHost   = '';
    public $OptsCPNLDBName   = '';
    public $OptsCPNLDBUser   = '';
    //ADVANCED OPTS
    public $OptsSSLAdmin;
    public $OptsSSLLogin;
    public $OptsCacheWP;
    public $OptsCachePath;
    //OTHER
    public $OptsURLNew;
    //PROTECTED
    protected $Package;

    //CONSTRUCTOR
    function __construct($package)
    {
        $this->Package = $package;
    }

    public function get_safe_filepath()
    {
        return DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$this->File}");
    }

    public function get_url()
    {
        return DUPLICATOR_PRO_SSDIR_URL."/{$this->File}";
    }

    public function build($package, $build_progress)
    {
        /* @var $package DUP_PRO_Package */
        DUP_PRO_LOG::trace("building installer");

        $this->Package = $package;
        $success       = false;

        if ($this->create_enhanced_installer_files()) {
            $success = $this->add_extra_files($package);
        }

        if ($success) {
            $build_progress->installer_built = true;
        } else {
            $build_progress->failed = true;
        }
    }

    private function create_enhanced_installer_files()
    {
        $success = false;

        if ($this->create_enhanced_installer()) {
            $success = $this->create_archive_config_file();
        }

        return $success;
    }

    private function create_enhanced_installer()
    {
        $global = DUP_PRO_Global_Entity::get_instance();

        $success = true;

        $installer_filepath = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$this->Package->NameHash}_{$global->installer_base_name}";
        $template_filepath  = DUPLICATOR_PRO_PLUGIN_PATH.'/installer/installer.tpl';

        // Replace the @@ARCHIVE@@ token
        $installer_contents = file_get_contents($template_filepath);

        $search_array  = array('@@ARCHIVE@@', '@@VERSION@@', '@@ARCHIVE_SIZE@@');
        $replace_array = array($this->Package->Archive->File, DUPLICATOR_PRO_VERSION, $this->Package->Archive->Size);

        //$installer_contents = str_replace("@@ARCHIVE@@", $this->Package->Archive->File, $installer_contents);
        $installer_contents = str_replace($search_array, $replace_array, $installer_contents);

        if (@file_put_contents($installer_filepath, $installer_contents) === false) {
            DUP_PRO_Log::error(DUP_PRO_U::__('Error writing installer contents'), DUP_PRO_U::__("Couldn't write to $installer_filepath"), false);
            $success = false;
        }

        if ($success) {
            $storePath  = "{$this->Package->StorePath}/{$this->File}";
            $this->Size = @filesize($storePath);
        }

        return $success;
    }

    private function create_archive_config_file()
    {
        global $wpdb;

        $global                  = DUP_PRO_Global_Entity::get_instance();
        $success                 = true;
        $archive_config_filepath = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$this->Package->NameHash}_archive.cfg";
        //$archive_config_filepath = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP) . "/dpa.cfg";
        $ac                      = new DUP_PRO_Archive_Config();

        //COMPARE VALUES
        $ac->created     = $this->Package->Created;
        $ac->version_dup = DUPLICATOR_PRO_VERSION;
        $ac->version_wp  = $this->Package->VersionWP;
        $ac->version_db  = $this->Package->VersionDB;
        $ac->version_php = $this->Package->VersionPHP;
        $ac->version_os  = $this->Package->VersionOS;

        //GENERAL
        $ac->secure_on           = $this->Package->Installer->OptsSecureOn;
        $ac->secure_pass         = DUP_PRO_Crypt::scramble(base64_decode($this->Package->Installer->OptsSecurePass));
        $ac->skipscan            = $this->Package->Installer->OptsSkipScan;
        $ac->installer_base_name = $global->installer_base_name;
        $ac->package_name        = "{$this->Package->NameHash}_archive.zip";
        $ac->package_notes       = $this->Package->Notes;
        $ac->url_old             = get_option('siteurl');
        $ac->url_new             = $this->Package->Installer->OptsURLNew;
        $ac->dbhost              = $this->Package->Installer->OptsDBHost;
        $ac->dbname              = $this->Package->Installer->OptsDBName;
        $ac->dbuser              = $this->Package->Installer->OptsDBUser;
        $ac->dbpass              = '';
        $ac->ssl_admin           = $this->Package->Installer->OptsSSLAdmin;
        $ac->ssl_login           = $this->Package->Installer->OptsSSLLogin;
        $ac->cache_wp            = $this->Package->Installer->OptsCacheWP;
        $ac->cache_path          = $this->Package->Installer->OptsCachePath;

        $ac->opts_delete          = json_encode($GLOBALS['DUPLICATOR_PRO_OPTS_DELETE']);
        $ac->blogname             = esc_html(get_option('blogname'));
        $ac->wproot               = DUPLICATOR_PRO_WPROOTPATH;
        $ac->relative_content_dir = str_replace(ABSPATH, '', WP_CONTENT_DIR);

        //MULTISITE
        $ac->mu_mode = DUP_PRO_MU::getMode();
        if ($ac->mu_mode == 0) {
            $ac->wp_tableprefix = $wpdb->prefix;
        } else {
            $ac->wp_tableprefix = $wpdb->base_prefix;
        }

        $ac->subsites = DUP_PRO_MU::getSubsites();

        if ($ac->subsites === false) {
            $success = false;
        }

        //CPANEL
        $ac->cpnl_host    = $this->Package->Installer->OptsCPNLHost;
        $ac->cpnl_user    = $this->Package->Installer->OptsCPNLUser;
        $ac->cpnl_pass    = $this->Package->Installer->OptsCPNLPass;
        $ac->cpnl_enable  = $this->Package->Installer->OptsCPNLEnable;
        $ac->cpnl_connect = $this->Package->Installer->OptsCPNLConnect;

        //CPANEL:DB
        $ac->cpnl_dbaction = $this->Package->Installer->OptsCPNLDBAction;
        $ac->cpnl_dbhost   = $this->Package->Installer->OptsCPNLDBHost;
        $ac->cpnl_dbname   = $this->Package->Installer->OptsCPNLDBName;
        $ac->cpnl_dbuser   = $this->Package->Installer->OptsCPNLDBUser;

        //LICENSING
        $ac->license_limit = $global->license_limit;

        $json = json_encode($ac);

        DUP_PRO_LOG::traceObject('json', $json);

        if (file_put_contents($archive_config_filepath, $json) === false) {
            DUP_PRO_Log::error("Error writing archive config", "Couldn't write archive config at $archive_config_filepath", false);
            $success = false;
        }

        return $success;
    }

    /**
     *  createZipBackup
     *  Puts an installer zip file in the archive for backup purposes.
     */
    private function add_extra_files($package)
    {
        $success                 = false;
        $global                  = DUP_PRO_Global_Entity::get_instance();
        $installer_filepath      = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$this->Package->NameHash}_{$global->installer_base_name}";
        $scan_filepath           = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$this->Package->NameHash}_scan.json";
        $sql_filepath            = DUP_PRO_U::safePath("{$this->Package->StorePath}/{$this->Package->Database->File}");
        $zip_filepath            = DUP_PRO_U::safePath("{$this->Package->StorePath}/{$this->Package->Archive->File}");
        $archive_config_filepath = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$this->Package->NameHash}_archive.cfg";

        if (file_exists($installer_filepath) == false) {
            DUP_PRO_Log::error("Installer $installer_filepath not present", '', false);
            return false;
        }

        if (file_exists($sql_filepath) == false) {
            DUP_PRO_Log::error("Database SQL file $sql_filepath not present", '', false);
            return false;
        }

        if (file_exists($archive_config_filepath) == false) {
            DUP_PRO_Log::error("Archive configuration file $archive_config_filepath not present", '', false);
            return false;
        }

        if ($package->Archive->file_count != 2) {
            DUP_PRO_LOG::trace("Doing archive file check");
            // Only way it's 2 is if the root was part of the filter in which case the archive won't be there
            if (file_exists($zip_filepath) == false) {
                $error_text = DUP_PRO_U::__("Zip archive {$zip_filepath} not present.");
                $fix_text   = DUP_PRO_U::__("Go to: Settings > Packages Tab > Set Archive Engine to ZipArchive.");

                DUP_PRO_Log::error("$error_text. **RECOMMENDATION: $fix_text", '', false);

                $system_global = DUP_PRO_System_Global_Entity::get_instance();
                $system_global->add_recommended_text_fix($error_text, $fix_text);
                $system_global->save();

                return false;
            }
        }

        DUP_PRO_LOG::trace("Add extra files: Current build mode = ".$package->build_progress->current_build_mode);

        if ($package->build_progress->current_build_mode == DUP_PRO_Archive_Build_Mode::ZipArchive) {
            $success = $this->add_extra_files_using_zip_archive($installer_filepath, $scan_filepath, $sql_filepath, $zip_filepath,
                $archive_config_filepath);
        } else if ($package->build_progress->current_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
            $success = $this->add_extra_files_using_shellexec($zip_filepath, $installer_filepath, $scan_filepath, $sql_filepath,
                $archive_config_filepath);
        }

        // No sense keeping the archive config around
        @unlink($archive_config_filepath);

        $package->Archive->Size = @filesize($zip_filepath);

        return $success;
    }

    private function add_extra_files_using_zip_archive($installer_filepath, $scan_filepath, $sql_filepath, $zip_filepath, $archive_config_filepath)
    {
        $success = false;

        $zipArchive = new ZipArchive();

        if ($zipArchive->open($zip_filepath, ZIPARCHIVE::CREATE) === TRUE) {
            DUP_PRO_LOG::trace("Successfully opened zip $zip_filepath");

          //  if ($zipArchive->addFile($scan_filepath, DUPLICATOR_PRO_EMBEDDED_SCAN_FILENAME)) {
              if (DUP_PRO_Zip_U::addFileToZipArchive($zipArchive, $scan_filepath, DUPLICATOR_PRO_EMBEDDED_SCAN_FILENAME)) {
                if ($this->add_installer_files_using_zip_archive($zipArchive, $installer_filepath, $archive_config_filepath)) {
                    DUP_PRO_Log::info("Installer files added to archive");
                    DUP_PRO_LOG::trace("Added to archive");

                    $success = true;
                } else {
                    DUP_PRO_Log::error("Unable to add enhanced enhanced installer files to archive.", '', false);
                }
            } else {
                DUP_PRO_Log::error("Unable to add scan file to archive.", '', false);
            }

            if ($zipArchive->close() === false) {
                DUP_PRO_Log::error("Couldn't close archive when adding extra files.");
                $success = false;
            }

            DUP_PRO_LOG::trace('After ziparchive close when adding installer');
        }

        return $success;
    }

    private function add_extra_files_using_shellexec($zip_filepath, $installer_filepath, $scan_filepath, $sql_filepath, $archive_config_filepath)
    {
        $success = false;
        $global  = DUP_PRO_Global_Entity::get_instance();

        $installer_source_directory      = DUPLICATOR_PRO_PLUGIN_PATH.'installer/';
        $installer_dpro_source_directory = "$installer_source_directory/dpro-installer";
        $extras_directory                = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP).'/extras';
        $extras_installer_directory      = $extras_directory.'/dpro-installer';

        $installer_backup_filepath = "$extras_directory/".$global->get_installer_backup_filename();

        $dest_sql_filepath            = "$extras_directory/database.sql";
        $dest_archive_config_filepath = "$extras_installer_directory/archive.cfg";
        $dest_scan_filepath           = "$extras_directory/scan.json";

        if (file_exists($extras_directory)) {
            if (DUP_PRO_IO::deleteTree($extras_directory) === false) {
                DUP_PRO_Log::error("Error deleting $extras_directory", '', false);
                return false;
            }
        }

        if (!@mkdir($extras_directory)) {
            DUP_PRO_Log::error("Error creating extras directory", "Couldn't create $extras_directory", false);
            return false;
        }

        if (!@mkdir($extras_installer_directory)) {
            DUP_PRO_Log::error("Error creating extras directory", "Couldn't create $extras_installer_directory", false);
            return false;
        }

        if (@copy($installer_filepath, $installer_backup_filepath) === false) {
            DUP_PRO_Log::error("Error copying $installer_filepath to $installer_backup_filepath", '', false);
            return false;
        }

        if (@copy($sql_filepath, $dest_sql_filepath) === false) {
            DUP_PRO_Log::error("Error copying $sql_filepath to $dest_sql_filepath", '', false);
            return false;
        }

        if (@copy($archive_config_filepath, $dest_archive_config_filepath) === false) {
            DUP_PRO_Log::error("Error copying $archive_config_filepath to $dest_archive_config_filepath", '', false);
            return false;
        }

        if (@copy($scan_filepath, $dest_scan_filepath) === false) {
            DUP_PRO_Log::error("Error copying $scan_filepath to $dest_scan_filepath", '', false);
            return false;
        }

        $one_stage_add = strtoupper($global->get_installer_extension()) == 'PHP';

        if ($one_stage_add) {
            // If the installer has the PHP extension copy the installer files to add all extras in one shot since the server supports creation of PHP files
            if (DUP_PRO_IO::copyDir($installer_dpro_source_directory, $extras_installer_directory) === false) {
                DUP_PRO_Log::error("Error copying installer file directory to extras directory",
                    "Couldn't copy $installer_source_directory to $extras_installer_directory", false);
                return false;
            }
        }

        //-- STAGE 1 ADD
        $compression_parameter = DUP_PRO_Shell_U::getCompressionParam();

        $command = 'cd '.escapeshellarg(DUP_PRO_U::safePath($extras_directory));
        $command .= ' && '.escapeshellcmd(DUP_PRO_Zip_U::getShellExecZipPath())." $compression_parameter".' -g -rq ';
        $command .= escapeshellarg($zip_filepath).' ./*';

        DUP_PRO_LOG::trace("Executing Shell Exec Zip Stage 1 to add extras: $command");

        $stderr = shell_exec($command);

        //-- STAGE 2 ADD
        if ($stderr == '') {
            if (!$one_stage_add) {
                // Since we didn't bundle the installer files in the earlier stage we have to zip things up right from the plugin source area
                $command = 'cd '.escapeshellarg($installer_source_directory);
                $command .= ' && '.escapeshellcmd(DUP_PRO_Zip_U::getShellExecZipPath())." $compression_parameter".' -g -rq ';
                $command .= escapeshellarg($zip_filepath).' dpro-installer/*';

                DUP_PRO_LOG::trace("Executing Shell Exec Zip Stage 2 to add installer files: $command");
                $stderr = shell_exec($command);
            }
        }

        DUP_PRO_IO::deleteTree($extras_directory);

        if ($stderr == '') {
            if (DUP_PRO_U::getExeFilepath('unzip') != NULL) {
                $installer_backup_filename = basename($installer_backup_filepath);

                // Verify the essential extras got in there
                $extra_count_string = "unzip -Z1 '$zip_filepath' | grep '$installer_backup_filename\|scan.json\|database.sql\|archive.cfg' | wc -l";

                DUP_PRO_LOG::trace("Executing extra count string $extra_count_string");

                $extra_count = DUP_PRO_Shell_U::runAndGetResponse($extra_count_string, 1);

                if (is_numeric($extra_count)) {
                    // Accounting for the sql and installer back files
                    if ($extra_count >= 4) {
                        // Since there could be files with same name accept when there are m
                        DUP_PRO_LOG::trace("Core extra files confirmed to be in the archive");
                        $success = true;
                    } else {
                        DUP_PRO_Log::error("Tried to verify core extra files but one or more were missing. Count = $extra_count", '', false);
                    }
                } else {
                    DUP_PRO_LOG::trace("Executed extra count string of $extra_count_string");
                    DUP_PRO_Log::error("Error retrieving extra count in shell zip ".$extra_count, '', false);
                }
            } else {
                DUP_PRO_LOG::trace("unzip doesn't exist so not doing the extra file check");
                $success = true;
            }
        } else {
            $error_text = DUP_PRO_U::__("Unable to add installer extras to archive $stderr.");
            $fix_text   = DUP_PRO_U::__("Go to: Settings > Packages Tab > Set Archive Engine to ZipArchive.");

            DUP_PRO_Log::error("$error_text  **RECOMMENDATION: $fix_text", '', false);

            $system_global = DUP_PRO_System_Global_Entity::get_instance();

            $system_global->add_recommended_text_fix($error_text, $fix_text);

            $system_global->save();
        }

        return $success;
    }

    // Add installer directory to the archive and the archive.cfg
    private function add_installer_files_using_zip_archive(&$zip_archive, $installer_filepath, $archive_config_filepath)
    {
        $success                   = false;
        /* @var $global DUP_PRO_Global_Entity */
        $global                    = DUP_PRO_Global_Entity::get_instance();
        $installer_backup_filename = $global->get_installer_backup_filename();

        DUP_PRO_LOG::trace('Adding enhanced installer files to archive using ZipArchive');

     //   if ($zip_archive->addFile($installer_filepath, $installer_backup_filename)) {
    if (DUP_PRO_Zip_U::addFileToZipArchive($zip_archive, $installer_filepath, $installer_backup_filename)) {
            DUPLICATOR_PRO_PLUGIN_PATH . 'installer/';

            $installer_directory = DUPLICATOR_PRO_PLUGIN_PATH.'installer/dpro-installer';
            ;

            if (DUP_PRO_Zip_U::addDirWithZipArchive($zip_archive, $installer_directory)) {
                $archive_config_local_name = 'dpro-installer/archive.cfg';

               // if ($zip_archive->addFile($archive_config_filepath, $archive_config_local_name)) {
                 if (DUP_PRO_Zip_U::addFileToZipArchive($zip_archive, $archive_config_filepath, $archive_config_local_name)) {
                    $success = true;
                } else {
                    DUP_PRO_Log::error("Error adding $archive_config_filepath to zipArchive", '', false);
                }
            } else {
                DUP_PRO_Log::error("Error adding directory $installer_directory to zipArchive", '', false);
            }
        } else {
            DUP_PRO_Log::error("Error adding backup installer file to zipArchive", '', false);
        }

        return $success;
    }

    // Returns true if correctly added installer backup to root false if not
    private static function add_installer_backup_file_to_root($package)
    {
        $global         = DUP_PRO_Global_Entity::get_instance();
        $installer_path = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$package->NameHash}_{$global->installer_base_name}";

        $home_path = get_home_path();

        // Add installer to root directory
        $archive_installerbak_filepath = $home_path.$global->get_installer_backup_filename();

        return DUP_PRO_IO::copyWithVerify($installer_path, $archive_installerbak_filepath);
    }

    // Returns false if correctly added installer backup to root false if not
    private static function add_sql_file_to_root($source_sql_filepath)
    {
        $home_path = get_home_path();

        $archive_sql_filepath = $home_path.'database.sql';

        return DUP_PRO_IO::copyWithVerify($source_sql_filepath, $archive_sql_filepath);
    }

    private static function add_scan_file_to_root($package)
    {
        $global           = DUP_PRO_Global_Entity::get_instance();
        $source_scan_path = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP)."/{$package->NameHash}_scan.json";

        $home_path = get_home_path();

        // Add scan to root directory
        $dest_scan_path = $home_path.DUPLICATOR_PRO_EMBEDDED_SCAN_FILENAME;

        return DUP_PRO_IO::copyWithVerify($source_scan_path, $dest_scan_path);
    }

}

<?php
/**
 * Global Entity Layer
 *
 * Standard: Missing
 *
 * @package DUP_PRO
 * @subpackage classes/entities
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.0.0
 *
 * @todo Finish Docs
 */
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/entities/class.json.entity.base.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH.'/classes/class.crypt.blowfish.php');

/**
 * @copyright 2016 Snap Creek LLC
 */
abstract class DUP_PRO_Dropbox_Transfer_Mode
{
    const Unconfigured = -1;
    const Disabled     = 0;
    const cURL         = 1;
    const FOpen_URL    = 2;

}

/**
 * @copyright 2016 Snap Creek LLC
 */
abstract class DUP_PRO_Thread_Lock_Mode
{
    const Flock    = 0;
    const SQL_Lock = 1;

}

/**
 * @copyright 2016 Snap Creek LLC
 */
abstract class DUP_PRO_Email_Build_Mode
{
    const No_Emails           = 0;
    const Email_On_Failure    = 1;
    const Email_On_All_Builds = 2;

}

/**
 * @copyright 2016 Snap Creek LLC
 */
abstract class DUP_PRO_JSON_Mode
{
    const PHP    = 0;
    const Custom = 1;

}

/**
 * @copyright 2016 Snap Creek LLC
 */
abstract class DUP_PRO_Archive_Build_Mode
{
    const Unconfigured = -1;
    const Auto         = 0; // should no longer be used
    const Shell_Exec   = 1;
    const ZipArchive   = 2;

}

/**
 * @copyright 2016 Snap Creek LLC
 */
class DUP_PRO_Server_Load_Reduction
{
    const None  = 0;
    const A_Bit = 1;
    const More  = 2;
    const A_Lot = 3;

    public static function microseconds_from_reduction($reduction)
    {
        switch ($reduction) {
            case self::A_Bit:
                return 9000;

            case self::More:
                return 29000;

            case self::A_Lot:
                return 92000;

            default:
                return 0;
        }
    }
}

abstract class DUP_PRO_License_Status
{
    const OutOfLicenses = -3;
    const Uncached      = -2;
    const Unknown       = -1;
    const Valid         = 0;
    const Invalid       = 1;
    const Inactive      = 2;
    const Disabled      = 3;
    const Site_Inactive = 4;
    const Expired       = 5;

}

abstract class DUP_PRO_ZipArchive_Mode
{
    const Multithreaded = 0;
    const SingleThread  = 1;

}

class DUP_PRO_Global_Entity extends DUP_PRO_JSON_Entity_Base
{
    //GENERAL
    public $uninstall_settings      = false;
    public $uninstall_files         = false;
    public $uninstall_tables        = true;
    public $wpfront_integrate       = false;
    public $send_trace_to_error_log = true;
    public $package_debug           = false;
    //PACKAGES::Processing
    public $package_mysqldump          = true;
    public $package_mysqldump_path     = '';
    public $package_phpdump_qrylimit   = 100;
    public $archive_build_mode         = DUP_PRO_Archive_Build_Mode::Unconfigured;
    public $server_load_reduction      = DUP_PRO_Server_Load_Reduction::None;
    public $max_package_runtime_in_min = 90;
    public $archive_compression        = true;  // TODO: PHP 7 allows ZipArchive to be set to Store - implement later
    //PACKAGES::Visual
    public $package_ui_created = 1;
    //PACKAGES::Adanced
    public $ziparchive_mode             = DUP_PRO_ZipArchive_Mode::Multithreaded;
    public $ziparchive_chunk_size_in_mb = 6;
    public $lock_mode                   = DUP_PRO_Thread_Lock_Mode::Flock;
    public $json_mode                   = DUP_PRO_JSON_Mode::PHP;
    public $php_max_worker_time_in_sec  = 15;
    public $ajax_protocol               = "http";
    public $custom_ajax_url             = "";
    public $clientside_kickoff          = false;
    public $basic_auth_enabled          = false;
    public $basic_auth_user             = '';
    public $basic_auth_password         = '';
    public $installer_base_name         = 'installer.php';
    //SCHEDULES
    public $send_email_on_build_mode   = DUP_PRO_Email_Build_Mode::Email_On_Failure;
    public $notification_email_address = '';
    //STORAGE
    public $storage_htaccess_off           = false;
    public $max_storage_retries            = 10;
    public $max_default_store_files        = 20;
    public $dropbox_upload_chunksize_in_kb = 2000;
    public $dropbox_transfer_mode          = DUP_PRO_Dropbox_Transfer_Mode::Unconfigured;
    public $gdrive_upload_chunksize_in_kb  = 2000;  // Not exposed through the UI (yet)
    public $s3_upload_part_size_in_kb      = 6000;   // Not exposed through the UI (yet)
    public $manual_mode_storage_ids        = array();
    //LICENSING
    public $license_status              = DUP_PRO_License_Status::Unknown;
    public $license_expiration_time     = 0;
    public $license_no_activations_left = false;
    public $license_key_visible         = true;
    public $lkp                         = '';
    public $license_limit               = -1;
    //UPDATE CACHING
    public $last_edd_api_response  = null;
    public $last_edd_api_timestamp = 0;
    //MISC - SOME SHOULD BE IN SYSTEM GLOBAL
    public $last_system_check_timestamp  = 0;

    const GLOBAL_NAME                          = 'dup_pro_global';

    public $initial_activation_timestamp = 0;

    public static function initialize_plugin_data()
    {
        $globals = parent::get_by_type(get_class());
        /* @var $globals DUP_PRO_Global_Entity */

        if (count($globals) == 0) {
            $global = new DUP_PRO_Global_Entity();

            $max_execution_time = ini_get("max_execution_time");

            if (empty($max_execution_time) || ($max_execution_time == 0) || ($max_execution_time == -1)) {
                $max_execution_time = 30;
            }

            // Default is just a bit under the .7 max
            $global->php_max_worker_time_in_sec = (int) (0.6 * (float) $max_execution_time);

            if ($global->php_max_worker_time_in_sec > 18) {
                // Cap it at 18 as a starting point since there have been some oddities experienced on a couple servers
                $global->php_max_worker_time_in_sec = 18;
            }

            $global->set_build_mode();
            $global->license_expiration_time = time() - 10;  // Ensure it expires right away
            $global->custom_ajax_url         = admin_url('admin-ajax.php', 'http');

            // Default local selected by default
            array_push($global->manual_mode_storage_ids, -2);

            $global->save();
        }
    }

    public function set_from_data($global_data)
    {
        //GENERAL
        $this->uninstall_settings      = $global_data->uninstall_settings;
        $this->uninstall_files         = $global_data->uninstall_files;
        $this->uninstall_tables        = $global_data->uninstall_tables;
        $this->wpfront_integrate       = $global_data->wpfront_integrate;
        $this->send_trace_to_error_log = $global_data->send_trace_to_error_log;
        $this->package_debug           = $global_data->package_debug;

        //PACKAGES::Processing
        $this->package_mysqldump          = $global_data->package_mysqldump;
        $this->package_mysqldump_path     = $global_data->package_mysqldump_path;
        $this->package_phpdump_qrylimit   = $global_data->package_phpdump_qrylimit;
        $this->archive_build_mode         = $global_data->archive_build_mode;
        $this->server_load_reduction      = $global_data->server_load_reduction;
        $this->max_package_runtime_in_min = $global_data->max_package_runtime_in_min;
        $this->archive_compression        = $global_data->archive_compression;  // TODO: PHP 7 allows ZipArchive to be set to Store - implement later
        //PACKAGES::Adanced
        $this->ziparchive_mode             = $global_data->ziparchive_mode;
        $this->ziparchive_chunk_size_in_mb = $global_data->ziparchive_chunk_size_in_mb;
        $this->lock_mode                   = $global_data->lock_mode;
        $this->json_mode                   = $global_data->json_mode;
        $this->php_max_worker_time_in_sec  = $global_data->php_max_worker_time_in_sec;
        $this->ajax_protocol               = $global_data->ajax_protocol;
        $this->custom_ajax_url             = $global_data->custom_ajax_url;
        $this->clientside_kickoff          = $global_data->clientside_kickoff;
        $this->basic_auth_enabled          = $global_data->basic_auth_enabled;
        $this->basic_auth_user             = $global_data->basic_auth_user;
        $this->basic_auth_password         = $global_data->basic_auth_password;
        $this->installer_base_name         = $global_data->installer_base_name;

        //SCHEDULES
        $this->send_email_on_build_mode   = $global_data->send_email_on_build_mode;
        $this->notification_email_address = $global_data->notification_email_address;

        //STORAGE
        $this->storage_htaccess_off           = $global_data->storage_htaccess_off;
        $this->max_storage_retries            = $global_data->max_storage_retries;
        $this->max_default_store_files        = $global_data->max_default_store_files;
        $this->dropbox_upload_chunksize_in_kb = $global_data->dropbox_upload_chunksize_in_kb;
        $this->dropbox_transfer_mode          = $global_data->dropbox_transfer_mode;
        $this->gdrive_upload_chunksize_in_kb  = $global_data->gdrive_upload_chunksize_in_kb;  // Not exposed through the UI (yet)
        $this->s3_upload_part_size_in_kb      = $global_data->s3_upload_part_size_in_kb;   // Not exposed through the UI (yet)
        $this->manual_mode_storage_ids        = $global_data->manual_mode_storage_ids;

        //LICENSING
        $this->license_status              = DUP_PRO_License_Status::Unknown;
        $this->license_expiration_time     = 0;
        $this->license_no_activations_left = false;
        $this->license_key_visible         = $global_data->license_key_visible;
        $this->lkp                         = $global_data->lkp;

        //UPDATE CACHING
        $this->last_edd_api_response  = null;
        $this->last_edd_api_timestamp = 0;

        //MISC - SOME SHOULD BE IN SYSTEM GLOBAL
        $this->last_system_check_timestamp = 0;

        $this->initial_activation_timestamp = 0;
    }

    public function set_build_mode()
    {
        $is_shellexec_zip_available = (DUP_PRO_Zip_U::getShellExecZipPath() != null);

        // If unconfigured go with auto logic
        // If configured for shell exec verify that mode exists otherwise slam it back

        if (($this->archive_build_mode == DUP_PRO_Archive_Build_Mode::Unconfigured) || ($this->archive_build_mode == DUP_PRO_Archive_Build_Mode::Auto)) {
            if ($is_shellexec_zip_available) {
                $this->archive_build_mode = DUP_PRO_Archive_Build_Mode::Shell_Exec;
            } else {
                $this->archive_build_mode = DUP_PRO_Archive_Build_Mode::ZipArchive;
            }
        } else if ($this->archive_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
            if (!$is_shellexec_zip_available) {
                $this->archive_build_mode = DUP_PRO_Archive_Build_Mode::ZipArchive;
            }
        }
    }

    public function save()
    {
        $result = false;
        $this->encrypt();
        $result = parent::save();
        $this->decrypt();   // Whenever its in memory its unencrypted
        return $result;
    }

    // Change settings that may need to be changed because we have restored to a different system
    public function adjust_settings_for_system()
    {
        $save_required = false;

//			$max_execution_time = ini_get("max_execution_time");
//
//			if(empty($max_execution_time))
//			{
//				$max_execution_time = 30;
//				DUP_PRO_LOG::trace("xxxx 1");
//			}
//			$max_worker_time = (int)(0.7 * (float)$max_execution_time);
//						
//			if($this->php_max_worker_time_in_sec > $max_worker_time)
//			{				
//				DUP_PRO_LOG::trace("Max worker time is set to {$this->php_max_worker_time_in_sec} so overriding to $max_worker_time");
//		
//				$this->php_max_worker_time_in_sec = $max_worker_time;
//				
//				$save_required = true;
//			}

        if ($save_required) {
            $this->save();
        }
    }

    private function encrypt()
    {
        /* @var $storage DUP_PRO_Storage_Entity */
        if (!empty($this->basic_auth_password)) {
            $this->basic_auth_password = DUP_PRO_Crypt_Blowfish::encrypt($this->basic_auth_password);
        }

        if (!empty($this->lkp)) {
            $this->lkp = DUP_PRO_Crypt_Blowfish::encrypt($this->lkp);
        }
    }

    private function decrypt()
    {
        /* @var $storage DUP_PRO_Storage_Entity */
        if (!empty($this->basic_auth_password)) {
            $this->basic_auth_password = DUP_PRO_Crypt_Blowfish::decrypt($this->basic_auth_password);
        }

        if (!empty($this->lkp)) {
            $this->lkp = DUP_PRO_Crypt_Blowfish::decrypt($this->lkp);
        }
    }

    public static function &get_instance()
    {
        if (isset($GLOBALS[self::GLOBAL_NAME]) == false) {
            /* @var $global DUP_PRO_Global_Entity */
            $global = null;

            $globals = DUP_PRO_JSON_Entity_Base::get_by_type(get_class());

            if (count($globals) > 0) {
                $global = $globals[0];

                $global->decrypt();
            } else {
                DUP_PRO_LOG::traceError("Global entity is null!");
            }

            $GLOBALS[self::GLOBAL_NAME] = $global;
        }

        return $GLOBALS[self::GLOBAL_NAME];
    }

    public function configure_dropbox_transfer_mode()
    {
        if ($this->dropbox_transfer_mode == DUP_PRO_Dropbox_Transfer_Mode::Unconfigured) {
            $has_curl      = DUP_PRO_Server::isCurlEnabled();
            $has_fopen_url = DUP_PRO_Server::isURLFopenEnabled();

            if ($has_curl) {
                $this->dropbox_transfer_mode = DUP_PRO_Dropbox_Transfer_Mode::cURL;
            } else {
                if ($has_fopen_url) {
                    $this->dropbox_transfer_mode = DUP_PRO_Dropbox_Transfer_Mode::FOpen_URL;
                } else {
                    $this->dropbox_transfer_mode = DUP_PRO_Dropbox_Transfer_Mode::Disabled;
                }
            }

            $this->save();
        }
    }

    public function get_installer_backup_filename()
    {
        $installer_extension = $this->get_installer_extension();

        if (trim($installer_extension) == '') {
            return 'installer-backup';
        } else {
            return "installer-backup.$installer_extension";
        }
    }

    public function get_installer_extension()
    {
        return pathinfo($this->installer_base_name, PATHINFO_EXTENSION);
    }
}
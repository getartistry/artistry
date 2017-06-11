<?php
if (!defined('DUPLICATOR_PRO_VERSION')) exit; // Exit if accessed directly

require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/entities/class.global.entity.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/entities/class.storage.entity.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/entities/class.package.template.entity.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/package/class.pack.upload.info.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/package/class.pack.archive.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/package/class.pack.installer.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/package/class.pack.database.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/class.io.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/class.logging.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/utilities/class.u.json.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/class.crypt.custom.php');


final class DUP_PRO_PackageStatus
{
    const REQUIREMENTS_FAILED = -6;
    const STORAGE_FAILED      = -5;
    const STORAGE_CANCELLED   = -4;
    const PENDING_CANCEL      = -3;
    const BUILD_CANCELLED     = -2;
    const ERROR               = -1;
    const PRE_PROCESS         = 0;
    const SCANNING            = 3;
    const AFTER_SCAN          = 5;
    const START               = 10;
    const DBSTART             = 20;
    const DBDONE              = 30;
    const ARCSTART            = 40;
    const ARCDONE             = 65;
    const COPIEDPACKAGE       = 70;
    const STORAGE_PROCESSING  = 75;
    const COMPLETE            = 100;

}

final class DUP_PRO_PackageType
{
    const MANUAL    = 0;
    const SCHEDULED = 1;
    const RUN_NOW   = 2;

}

final class DUP_PRO_Package_Build_Outcome
{
    const SUCCESS = 0;
    const FAILURE = 1;

}

class DUP_PRO_Build_Progress
{
    public $thread_start_time;
    public $initialized             = false;
    public $installer_built         = false;
    public $archive_started         = false;
    public $archive_built           = false;
    public $database_script_built   = false;
    public $failed                  = false;
    public $next_archive_file_index = 0;
    public $next_archive_dir_index  = 0;
    public $retries                 = 0;
    public $current_build_mode      = -1;

    public function set_build_mode()
    {
        DUP_PRO_LOG::trace('set build mode');

        if ($this->current_build_mode == -1) {
            $global = DUP_PRO_Global_Entity::get_instance();

            $global->set_build_mode();

            $global->save();

            if ($global->archive_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
                DUP_PRO_LOG::trace("shell exec is global setting so setting this package to shell exec");
                if (DUP_PRO_Zip_U::getShellExecZipPath() == null) {
                    $this->failed = true;
                    DUP_PRO_LOG::trace("Archive building set to shell exec but zip doesn't exist!  How did this get past the config?");
                }

                $this->current_build_mode = DUP_PRO_Archive_Build_Mode::Shell_Exec;
            } else {
                DUP_PRO_LOG::trace("Ziparchive is global setting so setting this package to ziparchive");
                $this->current_build_mode = DUP_PRO_Archive_Build_Mode::ZipArchive;
            }
        } else {
            DUP_PRO_LOG::trace("Build mode already set to $this->current_build_mode");
        }
    }

    public function has_completed()
    {
        return $this->failed || ($this->installer_built && $this->archive_built && $this->database_script_built);
    }

    public function timed_out($max_time)
    {
        if ($max_time > 0) {
            $time_diff = time() - $this->thread_start_time;
            return ($time_diff >= $max_time);
        } else {
            return false;
        }
    }

    public function start_timer()
    {
        $this->thread_start_time = time();
    }
}

abstract class DUP_PRO_Package_File_Type
{
    const Installer = 0;
    const Archive   = 1;
    const SQL       = 2;
    const Log       = 3;
    const Dump      = 4;
}

/**
 * Class used to store and process all Package logic
 * @package Dupicator\classes
 */
class DUP_PRO_Package
{
    const OPT_ACTIVE = 'duplicator_pro_package_active';

    //Properties
    public $Created;
    public $Version;
    public $VersionWP;
    public $VersionDB;
    public $VersionPHP;
    public $VersionOS;
    public $ID;
    public $Name;
    public $Hash;
    public $NameHash;
    public $Type            = -1;
    public $Notes;
    public $StorePath;
    public $StoreURL;
    public $ScanFile;
    public $timer_start     = -1;
    public $Runtime;
    public $ExeSize;
    public $ZipSize			= 0;
    public $ZipMode;
    public $ziparchive_mode;
    //Objects
    public $Archive;
    public $Installer;
    public $Database;
    public $Status            = DUP_PRO_PackageStatus::PRE_PROCESS;
    public $schedule_id       = -1;   // Schedule ID that created this
    // Chunking progress through build and storage uploads
    public $build_progress;
    public $upload_infos;
    public $active_storage_id = -1;
    public $template_id       = -1;

    public function add_log_to_zip($zip_filepath)
    {
        $log_filepath = $this->get_safe_log_filepath();
        if (file_exists($log_filepath)) {
            $log_filename = $this->ID.'_'.basename($log_filepath);
            return DUP_PRO_Zip_U::zipFile($log_filepath, $zip_filepath, false, $log_filename);
        } else {
            DUP_PRO_LOG::trace("$log_filepath doesnt exist to add to $zip_filepath");
            return true;
        }
    }

    /**
     *  Manages the Package Process
     */
    function __construct()
    {
        $this->ID        = null;
        $this->Version   = DUPLICATOR_PRO_VERSION;
        $this->Name      = self::get_default_name();
        $this->Notes     = null;
        $this->StoreURL  = DUPLICATOR_PRO_SSDIR_URL.'/';
        $this->StorePath = DUPLICATOR_PRO_SSDIR_PATH_TMP;
        $this->Database  = new DUP_PRO_Database($this);
        $this->Archive   = new DUP_PRO_Archive($this);
        $this->Installer = new DUP_PRO_Installer($this);

        $this->build_progress            = new DUP_PRO_Build_Progress();
        $this->upload_infos              = array();
        $default_upload_info             = new DUP_PRO_Package_Upload_Info();
        $default_upload_info->storage_id = DUP_PRO_Virtual_Storage_IDs::Default_Local;

        array_push($this->upload_infos, $default_upload_info);
    }

    public function cancel_all_uploads()
    {
        DUP_PRO_LOG::trace("Cancelling all uploads");

        // Cancel outstanding uploads
        /* @var $upload_info DUP_PRO_Package_Upload_Info */
        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->has_completed() == false) {
                $upload_info->cancelled = true;
            }
        }
    }

    public function get_latest_upload_infos()
    {
        $upload_infos = array();

        // Just save off the latest per the storage id
        foreach ($this->upload_infos as $upload_info) {
            $upload_infos[$upload_info->storage_id] = $upload_info;
        }

        return $upload_infos;
    }

    // What % along we are in the given status level
    public function get_status_progress()
    {
        if ($this->Status == DUP_PRO_PackageStatus::STORAGE_PROCESSING) {
            $completed_infos  = 0;
            $total_infos      = count($this->upload_infos);
            $partial_progress = 0;

            foreach ($this->upload_infos as $upload_info) {
                if ($upload_info->has_completed()) {
                    $completed_infos++;
                } else {
                    $partial_progress += $upload_info->progress;
                }
            }

            DUP_PRO_LOG::trace("partial progress $partial_progress");
            DUP_PRO_LOG::trace("completed infos before $completed_infos");
            $bcd = ($partial_progress / (float) 100);

            DUP_PRO_LOG::trace("partial progress info contributor=$bcd");
            $completed_infos += $bcd;
            DUP_PRO_LOG::trace("completed infos after $completed_infos");

            // Add on the particulars where the latest guy is at
            // return 100 * (bcdiv($completed_infos, $total_infos, 2));
            return DUP_PRO_U::percentage($completed_infos, $total_infos, 0);
        } else {
            return 0;
        }
    }

    public function does_default_storage_exist()
    {
        $retval = false;

        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->storage_id == DUP_PRO_Virtual_Storage_IDs::Default_Local) {
                if ($upload_info->has_completed(true)) {
                    $retval = ($this->get_local_package_file(DUP_PRO_Package_File_Type::Archive, true) != null);
                }
            }
        }

        return $retval;
    }

    public function add_upload_infos($storage_ids)
    {
        DUP_PRO_LOG::trace('adding upload infos');
        $this->upload_infos = array();

        foreach ($storage_ids as $storage_id) {
            $upload_info             = new DUP_PRO_Package_Upload_Info();
            $upload_info->storage_id = $storage_id;
            array_push($this->upload_infos, $upload_info);
        }

        DUP_PRO_LOG::trace("upload infos added:".count($this->upload_infos));
    }

    public function get_display_size()
    {
        $storage_problem = (($this->Status == DUP_PRO_PackageStatus::STORAGE_CANCELLED) || ($this->Status == DUP_PRO_PackageStatus::STORAGE_FAILED));

        if ($this->Status == 100 || $storage_problem) {
            return DUP_PRO_U::byteSize($this->Archive->Size);
        } else {
            $size              = 0;
            $temp_archive_path = DUPLICATOR_PRO_SSDIR_PATH_TMP.'/'.$this->get_archive_filename();
            $archive_path      = DUPLICATOR_PRO_SSDIR_PATH.'/'.$this->get_archive_filename();

            if (file_exists($archive_path)) {
                $size = @filesize($archive_path);
            } else if (file_exists($temp_archive_path)) {
                $size = @filesize($temp_archive_path);
            } else {
                //  DUP_PRO_LOG::trace("Couldn't find archive for file size");
            }
            return DUP_PRO_U::byteSize($size);
        }
    }

    public function get_scan_filename()
    {
        return $this->NameHash.'_scan.json';
    }

    public function get_safe_scan_filepath()
    {
        $filename = $this->get_scan_filename();
        return DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/$filename");
    }

    public function get_log_filename()
    {
        return $this->NameHash.'.log';
    }

    public function get_dump_filename()
    {
        return $this->NameHash.'_dump.txt';
    }

    public function get_safe_log_filepath()
    {
        $filename = $this->get_log_filename();
        return DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/$filename");
    }

    public function dump_file_exists()
    {
        $filename = $this->get_dump_filename();
        $filepath = DUP_PRO_U::safePath(DUPLICATOR_PRO_DUMP_PATH."/$filename");
        return file_exists($filepath);
    }

    public function &get_upload_info_for_storage_id($storage_id)
    {
        $selected_upload_info = null;

        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->storage_id == $storage_id) {
                $selected_upload_info = &$upload_info;
                break;
            }
        }

        return $selected_upload_info;
    }

    public function get_local_package_file($file_type, $only_default = false)
    {
        $file_path = null;

        if ($file_type == DUP_PRO_Package_File_Type::Installer) {
            DUP_PRO_LOG::trace("Installer requested");
            $file_name = $this->get_installer_filename();
        } else if ($file_type == DUP_PRO_Package_File_Type::Archive) {
            DUP_PRO_LOG::trace("Archive requested");
            $file_name = $this->get_archive_filename();
        } else if ($file_type == DUP_PRO_Package_File_Type::SQL) {
            DUP_PRO_LOG::trace("SQL requested");
            $file_name = $this->get_database_filename();
        } else if ($file_type == DUP_PRO_Package_File_Type::Dump) {
            $file_name     = $this->get_dump_filename();
            // Log file is special case since it should always present in default location
            $log_file_path = DUP_PRO_U::safePath(DUPLICATOR_PRO_DUMP_PATH)."/$file_name";

            if (file_exists($log_file_path)) {
                return $log_file_path;
            } else {
                return null;
            }
        } else {
            // log
            $file_name     = $this->get_log_filename();
            // Log file is special case since it should always present in default location
            $log_file_path = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH)."/$file_name";

            if (file_exists($log_file_path)) {
                return $log_file_path;
            } else {
                return null;
            }
        }

        $successful_local_storages = array();

        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->has_completed(true)) {
                $storage = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id, false);
                if (($storage != null) && ($storage->storage_type == DUP_PRO_Storage_Types::Local)) {
                    array_push($successful_local_storages, $storage);
                }
            }
        }

        foreach ($successful_local_storages as $storage) {
            $candidate_path = "$storage->local_storage_folder/$file_name";

            if (file_exists($candidate_path)) {
                if (($only_default == false) || ($storage->id == DUP_PRO_Virtual_Storage_IDs::Default_Local)) {
                    $file_path = $candidate_path;
                    break;
                }
            }
        }

        return $file_path;
    }

    public function process_storages()
    {
        DUP_PRO_LOG::trace("Processing storages");
        DUP_PRO_Log::info("\n********************************************************************************");
        DUP_PRO_Log::info("STORAGE PROCESSING:");
        DUP_PRO_Log::info("********************************************************************************");

        $complete = (count($this->upload_infos) == 0);  // Indicates if all storages have finished (succeeded or failed all-together)

        $error_present         = false;
        $local_default_present = false;

        if (!$complete) {
            $complete            = true;
            $latest_upload_infos = $this->get_latest_upload_infos();

            foreach ($latest_upload_infos as $upload_info) {
                DUP_PRO_LOG::trace("upload loop 1");
                if ($upload_info->storage_id == DUP_PRO_Virtual_Storage_IDs::Default_Local) {
                    $local_default_present = true;
                }

                if ($upload_info->failed) {
                    DUP_PRO_LOG::trace("upload loop 3");
                    $error_present = true;
                } else if ($upload_info->has_completed() == false) {
                    DUP_PRO_LOG::trace("upload loop 4");
                    $complete = false;

                    DUP_PRO_LOG::trace("upload loop 5");
                    DUP_PRO_LOG::trace("telling storage id $upload_info->storage_id to process");
                    $storage = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id);

                    if ($upload_info->has_started() === false) {
                        $upload_info->start();
                    }

                    // Process a bit of work then let the next cron take care of if it's completed or not.
                    $storage->process_package($this, $upload_info);

                    if ($upload_info->has_completed()) {
                        // It just completed so update its upload status
                        $upload_info->end_ticks = time();
                    }

                    break;
                }
            }
        } else {
            DUP_PRO_LOG::trace("No storage ids defined for package $this->ID!");
            $error_present = true;
        }

        if ($complete) {
            if ($error_present) {
                DUP_PRO_LOG::trace("Storage error is present");
                $this->set_status(DUP_PRO_PackageStatus::STORAGE_FAILED);
                $this->send_build_email(1, false);

                if ($local_default_present == false) {
                    DUP_PRO_LOG::trace("deleting local files");
                    self::delete_default_local_files($this->NameHash, true, false);
                }
            } else {
                if ($local_default_present == false) {
                    DUP_PRO_LOG::trace("deleting local files");
                    self::delete_default_local_files($this->NameHash, true, false);
                } else {
                    /* @var $default_local_storage DUP_PRO_Storage_Entity */
                    $default_local_storage = DUP_PRO_Storage_Entity::get_default_local_storage();
                    $default_local_storage->purge_old_local_packages();
                }

                $this->set_status(DUP_PRO_PackageStatus::COMPLETE);
                $this->send_build_email(1, true);
            }
        }

        return $complete;
    }

    public static function get_all()
    {
        global $wpdb;
        $table    = $wpdb->prefix."duplicator_pro_packages";
        $packages = array();
        $rows     = $wpdb->get_results("SELECT * FROM `{$table}` ORDER BY ID ASC");
        if ($rows != null) {
            foreach ($rows as $row) {
                $package = self::package_from_row($row);
                if ($package != null) {
                    array_push($packages, $package);
                }
            }
        }
        return $packages;
    }

    public static function get_all_by_type($type)
    {
        $filtered_packages = array();
        $packages          = self::get_all();
        foreach ($packages as $package) {
            if ($package->Type == $type) {
                array_push($filtered_packages, $package);
            }
        }
        return $filtered_packages;
    }

    public function set_for_cancel()
    {
        $pending_cancellations = self::get_pending_cancellations();
        array_push($pending_cancellations, $this->ID);
        set_transient('dup_pro_pending_cancellations', $pending_cancellations, 60 * 60 * 24);
    }

    public static function get_pending_cancellations()
    {
        $pending_cancellations = get_transient('dup_pro_pending_cancellations');
        if ($pending_cancellations === false) {
            $pending_cancellations = array();
        }
        return $pending_cancellations;
    }

    public function is_cancel_pending()
    {
        $pending_cancellations = self::get_pending_cancellations();

        return in_array($this->ID, $pending_cancellations);
    }

    public static function clear_pending_cancellations()
    {
        if (delete_transient('dup_pro_pending_cancellations') == false) {
            DUP_PRO_LOG::traceError("Couldn't remove pending cancel transient");
        }
    }

    public static function get_by_id($id)
    {
        global $wpdb;
        $table = $wpdb->prefix."duplicator_pro_packages";
        $sql   = $wpdb->prepare("SELECT * FROM `{$table}` where ID = %d", $id);
        $row   = $wpdb->get_row($sql);
        return self::package_from_row($row);
    }

    // returns either package or null if can't get it
    private static function package_from_row($row)
    {
        $package = null;
        if ($row != null) {
            if (strlen($row->hash) == 0) {
                DUP_PRO_LOG::trace("Hash is 0 for the package $row->id...");
            } else {

                $package = self::get_from_json($row->package);

                if (($package == false) || !is_object($package)) {
                    DUP_PRO_LOG::traceError("Problem deserializing package or package not an object");
                } else {
                    // Since ID was stuffed into the package body the ID was known cant rely on it thus just do a quick copy on construction
                    $package->ID = $row->id;
                }
            }
        }
        return $package;
    }

    public function delete($delete_temp = false)
    {
        $ret_val   = false;
        global $wpdb;
        $tblName   = $wpdb->prefix.'duplicator_pro_packages';
        $getResult = $wpdb->get_results($wpdb->prepare("SELECT name, hash FROM `{$tblName}` WHERE id = %d", $this->ID), ARRAY_A);

        if ($getResult) {
            $row       = $getResult[0];
            $name_hash = "{$row['name']}_{$row['hash']}";
            $delResult = $wpdb->query($wpdb->prepare("DELETE FROM `{$tblName}` WHERE id = %d", $this->ID));

            if ($delResult != 0) {
                $ret_val = true;
                self::delete_default_local_files($name_hash, $delete_temp);
                $this->delete_local_storage_files();
            }
        }

        return $ret_val;
    }

    // Use only in extreme cases to get rid of a runaway package
    public static function force_delete($id)
    {
        $ret_val = false;
        global $wpdb;

        $tblName   = $wpdb->prefix.'duplicator_pro_packages';
        $getResult = $wpdb->get_results($wpdb->prepare("SELECT name, hash FROM `{$tblName}` WHERE id = %d", $id), ARRAY_A);

        if ($getResult) {
            $row       = $getResult[0];
            $name_hash = "{$row['name']}_{$row['hash']}";
            $delResult = $wpdb->query($wpdb->prepare("DELETE FROM `{$tblName}` WHERE id = %d", $id));

            if ($delResult != 0) {
                $ret_val = true;
                self::delete_default_local_files($name_hash, true);
            }
        }

        return $ret_val;
    }

    private function delete_local_storage_files()
    {
        $storages           = $this->get_storages(false);
        $archive_filename   = $this->get_archive_filename();
        $installer_filename = $this->get_installer_filename();
        $log_filename       = $this->get_log_filename();
        $scan_filename      = $this->get_scan_filename();
        $sql_filename       = $this->get_database_filename();

        foreach ($storages as $storage) {
            if ($storage->storage_type == DUP_PRO_Storage_Types::Local) {
                $archive_filepath   = "$storage->local_storage_folder/$archive_filename";
                $installer_filepath = "$storage->local_storage_folder/$installer_filename";
                $log_filepath       = "$storage->local_storage_folder/$log_filename";
                $scan_filepath      = "$storage->local_storage_folder/$scan_filename";
                $sql_filepath       = "$storage->local_storage_folder/$sql_filename";

                @unlink($archive_filepath);
                @unlink($installer_filepath);
                @unlink($log_filepath);
                @unlink($scan_filepath);
                @unlink($sql_filepath);
            }
        }
    }

    public static function delete_default_local_files($name_hash, $delete_temp, $delete_log_files = true)
    {
        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        $archive_cfg_temppath  = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$name_hash}_archive.cfg");
        $archive_zip_temppath  = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$name_hash}_archive.zip");
        $database_sql_temppath = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$name_hash}_database.sql");
        $installer_temppath    = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$name_hash}_{$global->installer_base_name}");
        $scan_json_temppath    = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$name_hash}_scan.json");

        $archive_cfg_filepath  = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$name_hash}_archive.cfg");
        $archive_zip_filepath  = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$name_hash}_archive.zip");
        $database_sql_filepath = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$name_hash}_database.sql");
        $installer_filepath    = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$name_hash}_{$global->installer_base_name}");
        $log_filepath          = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$name_hash}.log");
        $scan_json_filepath    = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH."/{$name_hash}_scan.json");

        //Perms
        if ($delete_temp) {
            @chmod($archive_cfg_temppath, 0644);
            @chmod($archive_zip_temppath, 0644);
            @chmod($database_sql_temppath, 0644);
            @chmod($installer_temppath, 0644);
            @chmod($scan_json_temppath, 0644);
        }

        @chmod($archive_cfg_filepath, 0644);
        @chmod($archive_zip_filepath, 0644);
        @chmod($database_sql_filepath, 0644);
        @chmod($installer_filepath, 0644);
        @chmod($scan_json_filepath, 0644);

        if ($delete_log_files) {
            @chmod($log_filepath, 0644);
        }

        if ($delete_temp) {
            @unlink($archive_cfg_temppath);
            @unlink($archive_zip_temppath);
            @unlink($database_sql_temppath);
            @unlink($installer_temppath);
            @unlink($scan_json_temppath);
        }

        @unlink($archive_cfg_temppath);
        @unlink($archive_zip_filepath);
        @unlink($database_sql_filepath);
        @unlink($installer_filepath);
        @unlink($scan_json_filepath);

        if ($delete_log_files) {
            @unlink($log_filepath);
        }

        //Unfinished Zip files
        if ($delete_temp) {
            $tempZipFile = glob($archive_zip_temppath);

            if ($tempZipFile !== false) {
                array_map('unlink', $tempZipFile);
            }
        }
    }

    public static function get_from_json($json_string)
    {
        $stdobject = json_decode($json_string);

        $package = new DUP_PRO_Package();
        DUP_PRO_U::objectCopy($stdobject, $package);

        $package->Archive = new DUP_PRO_Archive($package);
        DUP_PRO_U::objectCopy($stdobject->Archive, $package->Archive);
        DUP_PRO_U::objectCopy($stdobject->Archive->FilterInfo->Dirs, $package->Archive->FilterInfo->Dirs);
        DUP_PRO_U::objectCopy($stdobject->Archive->FilterInfo->Exts, $package->Archive->FilterInfo->Exts);
        DUP_PRO_U::objectCopy($stdobject->Archive->FilterInfo->Files, $package->Archive->FilterInfo->Files);

        $package->Installer = new DUP_PRO_Installer($package);
        DUP_PRO_U::objectCopy($stdobject->Installer, $package->Installer);

        $package->Database = new DUP_PRO_Database($package);
        DUP_PRO_U::objectCopy($stdobject->Database, $package->Database);

        $package->upload_infos = array();
        DUP_PRO_U::objectArrayCopy($stdobject->upload_infos, $package->upload_infos, 'DUP_PRO_Package_Upload_Info');

        $package->build_progress = new DUP_PRO_Build_Progress();
        DUP_PRO_U::objectCopy($stdobject->build_progress, $package->build_progress);

        return $package;
    }

    public function contains_non_default_storage()
    {
        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->storage_id != DUP_PRO_Virtual_Storage_IDs::Default_Local) {
                $storage = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id);
                if ($storage != null) {
                    return true;
                } else {
                    DUP_PRO_LOG::traceError("Package refers to a storage provider that no longer exists - ".$upload_info->storage_id);
                }
            }
        }
        return false;
    }

    public function non_default_storage_count()
    {
        $count = 0;

        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->storage_id != DUP_PRO_Virtual_Storage_IDs::Default_Local) {
                $storage = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id);
                if ($storage != null) {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function contains_storage_type($storage_type)
    {
        foreach ($this->get_storages() as $storage) {
            if ($storage->storage_type == $storage_type) {
                return true;
            }
        }
        return false;
    }

    public function get_installer_filename()
    {
        $global = DUP_PRO_Global_Entity::get_instance();

        return "{$this->NameHash}_{$global->installer_base_name}";
    }

    public function get_archive_filename()
    {
        return $this->NameHash.'_archive.zip';
    }

    public function get_database_filename()
    {
        return $this->NameHash.'_database.sql';
    }

    public static function get_next_active_package()
    {
        $packages = self::get_all();

        if (count($packages) > 0) {
            foreach ($packages as $package) {
                if (($package->Status >= 0) && ($package->Status < 100)) {
                    return $package;
                }
            }
        }
        return null;
    }

    // Quickly determine without going through the overhead of creating package objects
    public static function is_active_package_present()
    {
        global $wpdb;

        $table = $wpdb->prefix."duplicator_pro_packages";
        $count = $wpdb->get_var("SELECT count(Status) FROM `{$table}` WHERE (Status >= 0 AND Status < 100)");

        return ($count > 0);
    }

    /**
     * Generates a scan report
     * @return array of scan results
     */
    public function create_scan_report()
    {
        DUP_PRO_LOG::trace('Scanning');

        try {
            $global = DUP_PRO_Global_Entity::get_instance();

            if (is_numeric($this->ID)) {
                $this->set_status(DUP_PRO_PackageStatus::SCANNING);
            }

            self::safe_tmp_cleanup();

            $start_scan_time = time();
            $timerStart      = DUP_PRO_U::getMicrotime();
            $report          = array();
            $this->ScanFile  = "{$this->NameHash}_scan.json";

            $report['RPT']['ScanTime'] = "0";
            $report['RPT']['ScanFile'] = $this->ScanFile;

            //SERVER
            $srv           = DUP_PRO_Server::getChecks($this);
            $report['SRV'] = $srv['SRV'];

            //FILES
            $this->Archive->run_scan_stats();
            $dirCount                   = count($this->Archive->Dirs);
            $fileCount                  = count($this->Archive->Files);
            $fullCount                  = $dirCount + $fileCount;
            //Formated
            $report['ARC']['Size']      = DUP_PRO_U::byteSize($this->Archive->Size) or "unknown";
            $report['ARC']['DirCount']  = number_format($dirCount);
            $report['ARC']['FileCount'] = number_format($fileCount);
            $report['ARC']['FullCount'] = number_format($fullCount);

            //Int Type
            $report['ARC']['USize']               = $this->Archive->Size;
            $report['ARC']['UDirCount']           = $dirCount;
            $report['ARC']['UFileCount']          = $fileCount;
            $report['ARC']['UFullCount']          = $fullCount;
            $report['ARC']['WarnFileCount']       = count($this->Archive->FilterInfo->Files->Warning);
            // RSR TODO NEW
            $report['ARC']['WarnDirCount']        = count($this->Archive->FilterInfo->Dirs->Warning);
            $report['ARC']['UnreadableDirCount']  = count($this->Archive->FilterInfo->Dirs->Unreadable);
            $report['ARC']['UnreadableFileCount'] = count($this->Archive->FilterInfo->Files->Unreadable);

            $report['ARC']['FilterInfo']['Dirs']  = $this->Archive->FilterInfo->Dirs;
            $report['ARC']['FilterInfo']['Files'] = $this->Archive->FilterInfo->Files;
            $report['ARC']['FilterInfo']['Exts']  = $this->Archive->FilterInfo->Exts;

            if ($global->archive_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
                $site_warning_size = DUPLICATOR_PRO_SCAN_SITE_SHELL_EXEC_WARNING_SIZE;
            } else {
                $site_warning_size = DUPLICATOR_PRO_SCAN_SITE_WARNING_SIZE;
            }

            $report['ARC']['Status']['Size'] = ($this->Archive->Size > $site_warning_size) ? 'Warn' : 'Good';

            if ($global->archive_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
                $name_check = 'Good';
            } else {
                $name_check = (count($this->Archive->FilterInfo->Files->Warning) + count($this->Archive->FilterInfo->Dirs->Warning)) ? 'Warn' : 'Good';
            }

            $report['ARC']['Status']['Names']      = $name_check;
            $report['ARC']['Status']['Big']        = count($this->Archive->FilterInfo->Files->Size) ? 'Warn' : 'Good';
            $report['ARC']['Status']['AddonSites'] = count($this->Archive->FilterInfo->Dirs->AddonSites) ? 'Warn' : 'Good';
            $report['ARC']['Dirs']                 = $this->Archive->Dirs;
            $report['ARC']['Files']                = $this->Archive->Files;

            //DATABASE
            $db                         = $this->Database->get_scan_data();
            $report['DB']['Status']     = $db['Status'];
            $report['DB']['Size']       = DUP_PRO_U::byteSize($db['Size']) or "unknown";
            $report['DB']['Rows']       = number_format($db['Rows']) or "unknown";
            $report['DB']['TableCount'] = $db['TableCount'] or "unknown";
            $report['DB']['TableList']  = $db['TableList'] or "unknown";

            $report['RPT']['ScanCreated'] = @date("Y-m-d H:i:s");
            $report['RPT']['ScanTime']    = DUP_PRO_U::elapsedTime(DUP_PRO_U::getMicrotime(), $timerStart);
            $report['RPT']['ScanPath']    = DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$this->ScanFile}";
            $report['RPT']['ScanFile']    = $this->ScanFile;


            //Pass = 1;  Warn = 2; Fail = 3;
            $report['Status'] = 1;
            $fp               = fopen($report['RPT']['ScanPath'], 'w');

            if ($global->json_mode == DUP_PRO_JSON_Mode::PHP) {
                DUP_PRO_LOG::trace("Doing PHP JSON encode");
                $json = DUP_PRO_JSON_U::encode($report);
            } else {
                DUP_PRO_LOG::trace("Doing Custom JSON encode");
                $json = DUP_PRO_JSON_U::customEncode($report);
            }

            fwrite($fp, $json);
            fclose($fp);

            //Safe to clear at this point only JSON
            //report stores the full directory and file lists
            $this->Archive->Dirs  = null;
            $this->Archive->Files = null;

            $total_scan_time = time() - $start_scan_time;
            DUP_PRO_LOG::trace("TOTAL SCAN TIME = $total_scan_time");
        } catch (Exception $ex) {

            DUP_PRO_LOG::trace("SCAN ERROR: ".$ex->getMessage());
            DUP_PRO_LOG::trace("SCAN ERROR: ".$ex->getTraceAsString());
            DUP_PRO_Log::error("An error has occurred scanning the file system.", $ex->getMessage());
        }

        return $report;
    }

    public function save()
    {
        global $wpdb;


        if ($this->ID == -1 || empty($this->ID)) {
            /* @var $global DUP_PRO_Global_Entity */
            $global = DUP_PRO_Global_Entity::get_instance();

            $global->adjust_settings_for_system();

            $this->build_progress->set_build_mode();

            $packageObj = json_encode($this);

            $results = $wpdb->insert($wpdb->prefix."duplicator_pro_packages",
                array(
                'name' => $this->Name,
                'hash' => $this->Hash,
                'status' => DUP_PRO_PackageStatus::START,
                'created' => current_time('mysql'/* , get_option('gmt_offset', 1) */),
                'owner' => isset($current_user->user_login) ? $current_user->user_login : 'unknown',
                'package' => $packageObj)
            );

            if ($results === false) {
                DUP_PRO_LOG::trace("Problem inserting package: {$wpdb->last_error}");

                DUP_PRO_Log::error("Duplicator is unable to insert a package record into the database table.", "'{$wpdb->last_error}'");
            } else {
                DUP_PRO_LOG::trace("inserted properly now saving $wpdb->insert_id");
                $this->ID = $wpdb->insert_id;
                $this->update();
            }
        } else {
            $this->update();
        }
    }

    /**
     * Starts the package build process
     * @return DUP_PRO_Package
     */
    public function run_build()
    {
        DUP_PRO_LOG::trace('Main build step');

        global $wp_version;
        global $wpdb;
        global $current_user;

        //START LOGGING
        DUP_PRO_Log::open($this->NameHash);

        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        $this->build_progress->start_timer();

        if ($this->build_progress->initialized == false) {
            $this->timer_start      = DUP_PRO_U::getMicrotime();
            $this->Archive->File    = "{$this->NameHash}_archive.zip";
            $this->Installer->File  = "{$this->NameHash}_{$global->installer_base_name}";
            $this->Database->File   = "{$this->NameHash}_database.sql";
            $this->Database->DBMode = ($global->package_mysqldump) ? 'MYSQLDUMP' : 'PHP';
            $this->ZipMode          = $global->archive_build_mode;
            $this->ziparchive_mode  = $global->ziparchive_mode;

            $php_max_time   = @ini_get("max_execution_time");
            $php_max_memory = @ini_set('memory_limit', DUPLICATOR_PRO_PHP_MAX_MEMORY);
            $php_max_time   = ($php_max_time == 0) ? "(0) no time limit imposed" : "[{$php_max_time}] not allowed";
            $php_max_memory = ($php_max_memory === false) ? "Unable to set php memory_limit" : DUPLICATOR_PRO_PHP_MAX_MEMORY." ({$php_max_memory} default)";

            $info = "********************************************************************************\n";
            $info .= "DUPLICATOR PRO PACKAGE-LOG: ".@date("Y-m-d H:i:s")."\n";
            $info .= "NOTICE: Do NOT post to public sites or forums \n";
            $info .= "********************************************************************************\n";
            $info .= "VERSION:\t".DUPLICATOR_PRO_VERSION."\n";
            $info .= "WORDPRESS:\t{$wp_version}\n";
            $info .= "PHP INFO:\t".phpversion().' | '.'SAPI: '.php_sapi_name()."\n";
            $info .= "SERVER:\t\t{$_SERVER['SERVER_SOFTWARE']} \n";
            $info .= "PHP TIME LIMIT: {$php_max_time} \n";
            $info .= "PHP MAX MEMORY: {$php_max_memory} \n";
            $info .= "RUN TYPE: ".$this->get_type_string()."\n";
            $info .= "MEMORY STACK: ".DUP_PRO_Server::getPHPMemory();

            DUP_PRO_Log::info($info);
            $info = null;

            //CREATE DB RECORD
            $this->build_progress->set_build_mode();
            $packageObj = json_encode($this);

            if (!$packageObj) {
                DUP_PRO_Log::error("Unable to serialize pacakge object while building record.");
            }

            $this->ID = $this->find_hash_key($this->Hash);
            if ($this->ID != 0) {
                DUP_PRO_LOG::trace("ID non zero so setting to start");
                $this->set_status(DUP_PRO_PackageStatus::START);
            } else {
                DUP_PRO_LOG::trace("ID IS zero so creating another package");
                $results = $wpdb->insert($wpdb->prefix."duplicator_pro_packages",
                    array(
                    'name' => $this->Name,
                    'hash' => $this->Hash,
                    'status' => DUP_PRO_PackageStatus::START,
                    'created' => current_time('mysql'/* , get_option('gmt_offset', 1) */),
                    'owner' => isset($current_user->user_login) ? $current_user->user_login : 'unknown',
                    'package' => $packageObj)
                );
                if ($results === false) {
                    DUP_PRO_LOG::trace("Problem inserting package: {$wpdb->last_error}");

                    DUP_PRO_Log::error("Duplicator is unable to insert a package record into the database table.", "'{$wpdb->last_error}'");
                }
                $this->ID = $wpdb->insert_id;
            }

            $this->build_progress->initialized = true;
            $this->update();
        }

        // At one point having this as an else as not part of the main logic prevented failure emails from getting sent.
        // Note2: Think that by putting has_completed() at top of check will prevent archive from continuing to build after a failure has hit.
        if ($this->build_progress->has_completed()) {
            $schedule = DUP_PRO_Schedule_Entity::get_by_id($this->schedule_id);

            DUP_PRO_Log::info("\n********************************************************************************");
            DUP_PRO_Log::info("STORAGE:");
            DUP_PRO_Log::info("********************************************************************************");
            foreach ($this->upload_infos as $upload_info) {
                $storage             = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id);
                $storage_type_string = strtoupper($storage->get_storage_type_string());
                $storage_path        = $storage->get_storage_location_string();
                DUP_PRO_Log::info("$storage_type_string: $storage->name, $storage_path");
            }

            if (!$this->build_progress->failed) {
                // Only makees sense to perform build integrity check on completed archives
                $this->build_integrity_check();
            }

            $timerEnd      = DUP_PRO_U::getMicrotime();
            $timerSum      = DUP_PRO_U::elapsedTime($timerEnd, $this->timer_start);
            $this->Runtime = $timerSum;

            //FINAL REPORT
            $info = "\n********************************************************************************\n";
            $info .= "RECORD ID:[{$this->ID}]\n";
            $info .= "TOTAL PROCESS RUNTIME: {$timerSum}\n";
            $info .= "PEAK PHP MEMORY USED: ".DUP_PRO_Server::getPHPMemory(true)."\n";
            $info .= "DONE PROCESSING => {$this->Name} ".@date("Y-m-d H:i:s")."\n";

            DUP_PRO_Log::info($info);
            DUP_PRO_LOG::trace("Done package building");

            if ($this->build_progress->failed) {
                if ($schedule != null) {
                    $schedule->times_run++;
                    $schedule->last_run_time   = time();
                    $schedule->last_run_status = DUP_PRO_Package_Build_Outcome::FAILURE;
                    $schedule->save();
                    $this->send_build_email(0, false);
                }

                $this->set_status(DUP_PRO_PackageStatus::ERROR);

                $message = "Package creation failed.";
                DUP_PRO_Log::error($message);
                DUP_PRO_LOG::trace($message);
            } else {
                if ($schedule != null) {
                    $schedule->times_run++;
                    $schedule->last_run_time   = time();
                    $schedule->last_run_status = DUP_PRO_Package_Build_Outcome::SUCCESS;
                    $schedule->save();
                    // don't send build email for success - rely on storage phase to handle that
                }

                //File Cleanup
                $this->build_cleanup();
            }
        }
        //START BUILD
        //PHPs serialze method will return the object, but the ID above is not passed
        //for one reason or another so passing the object back in seems to do the trick
        else if (!$this->build_progress->database_script_built) {
            $this->Database->build($this);
            $this->build_progress->database_script_built = true;
            $this->update();
            DUP_PRO_LOG::trace("Set db built for package $this->ID");
        } else if (!$this->build_progress->archive_built) { 
            $this->Archive->build($this, $this->build_progress);
            $this->update();
        } else if (!$this->build_progress->installer_built) {
            $this->Installer->build($this, $this->build_progress);
            $this->update();

            if ($this->build_progress->failed) {
                $this->set_status(DUP_PRO_PackageStatus::ERROR);
                DUP_PRO_Log::error('ERROR: Problem adding installer to archive.');
            }
        }


        DUP_PRO_Log::close();
        return $this;
    }

    public function build_integrity_check()
    {
        //INTEGRITY CHECKS
        //We should not rely on data set in the serlized object, we need to manually check each value
        //indepentantly to have a true integrity check.
        DUP_PRO_Log::info("\n********************************************************************************");
        DUP_PRO_Log::info("INTEGRITY CHECKS:");
        DUP_PRO_Log::info("********************************************************************************");

        //------------------------
        //SQL CHECK:  File should be at minium 5K.  A base WP install with only Create tables is about 9K
        $sql_temp_path = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP.'/'.$this->Database->File);
        $sql_temp_size = @filesize($sql_temp_path);
        $sql_easy_size = DUP_PRO_U::byteSize($sql_temp_size);
        $sql_done_txt  = DUP_PRO_U::tailFile($sql_temp_path, 3);
        if (!strstr($sql_done_txt, 'DUPLICATOR_PRO_MYSQLDUMP_EOF') || $sql_temp_size < 5120) {
            $this->build_progress->failed = true;
            $this->update();
            $this->set_status(DUP_PRO_PackageStatus::ERROR);

            $error_text = "ERROR: SQL file not complete.  The file looks too small ($sql_temp_size bytes) or the end of file marker was not found.";

            if ($this->Database->DBMode == 'MYSQLDUMP') {
                $fix_text = DUP_PRO_U::__('Go to: Settings > Packages Tab Database to PHP.');
            } else {
                $fix_text .= DUP_PRO_U::__('Go to: Settings > Packages Tab > Database to mySQLDump.');
            }

            $system_global = DUP_PRO_System_Global_Entity::get_instance();
            $system_global->add_recommended_text_fix($error_text, $fix_text);

            $system_global->save();

            DUP_PRO_Log::error("$error_text  **RECOMMENDATION: $fix_text", '', false);

            return;
        }
        DUP_PRO_Log::info("SQL FILE: {$sql_easy_size}");

        //------------------------
        //INSTALLER CHECK:
        $exe_temp_path = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH_TMP.'/'.$this->Installer->File);
        $exe_temp_size = @filesize($exe_temp_path);
        $exe_easy_size = DUP_PRO_U::byteSize($exe_temp_size);
        $exe_done_txt  = DUP_PRO_U::tailFile($exe_temp_path, 10);

        if (!strstr($exe_done_txt, 'DUPLICATOR_PRO_INSTALLER_EOF') && !$this->build_progress->failed) {
            $this->build_progress->failed = true;
            $this->update();
            $this->set_status(DUP_PRO_PackageStatus::ERROR);
            DUP_PRO_Log::error("ERROR: Installer file not complete.  The end of file marker was not found.  Please try to re-create the package.", '', false);
            return;
        }
        DUP_PRO_Log::info("INSTALLER FILE: {$exe_easy_size}");

        /* @var $global DUP_PRO_Global_Entity */
        $global = DUP_PRO_Global_Entity::get_instance();

        //------------------------
        //ARCHIVE CHECK:
        // Only performs check if we were able to obtain the count
        DUP_PRO_LOG::trace("Archive file count is ".$this->Archive->file_count);

        if ($this->Archive->file_count != -1) {
            $zip_easy_size = DUP_PRO_U::byteSize($this->Archive->Size);
            if (!($this->Archive->Size)) {
                $this->build_progress->failed = true;
                $this->update();
                $this->set_status(DUP_PRO_PackageStatus::ERROR);
                DUP_PRO_Log::error("ERROR: The archive file contains no size.", "Archive Size: {$zip_easy_size}", false);
                return;
            }

            $scan_filepath = DUPLICATOR_PRO_SSDIR_PATH_TMP."/{$this->NameHash}_scan.json";

            $json = '';

            DUP_PRO_LOG::trace("***********Does $scan_filepath exist?");
            if (file_exists($scan_filepath)) {
                $json = file_get_contents($scan_filepath);
            } else {
                $error_message = sprintf(DUP_PRO_U::__("Can't find Scanfile %s. Please ensure there no non-English characters in the package or schedule name."), $scan_filepath);

                $this->build_progress->failed = true;
                $this->set_status(DUP_PRO_PackageStatus::ERROR);
                $this->update();


                DUP_PRO_Log::error($error_message, '', false);
                return;
            }

            $scanReport         = json_decode($json);
            $expected_filecount = $scanReport->ARC->UDirCount + $scanReport->ARC->UFileCount;

            DUP_PRO_Log::info("ARCHIVE FILE: {$zip_easy_size} ");
            DUP_PRO_Log::info(sprintf(DUP_PRO_U::__('EXPECTED FILE/DIRECTORY COUNT: %1$s'), number_format($expected_filecount)));
            DUP_PRO_Log::info(sprintf(DUP_PRO_U::__('ACTUAL FILE/DIRECTORY COUNT: %1$s'), number_format($this->Archive->file_count)));

            $this->ExeSize = $exe_easy_size;
            $this->ZipSize = $zip_easy_size;

            /* ------- ZIP Filecount Check -------- */
            // Any zip of over 500 files should be within 2% - this is probably too loose but it will catch gross errors
            DUP_PRO_LOG::trace("Expected filecount = $expected_filecount and archive filecount=".$this->Archive->file_count);

            if ($expected_filecount > 500) {
                $straight_ratio = (float) $expected_filecount / (float) $this->Archive->file_count;

                // RSR NEW
                $warning_count = $scanReport->ARC->WarnFileCount + $scanReport->ARC->WarnDirCount + $scanReport->ARC->UnreadableFileCount + $scanReport->ARC->UnreadableDirCount;

                DUP_PRO_LOG::trace("Warn/unread counts) warnfile:{$scanReport->ARC->WarnFileCount} warndir:{$scanReport->ARC->WarnDirCount} unreadfile:{$scanReport->ARC->UnreadableFileCount} unreaddir:{$scanReport->ARC->UnreadableDirCount}");

                $warning_ratio = ((float) ($expected_filecount + $warning_count)) / (float) $this->Archive->file_count;

                DUP_PRO_LOG::trace("Straight ratio is $straight_ratio and warning ratio is $warning_ratio. # Expected=$expected_filecount # Warning=$warning_count and #Archive File {$this->Archive->file_count}");

                // Allow the real file count to exceed the expected by 10% but only allow 1% the other way
                if (($straight_ratio < 0.90) || ($straight_ratio > 1.01)) {
                    // Has to exceed both the straight as well as the warning ratios
                    if (($warning_ratio < 0.90) || ($warning_ratio > 1.01)) {
                        $this->build_progress->failed = true;
                        $this->update();
                        $this->set_status(DUP_PRO_PackageStatus::ERROR);

                        $zip_file_count = $this->Archive->file_count;

                        $error_message = sprintf('ERROR: File count in archive vs expected suggests a bad archive (%1$d vs %2$d).', $zip_file_count, $expected_filecount);

                        if ($this->ZipMode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
                            $fix_text = "Go to: Settings > Packages Tab > Archive Engine to ZipArchive.";

                            $system_global = DUP_PRO_System_Global_Entity::get_instance();
                            $system_global->add_recommended_text_fix($error_message, $fix_text);
                            $system_global->save();

                            $error_message .= DUP_PRO_U::__(" **RECOMMENDATION: $fix_text");
                        }

                        DUP_PRO_LOG::trace($error_message);
                        DUP_PRO_Log::error($error_message, '', false);
                        return;
                    }
                }
            }
        }

        /* ------ ZIP CONSISTENCY CHECK ------ */
        if (class_exists('ZipArchive') && ($this->build_progress->current_build_mode == DUP_PRO_Archive_Build_Mode::ZipArchive)) {
            DUP_PRO_LOG::trace("Running ZipArchive consistency check");
            $zipPath = DUP_PRO_U::safePath("{$this->StorePath}/{$this->Archive->File}");

            $zip = new ZipArchive();

            // ZipArchive::CHECKCONS will enforce additional consistency checks
            $res = $zip->open($zipPath, ZipArchive::CHECKCONS);

            if ($res !== TRUE) {
                $consistency_error = sprintf(DUP_PRO_U::__('ERROR: Cannot open created archive. Error code = %1$s'), $res);

                DUP_PRO_LOG::trace($consistency_error);
                switch ($res) {
                    case ZipArchive::ER_NOZIP :
                        $consistency_error = DUP_PRO_U::__('ERROR: Archive is not valid zip archive.');
                        break;

                    case ZipArchive::ER_INCONS :
                        $consistency_error = DUP_PRO_U::__("ERROR: Archive doesn't pass consistency check.");
                        break;


                    case ZipArchive::ER_CRC :
                        $consistency_error = DUP_PRO_U::__("ERROR: Archive checksum is bad.");
                        break;
                }

                $this->build_progress->failed = true;
                $this->update();
                $this->set_status(DUP_PRO_PackageStatus::ERROR);

                DUP_PRO_LOG::trace($consistency_error);
                DUP_PRO_Log::error($consistency_error);
            } else {
                DUP_PRO_Log::info(__('ARCHIVE CONSISTENCY TEST: Pass'));
                DUP_PRO_LOG::trace("Zip for package $this->ID passed consistency test");
            }

            $zip->close();
        }
    }

    public function send_build_failure_email()
    {
        $this->send_build_email(0, false);
    }

    // $stage = 0 for build, 1 = storage
    public function send_build_email($stage, $success)
    {
        $schedule = DUP_PRO_Schedule_Entity::get_by_id($this->schedule_id);

        if ($schedule != null) {
            $global = DUP_PRO_Global_Entity::get_instance();

            if (($global->send_email_on_build_mode === DUP_PRO_Email_Build_Mode::Email_On_All_Builds) ||
                (($global->send_email_on_build_mode === DUP_PRO_Email_Build_Mode::Email_On_Failure) && ($success === false))) {
                DUP_PRO_LOG::trace('Sending build notification email');

                $to = $global->notification_email_address;

                if (empty($to)) {
                    $to = get_option('admin_email');

                    DUP_PRO_LOG::trace("Email address not defined so using admin email ($to)");
                }

                DUP_PRO_LOG::trace("Attempting to send build notification to $to");

                if (empty($to) === false) {
                    if ($success) {
                        //$subject = get_option('blogname') . DUP_PRO_U::__(' Backup Success');
                        $subject = sprintf(DUP_PRO_U::__('Backup of %1$s Succeeded'), home_url());
                        $message = DUP_PRO_U::__('BACKUP SUCCEEDED');
                    } else {
                        //$subject = get_option('blogname') . DUP_PRO_U::__(' Backup Failed');
                        $subject = sprintf(DUP_PRO_U::__('Backup of %1$s Failed'), home_url());
                        $message = DUP_PRO_U::__('BACKUP FAILED').' ';

                        if ($stage == 0) {
                            $message .= DUP_PRO_U::__('DURING BUILD PHASE');
                        } else {
                            $message .= DUP_PRO_U::__('DURING STORAGE PHASE. CHECK SITE FOR DETAILS.');
                        }
                        $message .= '</strong>';
                    }

                    $message .= "<br/><br/>";

                    $message .= '<strong>'.DUP_PRO_U::__('Package').': </strong>'."$this->Name (ID = $this->ID)";
                    $message .= '<br/>';

                    $message .= '<strong>'.DUP_PRO_U::__('Time').': </strong>'.DUP_PRO_DATE::getlocalTimeInFormat('Y-m-d H:i:s');
                    $message .= '<br/>';

                    $message .= '<strong>'.DUP_PRO_U::__('Schedule').': </strong>'.$schedule->name;

                    $log_filepath = $this->get_safe_log_filepath();

                    if (file_exists($log_filepath)) {
                        $attachments = $log_filepath;
                        $message .= '<br/><br/>'.DUP_PRO_U::__('Log is attached.');
                    } else {
                        DUP_PRO_LOG::trace("Attempted to attach the log for build of package $this->ID but it was missing.");
                        $attachments = '';
                    }

                    if (wp_mail($to, $subject, $message, array('Content-Type: text/html; charset=UTF-8'), $attachments)) {
                        // ok
                        DUP_PRO_LOG::trace('wp_mail reporting send success');
                    } else {
                        DUP_PRO_LOG::trace("Problem sending build notification to $to regarding package $this->ID");
                    }
                } else {
                    DUP_PRO_LOG::trace("Would normally send a build notification but admin email is empty.");
                }
            }
        }
    }

    public function get_type_string()
    {
        switch ($this->Type) {
            case DUP_PRO_PackageType::MANUAL:
                if ($this->template_id != -1) {
                    $template = DUP_PRO_Package_Template_Entity::get_by_id($this->template_id);

                    if (!$template->is_manual) {
                        return DUP_PRO_U::__('Template')." ($template->name}";
                    }
                }

                return DUP_PRO_U::__('Manual');
                break;

            case DUP_PRO_PackageType::SCHEDULED:

                return DUP_PRO_U::__('Schedule');
                break;

            case DUP_PRO_PackageType::RUN_NOW:
                return DUP_PRO_U::__('Schedule (Run Now)');
                break;

            default:
                return DUP_PRO_U::__('Unknown');
        }
    }

    public function get_active_storage()
    {
        if ($this->active_storage_id != -1) {
            
            $storage = DUP_PRO_Storage_Entity::get_by_id($this->active_storage_id);

            if($storage === null)
            {
                DUP_PRO_LOG::traceError("Active storage for package {$this->ID} is {$this->active_storage_id} but it's coming back null so resetting.");
                $this->active_storage_id = -1;
                $this->save();
            }

            return $storage;
        } else {
            
            return null;
        }
    }

    public function get_storages($include_virtual = true)
    {
        $storages = array();

        foreach ($this->upload_infos as $upload_info) {
            if ($upload_info->storage_id > 0) {
                $storage = DUP_PRO_Storage_Entity::get_by_id($upload_info->storage_id);
                array_push($storages, $storage);
            } else {
                if ($include_virtual) {
                    if ($upload_info->storage_id == DUP_PRO_Virtual_Storage_IDs::Default_Local) {
                        $storage                       = new DUP_PRO_Storage_Entity();
                        $storage->name                 = DUP_PRO_U::__('Default');
                        $storage->storage_type         = DUP_PRO_Storage_Types::Local;
                        $storage->id                   = DUP_PRO_Virtual_Storage_IDs::Default_Local;
                        $storage->local_storage_folder = DUPLICATOR_PRO_SSDIR_PATH;

                        array_push($storages, $storage);
                    }
                }
            }
        }

        return $storages;
    }

    // Used when we already have a package object that we need to make active
    public function set_temporary_package()
    {
        $json_package = json_encode($this);
        update_option(self::OPT_ACTIVE, $json_package);
    }

    /**
     *  Saves the active options associted with the active(latest) package.
     *  @param $_POST $post The Post server object
     *  @see DUP_PRO_Package::GetActive
     *  @return void */
    public static function set_manual_template_from_post($post = null)
    {
        if (isset($post)) {
            $post = stripslashes_deep($post);

            $mtemplate    = DUP_PRO_Package_Template_Entity::get_manual_template();
            $filter_dirs  = isset($post['filter-dirs']) ? DUP_PRO_Package::parse_directory_filter($post['filter-dirs']) : '';
            $filter_exts  = isset($post['filter-exts']) ? DUP_PRO_Package::parse_extension_filter($post['filter-exts']) : '';
            $filter_files = isset($post['filter-files']) ? DUP_PRO_Package::parse_file_filter($post['filter-files']) : '';
            $tablelist    = isset($post['dbtables']) ? implode(',', $post['dbtables']) : '';
            $compatlist   = isset($post['dbcompat']) ? implode(',', $post['dbcompat']) : '';

            //PACKAGE
            $mtemplate->notes = esc_html($post['package-notes']);

            //ARCHIVE
            $mtemplate->archive_filter_on    = isset($post['filter-on']) ? 1 : 0;
            $mtemplate->archive_filter_dirs  = esc_html($filter_dirs);
            $mtemplate->archive_filter_exts  = str_replace(array('.', ' '), "", esc_html($filter_exts));
            $mtemplate->archive_filter_files = esc_html($filter_files);

            //INSTALLER
            $mtemplate->installer_opts_secure_on   = isset($post['secure-on']) ? 1 : 0;
            $mtemplate->installer_opts_secure_pass = base64_encode(esc_html($post['secure-pass']));

            $mtemplate->installer_opts_skip_scan      = isset($post['skipscan']) ? 1 : 0;
            //cPanel
            $mtemplate->installer_opts_cpnl_enable    = isset($post['cpnl-enable']) ? 1 : 0;
            $mtemplate->installer_opts_cpnl_host      = esc_html($post['cpnl-host']);
            $mtemplate->installer_opts_cpnl_user      = esc_html($post['cpnl-user']);
            $mtemplate->installer_opts_cpnl_db_action = esc_html($post['cpnl-dbaction']);
            $mtemplate->installer_opts_cpnl_db_host   = esc_html($post['cpnl-dbhost']);
            $mtemplate->installer_opts_cpnl_db_name   = esc_html($post['cpnl-dbname']);
            $mtemplate->installer_opts_cpnl_db_user   = esc_html($post['cpnl-dbuser']);
            //Basic
            $mtemplate->installer_opts_db_host        = esc_html($post['dbhost']);
            $mtemplate->installer_opts_db_name        = esc_html($post['dbname']);
            $mtemplate->installer_opts_db_user        = esc_html($post['dbuser']);
            //Advanced
            $mtemplate->installer_opts_ssl_admin      = isset($post['ssl-admin']) ? 1 : 0;
            $mtemplate->installer_opts_ssl_login      = isset($post['ssl-login']) ? 1 : 0;
            $mtemplate->installer_opts_cache_wp       = isset($post['cache-wp']) ? 1 : 0;
            $mtemplate->installer_opts_cache_path     = isset($post['cache-path']) ? 1 : 0;
            $mtemplate->installer_opts_url_new        = esc_html($post['url-new']);

            //DATABASE
            $mtemplate->database_filter_on           = isset($post['dbfilter-on']) ? 1 : 0;
            $mtemplate->database_filter_tables       = esc_html($tablelist);
            $mtemplate->database_compatibility_modes = $compatlist;

            $mtemplate->save();
        }
    }

    public static function set_temporary_package_from_template_and_storages($template_id, $storage_ids, $name)
    {
        global $wp_version;

        // Use the manual template for the data while the $template_id is used just to record where it originally came from
        $manual_template = DUP_PRO_Package_Template_Entity::get_manual_template();

        if ($manual_template != null) {
            $package    = new DUP_PRO_Package();
            $dbversion  = DUP_PRO_DB::getVersion();
            $dbversion  = is_null($dbversion) ? '- unknown -' : $dbversion;
            $dbcomments = DUP_PRO_DB::getVariable('version_comment');
            $dbcomments = is_null($dbcomments) ? '- unknown -' : $dbcomments;

            //PACKAGE
            $package->Created     = date("Y-m-d H:i:s");
            $package->Version     = DUPLICATOR_PRO_VERSION;
            $package->VersionOS   = defined('PHP_OS') ? PHP_OS : 'unknown';
            $package->VersionWP   = $wp_version;
            $package->VersionPHP  = phpversion();
            $package->VersionDB   = $dbversion;
            $package->Name        = $name;
            $package->Hash        = $package->make_hash();
            $package->NameHash    = "{$package->Name}_{$package->Hash}";
            $package->Notes       = $manual_template->notes;
            $package->Type        = DUP_PRO_PackageType::MANUAL;
            $package->Status      = DUP_PRO_PackageStatus::PRE_PROCESS;
            $package->schedule_id = -1;
            $package->template_id = $template_id;

            //ARCHIVE
            $package->Archive->PackDir     = rtrim(DUPLICATOR_PRO_WPROOTPATH, '/');
            $package->Archive->Format      = 'ZIP';
            $package->Archive->FilterOn    = $manual_template->archive_filter_on;
            $package->Archive->FilterDirs  = $manual_template->archive_filter_dirs;
            $package->Archive->FilterExts  = $manual_template->archive_filter_exts;
            $package->Archive->FilterFiles = $manual_template->archive_filter_files;

            //INSTALLER
            $package->Installer->OptsDBHost     = $manual_template->installer_opts_db_host;
            $package->Installer->OptsDBName     = $manual_template->installer_opts_db_name;
            $package->Installer->OptsDBUser     = $manual_template->installer_opts_db_user;
            $package->Installer->OptsSSLAdmin   = $manual_template->installer_opts_ssl_admin;
            $package->Installer->OptsSSLLogin   = $manual_template->installer_opts_ssl_login;
            $package->Installer->OptsCacheWP    = $manual_template->installer_opts_cache_wp;
            $package->Installer->OptsCachePath  = $manual_template->installer_opts_cache_path;
            $package->Installer->OptsURLNew     = $manual_template->installer_opts_url_new;
            $package->Installer->OptsSecureOn   = $manual_template->installer_opts_secure_on;
            $package->Installer->OptsSecurePass = $manual_template->installer_opts_secure_pass;
            $package->Installer->OptsSkipScan   = $manual_template->installer_opts_skip_scan;

            //cPanel
            $package->Installer->OptsCPNLEnable   = $manual_template->installer_opts_cpnl_enable;
            $package->Installer->OptsCPNLHost     = $manual_template->installer_opts_cpnl_host;
            $package->Installer->OptsCPNLUser     = $manual_template->installer_opts_cpnl_user;
            $package->Installer->OptsCPNLDBAction = $manual_template->installer_opts_cpnl_db_action;
            $package->Installer->OptsCPNLDBHost   = $manual_template->installer_opts_cpnl_db_host;
            $package->Installer->OptsCPNLDBName   = $manual_template->installer_opts_cpnl_db_name;
            $package->Installer->OptsCPNLDBUser   = $manual_template->installer_opts_cpnl_db_user;

            //DATABASE
            $package->Database->FilterOn     = $manual_template->database_filter_on;
            $package->Database->FilterTables = $manual_template->database_filter_tables;
            $package->Database->Compatible   = $manual_template->database_compatibility_modes;
            $package->Database->Comments     = $dbcomments;

            $package->add_upload_infos($storage_ids);
            $json_package = json_encode($package);

            /* @var $system_global DUP_PRO_System_Global_Entity */
            $system_global = DUP_PRO_System_Global_Entity::get_instance();

            $system_global->clear_recommended_fixes();
            $system_global->save();

            update_option(self::OPT_ACTIVE, $json_package);
        } else {
            DUP_PRO_LOG::trace('Template '.$manual_template->id."doesn't exist!");
        }
    }

    public static function delete_temporary_package()
    {
        delete_option(self::OPT_ACTIVE);
    }

    /**
     *  Save any property of this class through reflection
     *  @param $property A valid public property in this class
     *  @param $value	 The value for the new dynamic property
     *  @return void */
    public static function set_temporary_package_member($property, $value)
    {
        $package         = self::get_temporary_package();
        $reflectionClass = new ReflectionClass($package);
        $reflectionClass->getProperty($property)->setValue($package, $value);
        $json_package    = json_encode($package);

        update_option(self::OPT_ACTIVE, $json_package);
    }

    /**
     *  Sets the status to log the state of the build
     *  @param $status The status level for where the package is
     *  @return void */
    public function set_status($status)
    {
        global $wpdb;
        $this->Status = $status;
        $packageObj   = json_encode($this);

        if (!isset($status)) {
            DUP_PRO_Log::error("Package SetStatus did not receive a proper code.");
        }

        if (!$packageObj) {
            DUP_PRO_Log::error("Package SetStatus was unable to serialize package object while updating record.");
        }

        $wpdb->flush();
        $table = $wpdb->prefix."duplicator_pro_packages";

        // Have to escape the serialized package object b/c can contain characters that screwn up the SQL.
        $packageObj = esc_sql($packageObj);

        // getting a timeout on this massive set...
        $sql = "UPDATE `{$table}` SET  status = {$status}, package = '$packageObj' WHERE ID = {$this->ID}";

        $wpdb->query($sql);
    }

    public function update()
    {
        global $wpdb;
        $packageObj = json_encode($this);

        if (!$packageObj) {
            DUP_PRO_Log::error("Package SetStatus was unable to serialize package object while updating record.");
        }

        $wpdb->flush();
        $table = $wpdb->prefix."duplicator_pro_packages";

        // Have to escape the serialized package object b/c can contain characters that screwn up the SQL.
        $packageObj = esc_sql($packageObj);

        // getting a timeout on this massive set...
        $sql = "UPDATE `{$table}` SET  status = {$this->Status}, package = '$packageObj' WHERE ID = {$this->ID}";

        $wpdb->query($sql);
    }

    /**
     * Does a hash already exisit
     * @return int Returns 0 if no has is found, if found returns the table ID
     */
    public function find_hash_key($hash)
    {
        global $wpdb;

        $table = $wpdb->prefix."duplicator_pro_packages";
        $qry   = $wpdb->get_row("SELECT ID, hash FROM `{$table}` WHERE hash = '{$hash}'");
        if (strlen($qry->hash) == 0) {
            return 0;
        } else {
            return $qry->ID;
        }
    }

    /**
     *  Makes the hashkey for the package files
     *  @return string A unique hashkey */
    public function make_hash()
    {
        $hash = uniqid().'_'.date("YmdHis");
        return $hash;
    }

    /**
     * Gets the active package.  The active package is defined as the package that was lasted saved.
     * Do to cache issues with the built in WP function get_option moved call to a direct DB call.
     * @see DUP_PRO_Package::SaveActive
     * @return DUP_PRO_Package
     */
    public static function get_temporary_package($create_if_not_exists = true)
    {

        global $wpdb;
        $obj = new DUP_PRO_Package();
        $row = $wpdb->get_row($wpdb->prepare("SELECT option_value FROM `{$wpdb->options}` WHERE option_name = %s LIMIT 1", self::OPT_ACTIVE));
        if (is_object($row)) {
            $obj = DUP_PRO_Package::get_from_json($row->option_value);

            return $obj;
        } else if ($create_if_not_exists) {
            return new DUP_PRO_Package();
        }
    }

    /**
     *  Creates a default name
     *  @return string   A default packagename
     */
    public static function get_default_name()
    {
        //Remove specail_chars from final result
        $special_chars = array(".", "-");
        $name          = sanitize_title(get_bloginfo('name', 'display'));
        $name          = substr(sanitize_file_name($name), 0, 40);
        $name          = str_replace($special_chars, '', $name);
        return $name;
    }

    public static function safe_tmp_cleanup($purge_temp_archives = false)
    {
        if ($purge_temp_archives) {
            $dir = DUPLICATOR_PRO_SSDIR_PATH_TMP."/*_archive.zip.*";
            foreach (glob($dir) as $file_path) {
                unlink($file_path);
            }
        } else {
            //Remove all temp files that are 24 hours old
            $dir = DUPLICATOR_PRO_SSDIR_PATH_TMP."/*";

            $files = glob($dir);

            if ($files !== false) {
                foreach ($files as $file_path) {
                    // Cut back to keeping things around for just an hour 15 min
                    if (filemtime($file_path) <= time() - DUP_PRO_Constants::TEMP_CLEANUP_SECONDS) {
                        unlink($file_path);
                    }
                }
            }
        }
    }

    /**
     *  Cleanup all tmp files
     *  @param all empty all contents
     *  @return void
     */
    public static function tmp_cleanup($all = false)
    {
        //Delete all files now
        if ($all) {
            $dir = DUPLICATOR_PRO_SSDIR_PATH_TMP."/*";
            foreach (glob($dir) as $file) {
                unlink($file);
            }
        }
        //Remove scan files that are 24 hours old
        else {
            $dir = DUPLICATOR_PRO_SSDIR_PATH_TMP."/*_scan.json";
            foreach (glob($dir) as $file) {
                if (filemtime($file) <= time() - DUP_PRO_Constants::TEMP_CLEANUP_SECONDS) {
                    unlink($file);
                }
            }
        }
    }

    private function build_cleanup()
    {
        $files   = DUP_PRO_IO::getFiles(DUPLICATOR_PRO_SSDIR_PATH_TMP);
        $newPath = DUPLICATOR_PRO_SSDIR_PATH;

        if (function_exists('rename')) {
            foreach ($files as $file) {
                $name = basename($file);
                if (strstr($name, $this->NameHash)) {
                    rename($file, "{$newPath}/{$name}");
                }
            }
        } else {
            foreach ($files as $file) {
                $name = basename($file);
                if (strstr($name, $this->NameHash)) {
                    copy($file, "{$newPath}/{$name}");
                    unlink($file);
                }
            }
        }

        $this->set_status(DUP_PRO_PackageStatus::COPIEDPACKAGE);
    }

    public static function parse_directory_filter($dirs = "")
    {
        $dirs        = str_replace(array("\n", "\t", "\r"), '', $dirs);
        $filter_dirs = "";
        $dir_array   = array_unique(explode(";", $dirs));
        foreach ($dir_array as $val) {
            if (strlen($val) >= 2) {
                $filter_dirs .= DUP_PRO_U::safePath(trim(rtrim($val, "/\\"))).";";
            }
        }
        return $filter_dirs;
    }

    public static function parse_extension_filter($extensions = "")
    {
        $filter_exts = "";
        if (strlen($extensions) >= 1 && $extensions != ";") {
            $filter_exts = str_replace(array(' ', '.'), '', $extensions);
            $filter_exts = str_replace(",", ";", $filter_exts);
            $filter_exts = DUP_PRO_STR::appendOnce($extensions, ";");
        }
        return $filter_exts;
    }

    public static function parse_file_filter($files = "")
    {
        $files        = str_replace(array("\n", "\t", "\r"), '', $files);
        $filter_files = "";
        $file_array   = array_unique(explode(";", $files));
        foreach ($file_array as $val) {
            if (strlen($val) >= 2) {
                $filter_files .= DUP_PRO_U::safePath(trim(rtrim($val, "/\\"))).";";
            }
        }

        return $filter_files;
    }

    /**
     *  Provides various date formats
     * 
     *  @param $date The date to format
     *  @param $format Various date formats to apply
     * 
     *  @return a formated date
     */
    public static function format_created_date($date, $format = 1)
    {
        $date = new DateTime($date);
        switch ($format) {
            //YEAR
            case 1: return $date->format('Y-m-d H:i');
                break;
            case 2: return $date->format('Y-m-d H:i:s');
                break;
            case 3: return $date->format('y-m-d H:i');
                break;
            case 4: return $date->format('y-m-d H:i:s');
                break;
            //MONTH
            case 5: return $date->format('m-d-Y H:i');
                break;
            case 6: return $date->format('m-d-Y H:i:s');
                break;
            case 7: return $date->format('m-d-y H:i');
                break;
            case 8: return $date->format('m-d-y H:i:s');
                break;
            //DAY
            case 9: return $date->format('d-m-Y H:i');
                break;
            case 10: return $date->format('d-m-Y H:i:s');
                break;
            case 11: return $date->format('d-m-y H:i');
                break;
            case 12: return $date->format('d-m-y H:i:s');
                break;
        }
    }
}

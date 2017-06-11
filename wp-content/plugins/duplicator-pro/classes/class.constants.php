<?php
/**
 * @copyright 2016 Snap Creek LLC
 */
class DUP_PRO_Constants
{
    const PLUGIN_SLUG = 'duplicator-pro';

    const DAYS_TO_RETAIN_DUMP_FILES = 1;
    const ZIPPED_LOG_FILENAME = 'duplicator_pro_log.zip';
    const ZIP_MAX_FILE_DESCRIPTORS = 50; // How many file descriptors are allowed to be outstanding (addfile has issues)
    const ZIP_STRING_LIMIT = 1048576;   // Cutoff for using ZipArchive addtostring vs addfile
    const TEMP_CLEANUP_SECONDS = 900;   // 15 min = How many seconds to keep temp files around when delete is requested 
    const MAX_LOG_SIZE = 200000;    // The higher this is the more overhead
    const LICENSE_KEY_OPTION_NAME = 'duplicator_pro_license_key';
    const MAX_BUILD_RETRIES = 10; // Max number of tries doing the same part of the package before auto cancelling
    const EDD_API_CACHE_TIME = 86400;	// 24 hours
    const UNLICENSED_SUPER_NAG_DELAY_IN_DAYS = 30;

    /* Pseudo constants */
    public static $PACKAGES_SUBMENU_SLUG;
    public static $SCHEDULES_SUBMENU_SLUG;
    public static $STORAGE_SUBMENU_SLUG;
    public static $TEMPLATES_SUBMENU_SLUG;
    public static $TOOLS_SUBMENU_SLUG;
    public static $SETTINGS_SUBMENU_SLUG;
    public static $LOCKING_FILE_FILENAME;


    public static function init()
    {
        self::$PACKAGES_SUBMENU_SLUG = self::PLUGIN_SLUG;
        self::$SCHEDULES_SUBMENU_SLUG = self::PLUGIN_SLUG . '-schedules';
        self::$STORAGE_SUBMENU_SLUG = self::PLUGIN_SLUG . '-storage';
        self::$TEMPLATES_SUBMENU_SLUG = self::PLUGIN_SLUG . '-templates';
        self::$TOOLS_SUBMENU_SLUG = self::PLUGIN_SLUG . '-tools';
        self::$SETTINGS_SUBMENU_SLUG = self::PLUGIN_SLUG . '-settings';


        self::$LOCKING_FILE_FILENAME = DUPLICATOR_PRO_PLUGIN_PATH . '/dup_pro_lock.bin';
    }

}

DUP_PRO_Constants::init();


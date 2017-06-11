<?php
if (!defined('DUPLICATOR_PRO_VERSION')) exit; // Exit if accessed directly

require_once ('class.pack.archive.zip.php');
require_once ('class.pack.archive.shellzip.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'classes/class.io.php');
require_once (DUPLICATOR_PRO_PLUGIN_PATH.'lib/forceutf8/src/Encoding.php');

/**
 * Defines the scope from which a filter item was created/retreived from
 * @package DupicatorPro\classes
 */
class DUP_PRO_Archive_Filter_Scope_Base
{
    //All internal storage items that we decide to filter
    public $Core = array();
    //TODO: Enable with Settings UI
    //Global filter items added from settings
    public $Global = array();
    //Items when creating a package or template
    public $Instance = array();

}

/**
 * Defines the scope from which a filter item was created/retreived from
 * @package DupicatorPro\classes
 */
class DUP_PRO_Archive_Filter_Scope_Directory extends DUP_PRO_Archive_Filter_Scope_Base
{
    // Items that are not readable
    public $Warning = array();
    // Items that are not readable
    public $Unreadable = array();
    // Directories containing other WordPress installs
    public $AddonSites = array();

}

/**
 * Defines the scope from which a filter item was created/retreived from
 * @package DupicatorPro\classes
 */
class DUP_PRO_Archive_Filter_Scope_File extends DUP_PRO_Archive_Filter_Scope_Base
{
    // Items that are not readable
    public $Warning = array();
    // Items that are not readable
    public $Unreadable = array();
    //Items that are too large
    public $Size = array();

}

/**
 * Defines the filtered items that are pulled from there various scopes
 * @package DupicatorPro\classes
 */
class DUP_PRO_Archive_Filter_Info
{
    //Contains all folder filter info
    //public $Dirs = array();
    public $Dirs;
    //Contains all file filter info
    //public $Files = array();
    public $Files;
    //Contains all extensions filter info
    //public $Exts = array();
    public $Exts;
    public $UDirCount  = 0;
    public $UFileCount = 0;
    public $UExtCount  = 0;

    public function __construct()
    {
        $this->Dirs  = new DUP_PRO_Archive_Filter_Scope_Directory();
        $this->Files = new DUP_PRO_Archive_Filter_Scope_File();
        $this->Exts  = new DUP_PRO_Archive_Filter_Scope_Base();
    }
}

/**
 * Manages all aspects of the archive process
 * @package DupicatorPro\classes
 */
class DUP_PRO_Archive
{
    //PUBLIC
    //Includes only the dirs set on the package
    public $FilterDirs;
    public $FilterExts;
    public $FilterFiles;
    //Includes all FilterInfo except warnings
    public $FilterDirsAll  = array();
    public $FilterExtsAll  = array();
    public $FilterFilesAll = array();
    public $FilterOn;
    public $File;
    public $Format;
    public $PackDir;
    public $Size  = 0;
    public $Dirs  = array();
    public $Files = array();
    public $file_count = -1;
    public $FilterInfo;
    //PROTECTED
    protected $Package;

    public function __construct($package)
    {
        $this->Package    = $package;
        $this->FilterOn   = false;
        $this->FilterInfo = new DUP_PRO_Archive_Filter_Info();
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
        DUP_PRO_LOG::trace("Building archive");
        try {
            $this->Package = $package;
            if (!isset($this->PackDir) && !is_dir($this->PackDir)) throw new Exception("The 'PackDir' property must be a valid directory.");
            if (!isset($this->File)) throw new Exception("A 'File' property must be set.");

            $completed = false;

            switch ($this->Format) {
                case 'TAR': break;
                case 'TAR-GZIP': break;
                default:
                    $this->Format = 'ZIP';

                    if ($build_progress->current_build_mode == DUP_PRO_Archive_Build_Mode::Shell_Exec) {
                        DUP_PRO_LOG::trace('Doing shell exec zip');
                        $completed = DUP_PRO_ShellZip::create($this, $build_progress);
                    } else {
                        if (class_exists('ZipArchive')) {
                            $completed = DUP_PRO_Zip::create($this, $build_progress);
                        } else {
                            DUP_PRO_LOG::trace("Zip archive doesn't exist?");
                        }
                    }
                    $this->Package->Update();
                    break;
            }

            if ($completed) {
                if ($build_progress->failed) {
                    DUP_PRO_LOG::traceError("Error building archive");
                    $this->Package->set_status(DUP_PRO_PackageStatus::ERROR);
                } else {
					$zip_filepath    = DUP_PRO_U::safePath("{$this->Package->StorePath}/{$this->Package->Archive->File}");
                    $this->Size		 = @filesize($zip_filepath);
                    $this->Package->set_status(DUP_PRO_PackageStatus::ARCDONE);
                    DUP_PRO_LOG::trace("filesize of zip = {$this->Size}");
                    DUP_PRO_LOG::trace("Done building archive");
                }
            } else {
                DUP_PRO_LOG::trace("Archive chunk completed");
            }

			

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    /**
     *  STATS
     *  Create filters info and generate dirs and files array
     *  @returns array					An array of values for the directory stats
     *  @link http://msdn.microsoft.com/en-us/library/aa365247%28VS.85%29.aspx Windows filename restrictions
     */
    public function run_scan_stats()
    {
        $this->create_filter_info();
        $this->get_dirs();
        $this->get_files();

        $this->FilterDirsAll  = array_merge($this->FilterDirsAll, $this->FilterInfo->Dirs->Unreadable);
        $this->FilterFilesAll = array_merge($this->FilterFilesAll, $this->FilterInfo->Files->Unreadable);
        return $this;
    }

    private function create_filter_info()
    {
        DUP_PRO_LOG::traceObject('Filter files', $this->FilterFiles);

        $this->FilterInfo->Dirs->Core = array();

        //FILTER: INSTANCE ITEMS
        //Add the items generated at create time
        if ($this->FilterOn) {
            $this->FilterInfo->Dirs->Instance  = array_map('DUP_PRO_U::safePath', explode(";", $this->FilterDirs, -1));
            $this->FilterInfo->Exts->Instance  = explode(";", $this->FilterExts, -1);
            $this->FilterInfo->Files->Instance = array_map('DUP_PRO_U::safePath', explode(";", $this->FilterFiles, -1));
        }

        //FILTER: GLOBAL ITMES
        //TODO: Wire up to settings page
        if ($GLOBALS['DUPLICATOR_PRO_GLOBAL_DIR_FILTERS_ON']) {
            $this->FilterInfo->Dirs->Global = $GLOBALS['DUPLICATOR_PRO_GLOBAL_DIR_FILTERS'];
            //$this->FilterInfo->Exts->Global[] = call_to_store();
        }

        if ($GLOBALS['DUPLICATOR_PRO_GLOBAL_FILE_FILTERS_ON']) {
            $this->FilterInfo->Files->Global = $GLOBALS['DUPLICATOR_PRO_GLOBAL_FILE_FILTERS'];
        }

        //FILTER: CORE ITMES
        //Filters Duplicator free packages & All pro local directories
        $storages = DUP_PRO_Storage_Entity::get_all();
        foreach ($storages as $storage) {
            if ($storage->storage_type == DUP_PRO_Storage_Types::Local && $storage->local_filter_protection) {
                $this->FilterInfo->Dirs->Core[] = DUP_PRO_U::safePath($storage->local_storage_folder);
            }
        }

        $this->FilterDirsAll = array_merge($this->FilterInfo->Dirs->Instance, $this->FilterInfo->Dirs->Global, $this->FilterInfo->Dirs->Core);

        $this->FilterExtsAll = array_merge($this->FilterInfo->Exts->Instance, $this->FilterInfo->Exts->Global, $this->FilterInfo->Exts->Core);

        $this->FilterFilesAll = array_merge($this->FilterInfo->Files->Instance, $this->FilterInfo->Files->Global, $this->FilterInfo->Files->Core);
    }

    //Get All Directories then filter
    private function get_dirs()
    {
        $global   = DUP_PRO_Global_Entity::get_instance();
        $rootPath = DUP_PRO_U::safePath(rtrim(DUPLICATOR_PRO_WPROOTPATH, '//'));

        $this->FilterInfo->Dirs->Warning    = array();
        $this->FilterInfo->Dirs->Unreadable = array();

        //If the root directory is a filter then we will only need the root files
        if (in_array($this->PackDir, $this->FilterDirsAll)) {
            $this->Dirs = array();
        } else {
            $this->Dirs   = $this->dirs_to_array($rootPath, $this->FilterDirsAll);
            $this->Dirs[] = $this->PackDir;
        }

        //Filter Directories
        //Invalid test contains checks for: characters over 250, invlaid characters, 
        //empty string and directories ending with period (Windows incompatable)
        foreach ($this->Dirs as $key => $val) {
            //Remove path filter directories
//            foreach ($this->FilterDirsAll as $item)
//            {
//                $trimmed_item = rtrim($item, '/');
//                if ($val == $trimmed_item || strstr($val, $trimmed_item . '/')) 
//				{
//                    unset($this->Dirs[$key]);
//                    continue 2;
//                }
//            }

            $name = basename($val);

            if ($global->archive_build_mode == DUP_PRO_Archive_Build_Mode::ZipArchive) {
                //Locate invalid directories and warn
                $invalid_test = strlen($val) > 250 ||
                    preg_match('/(\/|\*|\?|\>|\<|\:|\\|\|)/', $name) ||
                    trim($name) == '' ||
                    (strrpos($name, '.') == strlen($name) - 1 && substr($name, -1) == '.');
            } else {
                $invalid_test = false;
            }

            if ($invalid_test || preg_match('/[^\x20-\x7f]/', $name)) {
                $this->FilterInfo->Dirs->Warning[] = Encoding::toUTF8($val);
            }

            //Dir is not readble remove and flag
            if (!is_readable($this->Dirs[$key])) {
                unset($this->Dirs[$key]);
                $unreadable_dir                       = Encoding::toUTF8($val);
                $this->FilterInfo->Dirs->Unreadable[] = $unreadable_dir;
            }

            //-- Check for other WordPress installs
            if ($name == 'wp-admin') {
                $parent_dir = realpath(dirname($val));

                if ($parent_dir != realpath(DUPLICATOR_PRO_WPROOTPATH)) {
                    $includes_dir = "$parent_dir/wp-includes";

                    if (file_exists($includes_dir)) {
                        $wp_config = "$parent_dir/wp-config.php";

                        if (file_exists($wp_config)) {
                            // Ensure we aren't adding any critical directories
                            $parent_name = basename($parent_dir);

                            if (($parent_name != 'wp-includes') && ($parent_name != 'wp-content') && ($parent_name != 'wp-admin')) {
                                $this->FilterInfo->Dirs->AddonSites[] = $parent_dir;
                            }
                        }
                    }
                }
            }
        }

        DUP_PRO_LOG::traceObject('filter dirs array', $this->FilterDirsAll);
        DUP_PRO_LOG::traceObject('filter exts array', $this->FilterExtsAll);
        DUP_PRO_LOG::traceObject('filter files array', $this->FilterFilesAll);
    }

    //Get all files and filter out error prone subsets
    private function get_files()
    {

        $global = DUP_PRO_Global_Entity::get_instance();

        //Init for each call to prevent concatination from stored entity objects
        $this->Size                          = 0;
        $this->FilterInfo->Files->Size       = array();
        $this->FilterInfo->Files->Warning    = array();
        $this->FilterInfo->Files->Unreadable = array();

        foreach ($this->Dirs as $key => $val) {
            $files = DUP_PRO_IO::getFiles($val);

            foreach ($files as $filePath) {
                $fileName = basename($filePath);
                if (!is_dir($filePath)) {
                    // Note: The last clause is present to perform just a filename check
                    if ((!in_array(@pathinfo($filePath, PATHINFO_EXTENSION), $this->FilterExtsAll) &&
                        !in_array($filePath, $this->FilterFilesAll)) && !in_array($fileName, $this->FilterFilesAll)) {
                        if (!is_readable($filePath)) {
                            $this->FilterInfo->Files->Unreadable[] = $filePath;
                            continue;
                        }

                        $fileSize = @filesize($filePath);
                        $fileSize = empty($fileSize) ? 0 : $fileSize;

                        if ($global->archive_build_mode == DUP_PRO_Archive_Build_Mode::ZipArchive) {
                            $invalid_test = strlen($filePath) > 250 ||
                                preg_match('/(\/|\*|\?|\>|\<|\:|\\|\|)/', $fileName) ||
                                trim($fileName) == "";
                        } else {
                            $invalid_test = false;
                        }

                        //Warning Only
                        if ($invalid_test || preg_match('/[^\x20-\x7f]/', $fileName)) {
                            $filePath = Encoding::toUTF8($filePath);

                            $this->FilterInfo->Files->Warning[] = $filePath;
                        }

                        $this->Size += $fileSize;
                        $this->Files[] = $filePath;

                        if ($fileSize > DUPLICATOR_PRO_SCAN_WARNFILESIZE) {
                            $this->FilterInfo->Files->Size[] = $filePath.' ['.DUP_PRO_U::byteSize($fileSize).']';
                        }
                    }
                }
            }
        }
    }

    //Recursive function to get all Directories in a wp install
    //Older PHP logic which is more stable on older version of PHP
    //NOTE RecursiveIteratorIterator is problematic on some systems issues include:
    // - error 'too many files open' for recursion
    // - $file->getExtension() is not reliable as it silently fails at least in php 5.2.17 
    // - issues with when a file has a permission such as 705 and trying to get info (had to fallback to pathinfo)
    // - basic conclusion wait on the SPL libs untill after php 5.4 is a requiremnt
    // - since we are in a tight recursive loop lets remove the utiltiy call DUP_PRO_U::safePath("{$path}/{$file}") and
    //   squeeze out as much performance as we possible can
    private function dirs_to_array($path, $filterDirsAll)
    {
        $items  = array();
        $handle = @opendir($path);

        if ($handle) {
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $fullPath = str_replace("\\", '/', "{$path}/{$file}");
                    // RSR TODO: Don't leave it like this. Convert into an option on the package to not follow symbolic links
                    //if (is_dir($fullPath) && (is_link($fullPath) == false))
                    if (is_dir($fullPath)) {
                        $addDir = true;

                        //Remove path filter directories
                        foreach ($filterDirsAll as $filterDir) {
                            $trimmedFilterDir = rtrim($filterDir, '/');

                            if ($fullPath == $trimmedFilterDir || strstr($fullPath, $trimmedFilterDir.'/')) {
                                $addDir = false;
                                break;
                            }
                        }

                        if ($addDir) {
                            $items   = array_merge($items, $this->dirs_to_array($fullPath, $filterDirsAll));
                            $items[] = $fullPath;
                        }
                    }
                }
            }
            closedir($handle);
        }
        return $items;
    }
}
<?php
/**
 * Utility class for ziping up content
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package DUP_PRO
 * @subpackage classes/utilities
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.3.0
 */

/**
 * Helper class for reporting problems with zipping
 *
 * @see  DUP_PRO_Zip_U
 */
class DUP_PRO_Problem_Fix
{
    /**
     * The detected problem
     */
    public $problem = '';

    /**
     * A recommended fix for the problem
     */
    public $fix = '';
}

class DUP_PRO_Zip_U
{

    /**
     * Add a directory to an existing ZipArchive object
     *
     * @param ZipArchive $zipArchive        An existing ZipArchive object
     * @param string     $directoryPath     The full directory path to add to the ZipArchive
     * @param bool       $retainDirectory   Should the full directory path be retained in the archive
     *
     * @return bool Returns true if the directory was added to the object
     */
    public static function addDirWithZipArchive(&$zipArchive, $directoryPath, $retainDirectory = true)
    {
        $success = true;

        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directoryPath), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $path => $object) {
            $path       = DUP_PRO_U::safePath($path);
            $local_name = ltrim(str_replace($directoryPath, '', $path), '/');

            if ($retainDirectory) {
                $local_name = basename($directoryPath)."/$local_name";
            }

            if (!is_dir($path)) {
                if (is_readable($path)) {
                 //   $added = $zipArchive->addFile($path, $local_name);
                       $added = DUP_PRO_Zip_U::addFileToZipArchive($zipArchive, $path, $local_name);
                } else {
                    $added = false;
                }
            } else {
                $added = true;
            }

            if (!$added) {
                DUP_PRO_Log::error("Couldn't add file $path to archive", '', false);
                $success = false;
                break;
            }
        }

        return $success;
    }

    /**
     * Gets an array of possible ShellExec Zip problems on the server
     *
     * @return array Returns array of DUP_PRO_Problem_Fix objects
     */
    public static function getShellExecZipProblems()
    {
        $problem_fixes = array();

        if (!self::getShellExecZipPath()) {
            $filepath = null;

            $possible_paths = array(
                '/usr/bin/zip',
                '/opt/local/bin/zip'// RSR TODO put back in when we support shellexec on windows,
                //'C:/Program\ Files\ (x86)/GnuWin32/bin/zip.exe');
            );

            foreach ($possible_paths as $path) {
                if (file_exists($path)) {
                    $filepath = $path;
                    break;
                }
            }

            if ($filepath == null) {
                $problem_fix          = new DUP_PRO_Problem_Fix();
                $problem_fix->problem = DUP_PRO_U::__('Zip executable not present');
                $problem_fix->fix     = DUP_PRO_U::__('To install the zip executable and make it accessible to PHP.');

                $problem_fixes[] = $problem_fix;
            }

            $cmds = array('shell_exec', 'escapeshellarg', 'escapeshellcmd', 'extension_loaded');

            //Function disabled at server level
            if (array_intersect($cmds, array_map('trim', explode(',', @ini_get('disable_functions'))))) {
                $problem_fix = new DUP_PRO_Problem_Fix();

                $problem_fix->problem = DUP_PRO_U::__('Required functions disabled in the php.ini.');
                $problem_fix->fix     = DUP_PRO_U::__('To remove any of the following from disable_functions in the php.ini file: shell_exec, escapeshellarg, escapeshellcmd, and extension_loaded.');

                $problem_fixes[] = $problem_fix;
            }

            if (extension_loaded('suhosin')) {
                $suhosin_ini = @ini_get("suhosin.executor.func.blacklist");
                if (array_intersect($cmds, array_map('trim', explode(',', $suhosin_ini)))) {
                    $problem_fix = new DUP_PRO_Problem_Fix();

                    $problem_fix->problem = DUP_PRO_U::__('Suhosin is blocking PHP shell_exec.');
                    $problem_fix->fix     = DUP_PRO_U::__('To remove any of the following from the suhosin.executor.func.blacklist setting in the php.ini file: shell_exec, escapeshellarg, escapeshellcmd, and extension_loaded.');

                    $problem_fixes[] = $problem_fix;
                }
            }
        }

        return $problem_fixes;
    }

    /**
     * Get the path to the zip progame exeacutable on the server
     *
     * @return string   Returns the path to the zip program
     */
    public static function getShellExecZipPath()
    {
        $filepath = null;

        if (DUP_PRO_Shell_U::isShellExecEnabled()) {
            if (shell_exec('hash zip 2>&1') == NULL) {
                $filepath = 'zip';
            } else {
                $possible_paths = array(
                    '/usr/bin/zip',
                    '/opt/local/bin/zip'// RSR TODO put back in when we support shellexec on windows,
                    //'C:/Program\ Files\ (x86)/GnuWin32/bin/zip.exe');
                );

                foreach ($possible_paths as $path) {
                    if (file_exists($path)) {
                        $filepath = $path;
                        break;
                    }
                }
            }
        }

        return $filepath;
    }

    /**
     * Add a directory to an existing ZipArchive object
     *
     * @param string    $sourceFilePath     The file to add to the zip file
     * @param string    $zipFilePath        The zip file to be added to
     * @param bool      $deleteOld          Delete the zip file before adding a file
     * @param string    $newName            Rename the $sourceFile if needed
     *
     * @return bool Returns true if the file was added to the zip file
     */
    public static function zipFile($sourceFilePath, $zipFilePath, $deleteOld = true, $newName = null)
    {
        if ($deleteOld && file_exists($zipFilePath)) {
            DUP_PRO_IO::deleteFile($zipFilePath);
        }

        if (file_exists($sourceFilePath)) {
            $zip_archive = new ZipArchive();

            $is_zip_open = ($zip_archive->open($zipFilePath, ZIPARCHIVE::CREATE) === TRUE);

            if ($is_zip_open === false) {
                DUP_PRO_Log::error("Cannot create zip archive {$zipFilePath}");
            } else {
                //ADD SQL
                if ($newName == null) {
                    $source_filename = basename($sourceFilePath);
                    DUP_PRO_LOG::trace("adding {$source_filename}");
                } else {
                    $source_filename = $newName;
                    DUP_PRO_LOG::trace("new name added {$newName}");
                }

              //  $in_zip = $zip_archive->addFile($sourceFilePath, $source_filename);
                $in_zip = DUP_PRO_Zip_U::addFileToZipArchive($zip_archive, $sourceFilePath, $source_filename);

                if ($in_zip === false) {
                    DUP_PRO_Log::error("Unable to add {$sourceFilePath} to $zipFilePath");
                }

                $zip_archive->close();

                return true;
            }
        } else {
            DUP_PRO_Log::error("Trying to add {$sourceFilePath} to a zip but it doesn't exist!");
        }

        return false;
    }

    public static function addFileToZipArchive(&$zipArchive, $filepath, $localName)
    {
        $global = DUP_PRO_Global_Entity::get_instance();

       $added = $zipArchive->addFile($filepath, $localName);
    
        if(DUP_PRO_U::PHP70() && !$global->archive_compression)
        {
            $zipArchive->setCompressionName($localName, ZipArchive::CM_STORE);
        }

        return $added;
    }

    public static function addFromStringToZipArchive(&$zipArchive, $localName, &$contents)
    {
        $global = DUP_PRO_Global_Entity::get_instance();

        $added = $zipArchive->addFromString($localName, $contents);

        if(DUP_PRO_U::PHP70() && !$global->archive_compression)
        {
            $zipArchive->setCompressionName($localName, ZipArchive::CM_STORE);
        }

        return $added;
    }
}
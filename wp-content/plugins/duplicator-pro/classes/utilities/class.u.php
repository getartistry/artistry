<?php
/**
 * Utility class used for various task
 *
 * Standard: Missing
 *
 * @package DUP_PRO
 * @subpackage classes/utilities
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 * @since 3.0.0
 *
 */

require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/lib/pcrypt/class.pcrypt.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/class.io.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/class.constants.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/entities/class.global.entity.php');
require_once(DUPLICATOR_PRO_PLUGIN_PATH . '/classes/utilities/class.u.multisite.php');

class DUP_PRO_U
{
    // Pseudo-constants
    private static $type_format_array;

    public static function init()
    {
        self::$type_format_array = array('boolean' => '%s', 'integer' => '%d', 'double' => '%g', 'string' => '%s');
    }

     /**
      * Converts an absolute path to a relative path
      *
      * @param string $from The the path releative to $to
      * @param string $to   The full path of the directory to transform
      *
      * @return string  A string of the result
      */
    public static function getRelativePath($from, $to)
    {
        // some compatibility fixes for Windows paths
        $from = is_dir($from) ? rtrim($from, '\/').'/' : $from;
        $to   = is_dir($to) ? rtrim($to, '\/').'/' : $to;
        $from = str_replace('\\', '/', $from);
        $to   = str_replace('\\', '/', $to);

        $from    = explode('/', $from);
        $to      = explode('/', $to);
        $relPath = $to;

        foreach ($from as $depth => $dir) {
            // find first non-matching dir
            if ($dir === $to[$depth]) {
                // ignore this directory
                array_shift($relPath);
            } else {
                // get number of remaining dirs to $from
                $remaining = count($from) - $depth;
                if ($remaining > 1) {
                    // add traversals up to first matching dir
                    $padLength = (count($relPath) + $remaining - 1) * -1;
                    $relPath   = array_pad($relPath, $padLength, '..');
                    break;
                } else {
                    //$relPath[0] = './' . $relPath[0];
                }
            }
        }
        return implode('/', $relPath);
    }

     /**
      * Gets the percentage of one value to another
      *
      * @param int $val1
      * @param int $val2
      *
      * example:  
      *     $val1 = 100
      *     $val2 = 400
      *     $res  = 25
      *
      * @return int  Returns the results
      */
    public static function percentage($val1, $val2, $precision = 0)
    {
        $division = $val1 / (float) $val2;
        $res = $division * 100;
        $res = round($res, $precision);
        return $res;
    }

    /**
     * Localize and echo the current text
     *
     * @param string $text The text to localize
     *
     * @return string Returns the text in its desired language
     */
    public static function _e($text)
    {
        _e($text, DUP_PRO_Constants::PLUGIN_SLUG);
    }

    /**
     * Localize and return the current text as a variable
     *
     * @param string $text The text to localize
     *
     * @return variable Returns the text as a localized variable
     */
    public static function __($text)
    {
        return __($text, DUP_PRO_Constants::PLUGIN_SLUG);
    }



    /**
     * Append a new query value to the end of a URL
     *
     * @param string $url   The url to append the new value to
     * @param string $key   The new key name
     * @param string $value The new key name value
     *
     * @return string Returns the new url with with the query string name and value
     */
    public static function appendQueryValue($url, $key, $value)
    {
        $separator = (parse_url($url, PHP_URL_QUERY) == NULL) ? '?' : '&';
        $modified_url = $url."$separator$key=$value";

        return $modified_url;
    }

    /**
     * Display human readable byte sizes
     *
     * @param int $size    The size in bytes
     *
     * @return string The size of bytes readable such as 100KB, 20MB, 1GB etc.
     */
    public static function byteSize($size)
    {
        try {
            $units = array('B', 'KB', 'MB', 'GB', 'TB');
            for ($i = 0; $size >= 1024 && $i < 4; $i++) {
                $size /= 1024;
            }
            return round($size, 2).$units[$i];
        } catch (Exception $e) {
            return "n/a";
        }
    }

    /**
     * Return a string with the elapsed time
     *
     * @see getMicrotime()
     *
     * @param mixed number $end     The final time in the sequence to measure
     * @param mixed number $start   The start time in the sequence to measure
     *
     * @return  string   The time elapsed from $start to $end
     */
    public static function elapsedTime($end, $start)
    {
        return sprintf('%.2f sec.', abs($end - $start));
    }

    /**
     * Gets the calling function name from where this method is called
     *
     * @return  string   Returns the calling function name from where this method is called
     */
    public static function getCallingFunctionName()
    {
        $callers = debug_backtrace();
        $function_name = $callers[2]['function'];
        $class_name    = isset($callers[2]['class']) ? $callers[2]['class'] : '';

        return "$class_name::$function_name";
    }


    /**
     * Gets the contents of the file as an attachment type
     *
     * @param string $filepath      The full path the file to read
     * @param string $contentType   The header content type to force when pushing the attachment
     *
     * @return  string   Returns the contents of the file as an attachment type
     */
    public static function getDownloadAttachment($filepath, $contentType)
    {
        $filename = basename($filepath);

        header('Content-Type: $contentType');
        header("Content-Disposition: attachment; filename={$filename}");
        header("Pragma: public");

        if (readfile($filepath) === false) {
            throw new Exception(self::__("Couldn't read {$filepath}"));
        }
    }

    /**
     * Return the path of an executable program
     *
     * @param string $exeFilename  A file name or path to a file name of the executable
     *
     * @return  string | null   Returns the full path of the executable or null if not found
     */
    public static function getExeFilepath($exeFilename)
    {
        $filepath = null;

        if (DUP_PRO_Shell_U::isShellExecEnabled()) {
            if (shell_exec("hash $exeFilename 2>&1") == NULL) {
                $filepath = $exeFilename;
            } else {
                $possible_paths = array(
                    "/usr/bin/$exeFilename",
                    "/opt/local/bin/$exeFilename"
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
     * Return the WP admin page url from the slug
     *
     * @param string $menuSlug  The slug to search on
     *
     * @return  string   Returns the url of the menu by the slug
     */
    public static function getMenuPageURL($menuSlug, $echo = true)
    {
        if (defined('MULTISITE') && MULTISITE) {
            return DUP_PRO_MU::networkMenuPageUrl($menuSlug, $echo);
        } else {
            return menu_page_url($menuSlug, $echo);
        }
    }

    /**
     * Get current microtime as a float.  Method is used for simple profiling
     *
     * @see elapsedTime
     *
     * @return  string   A float in the form "msec sec", where sec is the number of seconds since the Unix epoch
     */
    public static function getMicrotime()
    {
        return microtime(true);
    }

    /**
     * Gets an SQL lock request
     *
     * @see releaseSqlLock()
     *
     * @return  bool    Returns true if an SQL lock request was successful
     */
    public static function getSqlLock()
    {
        global $wpdb;

        $query_string = "select GET_LOCK('duplicator_pro_lock', 0)";

        $ret_val = $wpdb->get_var($query_string);

        if ($ret_val == 0) {
            DUP_PRO_LOG::trace("Couldnt get mysql lock");
            return false;
        } else if ($ret_val == null) {
            DUP_PRO_LOG::trace("Error retrieving mysql lock");
            return false;
        } else {
            DUP_PRO_LOG::trace("Mysql lock obtained");
            return true;
        }
    }

    /**
     * Does the current user have the capability
     *
     * @return null Dies if user doesn't have the correct capability
     */
    public static function hasCapability($permission = 'read')
	{
        $capability = $permission;
        $capability = apply_filters('wpfront_user_role_editor_duplicator_pro_translate_capability', $capability);

        if (!current_user_can($capability))
        {
            wp_die(DUP_PRO_U::__('You do not have sufficient permissions to access this page.'));
            return;
        }
    }

    /**
     * Creates the snapshot directory if it doesn't already exisit
     *
     * @return null
     */
    public static function initStorageDirectory()
    {
        $global = DUP_PRO_Global_Entity::get_instance();

        $path_wproot = DUP_PRO_U::safePath(DUPLICATOR_PRO_WPROOTPATH);
        $path_ssdir = DUP_PRO_U::safePath(DUPLICATOR_PRO_SSDIR_PATH);
        $path_plugin = DUP_PRO_U::safePath(DUPLICATOR_PRO_PLUGIN_PATH);

        //--------------------------------
        //CHMOD DIRECTORY ACCESS
        //wordpress root directory
        DUP_PRO_IO::changeMode($path_wproot, 0755);

        //snapshot directory
        DUP_PRO_IO::createDir($path_ssdir);
        DUP_PRO_IO::changeMode($path_ssdir, 0755);

        //snapshot tmp directory
        $path_ssdir_tmp = $path_ssdir . '/tmp';
        DUP_PRO_IO::createDir($path_ssdir_tmp);
        DUP_PRO_IO::changeMode($path_ssdir_tmp, 0755);

        //plugins dir/files
        DUP_PRO_IO::changeMode($path_plugin . 'files', 0755);

        //--------------------------------
        //FILE CREATION
        //SSDIR: Create Index File
        $ssfile = @fopen($path_ssdir . '/index.php', 'w');
        @fwrite($ssfile, '<?php error_reporting(0);  if (stristr(php_sapi_name(), "fcgi")) { $url  =  "http://" . $_SERVER["HTTP_HOST"]; header("Location: {$url}/404.html");} else { header("HTTP/1.1 404 Not Found", true, 404);} exit(); ?>');
        @fclose($ssfile);

        //SSDIR: Create token file in snapshot
        $tokenfile = @fopen($path_ssdir . '/dtoken.php', 'w');
        @fwrite($tokenfile, '<?php error_reporting(0);  if (stristr(php_sapi_name(), "fcgi")) { $url  =  "http://" . $_SERVER["HTTP_HOST"]; header("Location: {$url}/404.html");} else { header("HTTP/1.1 404 Not Found", true, 404);} exit(); ?>');
        @fclose($tokenfile);

        //SSDIR: Create .htaccess
        // $storage_htaccess_off = DUP_PRO_Settings::Get('storage_htaccess_off');
        if ($global->storage_htaccess_off)
        {
            @unlink($path_ssdir . '/.htaccess');
        }
        else
        {
            $htfile = @fopen($path_ssdir . '/.htaccess', 'w');
            $htoutput = "Options -Indexes";
            @fwrite($htfile, $htoutput);
            @fclose($htfile);
        }

        //SSDIR: Robots.txt file
        $robotfile = @fopen($path_ssdir . '/robots.txt', 'w');
        @fwrite($robotfile, "User-agent: * \nDisallow: /" . DUPLICATOR_PRO_SSDIR_NAME . '/');
        @fclose($robotfile);

        //PLUG DIR: Create token file in plugin
        $tokenfile2 = @fopen($path_plugin . 'installer/dtoken.php', 'w');
        @fwrite($tokenfile2, '<?php @error_reporting(0); @require_once("../../../../wp-admin/admin.php"); global $wp_query; $wp_query->set_404(); header("HTTP/1.1 404 Not Found", true, 404); header("Status: 404 Not Found"); @include(get_template_directory () . "/404.php"); ?>');
        @fclose($tokenfile2);
    }

    /**
     * Wrap to prevent malware scanners from reporting false/positive
     * Switched from our old method to avoid WordFence reporting a false positive
     *
     * @param string $string The string to decryt i.e. base64_decond
     *
     * @return string Returns the string base64 decoded
     */
    public static function installerDecrypt($string)
    {
        return base64_decode($string);
    }

    /**
     * Is the server running Windows operating system
     *
     * @return bool Returns true if operating system is Windows
     *
     */
    public static function isWindows()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        {
            return true;
        }
        return false;
    }

    /**
     * Copies an array to an objects array
     *
     * @param array &$sourceArray   The source array
     * @param array &$destArray     The destination array in the class
     * @param object $className     The class name where the $destArray exists
     *
     * @return null
     */
    public static function objectArrayCopy(&$sourceArray, &$destArray, $className)
    {
        foreach ($sourceArray as $source_object) {
            $dest_object = new $className();
            self::objectCopy($source_object, $dest_object);
            array_push($destArray, $dest_object);
        }
    }

    /**
     * Copies simple values from one object to another
     *
     * @param array &$srcObject  The source object
     * @param array &$destObject     The destination object to copy to
     *
     * @return null
     */
    public static function objectCopy($srcObject, $destObject)
    {
        foreach ($srcObject as $member_name => $member_value) {
            if (!is_object($member_value)) {
                // Skipping all object members
                $destObject->$member_name = $member_value;
            }
        }
    }

    /**
     * Is the server PHP 5.3 or better
     *
     * @return  bool    Returns true if the server PHP 5.3 or better
     */
    public static function PHP53()
    {
        return version_compare(PHP_VERSION, '5.3.2', '>=');
    }

    /**
     * Is the server PHP 5.5 or better
     *
     * @return  bool    Returns true if the server PHP 5.3 or better
     */
    public static function PHP55()
    {
        return version_compare(PHP_VERSION, '5.5.0', '>=');
    }

        /**
     * Is the server PHP 5.5 or better
     *
     * @return  bool    Returns true if the server PHP 5.3 or better
     */
    public static function PHP70()
    {
        return version_compare(PHP_VERSION, '7.0.0', '>=');
    }

    /**
     * Releases the SQL lock request
     *
     * @see getSqlLock()
     *
     * @return  bool    Returns true if an SQL lock request was released
     */
    public static function releaseSqlLock()
    {
        global $wpdb;

        $query_string = "select RELEASE_LOCK('duplicator_pro_lock')";

        $ret_val = $wpdb->get_var($query_string);

        if ($ret_val == 0) {
            DUP_PRO_LOG::trace("Failed releasing sql lock duplicator_pro_lock because it wasn't established by this thread");
        } else if ($ret_val == null) {
            DUP_PRO_LOG::trace("Tried to release sql lock duplicator_pro_lock but it didn't exist");
        } else {
            // Lock was released
            DUP_PRO_LOG::trace("SQL lock released");
        }
    }

    /**
     * Makes path safe for any OS
     *      Paths should ALWAYS READ be "/"
     *          uni: /home/path/file.xt
     *          win:  D:/home/path/file.txt
     *
     * @param string $path		The path to make safe
     *
     * @return string A path with all slashes facing "/"
     */
    public static function safePath($path)
    {
        return str_replace("\\", "/", $path);
    }

    /**
     * Returns the last N lines of a file. Equivelent to tail command
     *
     * @param string $filepath The full path to the file to be tailed
     * @param int $lines The number of lines to return with each tail call
     *
     * @return string The last N parts of the file
     */
    public static function tailFile($filepath, $lines = 2)
    {
        // Open file
        $f = @fopen($filepath, "rb");
        if ($f === false) return false;

        // Sets buffer size
        $buffer = 256;

        // Jump to last character
        fseek($f, -1, SEEK_END);

        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if (fread($f, 1) != "\n") $lines -= 1;

        // Start reading
        $output = '';
        $chunk  = '';

        // While we would like more
        while (ftell($f) > 0 && $lines >= 0) {
            // Figure out how far back we should jump
            $seek   = min(ftell($f), $buffer);
            // Do the jump (backwards, relative to where we are)
            fseek($f, -$seek, SEEK_CUR);
            // Read a chunk and prepend it to our output
            $output = ($chunk  = fread($f, $seek)).$output;
            // Jump back to where we started reading
            fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
            // Decrease our line counter
            $lines -= substr_count($chunk, "\n");
        }

        // While we have too many lines
        // (Because of buffer size we might have read too many)
        while ($lines++ < 0) {
            // Find first newline and remove all text before that
            $output = substr($output, strpos($output, "\n") + 1);
        }
        fclose($f);
        return trim($output);
    }


}

DUP_PRO_U::init();
<?php
/*
  Duplicator Pro Website Installer Bootstrap
  Copyright (C) 2017, Snap Creek LLC
  website: snapcreek.com

  Duplicator Pro Plugin is distributed under the GNU General Public License, Version 3,
  June 2007. Copyright (C) 2007 Free Software Foundation, Inc., 51 Franklin
  St, Fifth Floor, Boston, MA 02110, USA

  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
  DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
  ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
  ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */


/**
 * Bootstrap utility to exatract the core installer
 *
 * Standard: PSR-2
 *
 * @package SC\DUPX\Bootstrap
 * @link http://www.php-fig.org/psr/psr-2/
 *
 *  To force extraction mode:
 *		installer.php?unzipmode=auto
 *		installer.php?unzipmode=ziparchive
 *		installer.php?unzipmode=shellexec
 */

abstract class DUPX_Bootstrap_Unzip_Mode
{
	const Auto			= 0;
	const ZipArchive	= 1;
	const ShellExec		= 2;
}

abstract class DUPX_Connectivity
{
	const OK		= 0;
	const Error		= 1;
	const Unknown	= 2;
}

class DUPX_Bootstrap
{
	//@@ Params get dynamically swapped when package is built
	const ARCHIVE_FILENAME	 = '@@ARCHIVE@@';
	const ARCHIVE_SIZE		 = '@@ARCHIVE_SIZE@@';
	const INSTALLER_DIR_NAME = 'dpro-installer';
	const BOOTSTRAP_LOG		 = './installer-bootlog.txt';
	const VERSION			 = '@@VERSION@@';
	
	public $hasZipArchive     = false;
	public $hasShellExecUnzip = false;
	public $mainInstallerURL;
	public $installerContentsPath;
	public $installerExtractPath;
	public $archiveExpectedSize = 0;
	public $archiveActualSize = 0;
	public $activeRatio = 0;

	/**
	 * Instantiate the Bootstract Object
	 *
	 * @return null
	 */
	public function __construct()
	{
		$archiveActualSize		        = @filesize(self::ARCHIVE_FILENAME);
		$archiveActualSize				= ($archiveActualSize !== false) ? $archiveActualSize : 0;
		$this->hasZipArchive			= class_exists('ZipArchive');
		$this->hasShellExecUnzip		= $this->getUnzipFilePath() != null ? true : false;
		$this->installerContentsPath	= str_replace("\\", '/', (dirname(__FILE__). '/' .self::INSTALLER_DIR_NAME));
		$this->installerExtractPath		= str_replace("\\", '/', (dirname(__FILE__)));
		$this->archiveExpectedSize      = self::ARCHIVE_SIZE;
		$this->archiveActualSize        = $archiveActualSize;
		$this->archiveRatio				= ($this->archiveActualSize  / $this->archiveExpectedSize) * 100;
	}

	/**
	 * Run the bootstrap process which includes checking for requiremtns and running
	 * the extraction process
	 *
	 * @return null | string	Returns null if the run was sucessful otherwise an error message
	 */
	public function run()
	{
		date_default_timezone_set('UTC'); // Some machines don’t have this set so just do it here

		@unlink(self::BOOTSTRAP_LOG);
		$this->log('== DUPLICATOR PRO INSTALLER BOOTSTRAP v@@VERSION@@==');
		$this->log('----------------------------------------------------');
		$this->log('Installer bootstrap start');

		$archive_filepath	 = $this->getArchiveFilePath();
		$archive_filename	 = self::ARCHIVE_FILENAME;
		$unzip_mode			 = $this->getUnzipMode();
		$error				 = null;
		$extract_installer	 = true;
		$installer_directory = dirname(__FILE__).'/'.self::INSTALLER_DIR_NAME;
		$extract_success	 = false;
		$archiveExpectedEasy = $this->readableByteSize($this->archiveExpectedSize);
		$archiveActualEasy   = $this->readableByteSize($this->archiveActualSize);

		//ARCHIVE FILE NOT FOUND
		if (! file_exists($archive_filepath)) {
			$this->log("ERROR: Archive file not found! Expected File Name: [{$archive_filepath}]");
			
			//DETECT ARCHIVE FILES
			$zip_files = $this->getZipFiles();
			$zip_count = count($zip_files);
			$zip_html  = "- No zip files found -";

			if ($zip_count >= 1) {
				$zip_html = "<ol>";
				foreach($zip_files as $file) {
					$zip_html .=  "<li> {$file}</li>";
				}
			   $zip_html .=  "</ol>";
			}
			
			$error  = "<b>The correct archive file was not found!</b> The <i>'Required File'</i> below should be present in the <i>'Extraction Path'</i>.  "
				. "The archive file name must be the <u>exact</u> name of the archive file placed in the extraction path (character for character).  "
				. "If the file does not have the correct name then rename it to the <i>'Required File'</i> below.   When downloading the package files make "
				. "sure both files are from the same package line in the packages view.<br/><br/>"
				. "<b>Required File:</b>  {$archive_filename} <br/>"
				. "<b>Extraction Path:</b> {$this->installerExtractPath}/<br/><br/>"
				. "The following zip files were found at the extraction path: <br/>{$zip_html}";

			return $error;
		}

		if ($this->archiveRatio < 98) {
			$this->log("ERROR: The expected archive size is [{$archiveExpectedEasy}].  The actual size is currently [{$archiveActualEasy}].");
			$this->log("The archive file may not have fully been downloaded to the server");
			$percent = round($this->archiveRatio);

			$autochecked = isset($_POST['auto-fresh']) ? "checked='true'" : '';
			$error  = "<b>Expected archive file size is incorrect.</b><br/> The expected archive size is <b class='pass'>[{$archiveExpectedEasy}]</b>.  "
				. "The actual size is currently <b class='fail'>[{$archiveActualEasy}]</b>.  The archive file may not have fully been downloaded to the server.  "
				. "Please validate that the file sizes are the same and that the file has been completely downloaded to the destination server.<br/><br/>"
				. "To try again <a href='installer.php'>[Refresh Page]</a>"
				. "<div style='margin-top:4px'><input type='checkbox' id='auto-fresh' name='auto-fresh' {$autochecked} onclick='AutoFresh()' />"
				. "<label for='auto-fresh'>Auto refresh page every <span id='count-down'>10</span> seconds</label></div>"
				. "<br/><br/><b>Archive Download Progress:</b><br/>";
			$error .= "<div class='w3-light-grey'><div class='w3-container w3-green w3-center' style='width:{$percent}%'>{$percent}%</div></div>";

			return $error;
		}

		//INSTALL DIRECTORY: Check if its setup correctly
		if (file_exists($installer_directory)) {

			$this->log("$installer_directory already exists");
			$extract_installer = !file_exists($installer_directory."/main.installer.php");

			($extract_installer)
				? $this->log("But main.installer.php doesn't so extracting anyway")
				: $this->log("main.installer.php also exists so not going to extract installer directory");
			
		} else {
			$this->log("$installer_directory doesn't yet exist");
		}

		//ATTEMPT EXTRACTION: ZipArchive and Shell Exec
		if ($extract_installer) {

			$this->log("Ready to extract the installer");

			if (($unzip_mode == DUPX_Bootstrap_Unzip_Mode::Auto) || ($unzip_mode == DUPX_Bootstrap_Unzip_Mode::ZipArchive) && class_exists('ZipArchive')) {
				if ($this->hasZipArchive) {
					$this->log("ZipArchive exists so using that");
					$extract_success = $this->extractInstallerZipArchive($archive_filepath);

					if ($extract_success) {
						$this->log('Successfully extracted with ZipArchive');
					} else {
						$error = 'Error extracting with ZipArchive.';
						$this->log($error);
					}
				} else {
					$this->log("WARNING: ZipArchive is not enabled.");
					$error  = "NOTICE: ZipArchive is not enabled on this server please talk to your host or server admin about enabling ";
					$error .= "<a target='_blank' href='https://snapcreek.com/duplicator/docs/faqs-tech/#faq-trouble-060-q'>ZipArchive</a> on this server. <br/>";
				}
			}

			if (!$extract_success) {
				if (($unzip_mode == DUPX_Bootstrap_Unzip_Mode::Auto) || ($unzip_mode == DUPX_Bootstrap_Unzip_Mode::ShellExec)) {
					$unzip_filepath = $this->getUnzipFilePath();
					if ($unzip_filepath != null) {
						$extract_success = $this->extractInstallerShellexec($archive_filepath);
						if ($extract_success) {
							$this->log('Successfully extracted with Shell Exec');
							$error = null;
						} else {
							$error .= 'Error extracting with Shell Exec. Please manually extract archive then choose Advanced > Manual Extract in installer.';
							$this->log($error);
						}
					} else {
						$this->log('WARNING: Shell Exec Zip is not available');
						$error .= "NOTICE: Shell Exec is not enabled on this server please talk to your host or server admin about enabling ";
						$error .= "<a target='_blank' href='http://php.net/manual/en/function.shell-exec.php'>Shell Exec</a> on this server.";
					}
				}
			}
		} else {
			$this->log("Didn't need to extract the installer.");
		}

		$current_url  = $this->getCurrentURL();
		$current_url .= $_SERVER['SERVER_NAME'];
		$current_url  = $current_url.':'.$_SERVER['SERVER_PORT'];
		$current_url .= $_SERVER['REQUEST_URI'];
		$uri_start    = dirname($current_url);

		if ($error == null) {
			$bootloader_name	 = basename(__FILE__);
			$this->mainInstallerURL = $uri_start.'/'.self::INSTALLER_DIR_NAME.'/main.installer.php';

			$this->fixInstallerPerms($this->mainInstallerURL);
			$this->mainInstallerURL = $this->mainInstallerURL . "?archive=$archive_filename&bootloader=$bootloader_name";

			if (isset($_SERVER['QUERY_STRING'])) {
				$this->mainInstallerURL .= '&'.$_SERVER['QUERY_STRING'];
			}

			$this->log("No detected errors so redirecting to the main installer. Main Installer URI = {$this->mainInstallerURL}");
		}

		return $error;
	}

	/**
     * Gets the current url protocol portion http(s)
     *
     * @return string  Returns the 'http(s)' state of the current url
     */
	public function getCurrentURL()
	{
		$url = null;
		if (isset($_SERVER['HTTPS'])) {
			$url = ($_SERVER['HTTPS'] !== 'off') ? 'https://'  : 'http://';
		} else {
			$url = ($_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
		}
		return $url;
	}

	/**
     *  Attempts to set the 'dpro-installer' directory permissions
     *
     * @return null
     */
	private function fixInstallerPerms()
	{
		$file_perms = substr(sprintf('%o', fileperms(__FILE__)), -4);
		$file_perms = octdec($file_perms);
		//$dir_perms = substr(sprintf('%o', fileperms(dirname(__FILE__))), -4);

		// No longer using existing directory permissions since that can cause problems.  Just set it to 755
		$dir_perms = '755'; 
		$dir_perms = octdec($dir_perms);
		$installer_dir_path = $this->installerContentsPath;

		$this->setPerms($installer_dir_path, $dir_perms, false);
		$this->setPerms($installer_dir_path, $file_perms, true);
	}

	/**
     * Set the permissions of a given directory and optionally all files
     *
     * @param string $directory		The full path to the directory where perms will be set
     * @param string $perms			The given permission sets to use such as '0755'
	 * @param string $do_files		Also set the permissions of all the files in the directory
     *
     * @return null
     */
	private function setPerms($directory, $perms, $do_files)
	{
		if (!$do_files) {
			// If setting a directory hiearchy be sure to include the base directory
			$this->setPermsOnItem($directory, $perms);
		}

		$item_names = array_diff(scandir($directory), array('.', '..'));

		foreach ($item_names as $item_name) {
			$path = "$directory/$item_name";
			if (($do_files && is_file($path)) || (!$do_files && !is_file($path))) {
				$this->setPermsOnItem($path, $perms);
			}
		}
	}

	/**
     * Set the permissions of a single directory or file
     *
     * @param string $path			The full path to the directory or file where perms will be set
     * @param string $perms			The given permission sets to use such as '0755'
     *
     * @return bool		Returns true if the permission was properly set
     */
	private function setPermsOnItem($path, $perms)
	{
		$result = @chmod($path, $perms);
		$perms_display = decoct($perms);
		if ($result === false) {
			$this->log("Couldn't set permissions of $path to {$perms_display}<br/>");
		} else {
			$this->log("Set permissions of $path to {$perms_display}<br/>");
		}
		return $result;
	}


	/**
     * Logs a string to the installer-bootlog.txt file
     *
     * @param string $s			The string to log to the log file
     *
     * @return null
     */
	public function log($s)
	{
		$timestamp = date('M j H:i:s'); 
		file_put_contents(self::BOOTSTRAP_LOG, "$timestamp $s\n", FILE_APPEND); 
	}

	/**
     * Extracts only the 'dpro-installer' files using ZipArchive
     *
     * @param string $archive_filepath	The path to the archive file.
     *
     * @return bool		Returns true if the data was properly extracted
     */
	private function extractInstallerZipArchive($archive_filepath)
	{
		$success	 = true;
		$zipArchive	 = new ZipArchive();

		if ($zipArchive->open($archive_filepath) === true) {
			$this->log("Successfully opened $archive_filepath");
			$destination = dirname(__FILE__);
			$folder_prefix = self::INSTALLER_DIR_NAME.'/';
			$this->log("Extracting all files from archive within ".self::INSTALLER_DIR_NAME);

			$installer_files_found = 0;

			for ($i = 0; $i < $zipArchive->numFiles; $i++) {
				$stat		 = $zipArchive->statIndex($i);
				$filename	 = $stat['name'];

				if ($this->startsWith($filename, $folder_prefix)) {
					$installer_files_found++;

					if ($zipArchive->extractTo($destination, $filename) === true) {
						$this->log("Success: {$filename} >>> {$destination}");
					} else {
						$this->log("Error extracting {$filename} from archive {$archive_filepath}");
						$success = false;
						break;
					}
				}
			}

			if ($zipArchive->close() === true) {
				$this->log("Successfully closed {$archive_filepath}");
			} else {
				$this->log("Problem closing {$archive_filepath}");
				$success = false;
			}

			if ($installer_files_found < 10) {
				$this->log("Couldn't find the installer directory in the archive!");

				$success = false;
			}
		} else {
			$this->log("Couldn't open archive {$archive_filepath} with ZipArchive");
			$success = false;
		}
		return $success;
	}

	/**
     * Extracts only the 'dpro-installer' files using Shell-Exec Unzip
     *
     * @param string $archive_filepath	The path to the archive file.
     *
     * @return bool		Returns true if the data was properly extracted
     */
	private function extractInstallerShellexec($archive_filepath)
	{
		$success = false;
		$this->log("Attempting to use Shell Exec");
		$unzip_filepath	 = $this->getUnzipFilePath();

		if ($unzip_filepath != null) {
			$unzip_command	 = "$unzip_filepath -q $archive_filepath ".self::INSTALLER_DIR_NAME.'/* 2>&1';
			$this->log("Executing $unzip_command");
			$stderr	 = shell_exec($unzip_command);

			if ($stderr == '') {
				$this->log("Shell exec unzip succeeded");
				$success = true;
			} else {
				$this->log("Shell exec unzip failed. Output={$stderr}");
			}
		}

		return $success;
	}

	/**
     * Attempts to get the archive file path
     *
     * @return string	The full path to the archive file
     */
	private function getArchiveFilePath()
	{
		$archive_filename = self::ARCHIVE_FILENAME;

		if (isset($_GET['archive'])) {
			$archive_filename = $_GET['archive'];
		}

		$archive_filepath = str_replace("\\", '/', dirname(__FILE__) . '/' . $archive_filename);
		$this->log("Using archive $archive_filepath");
		return $archive_filepath;
	}

	/**
     * Gets the DUPX_Bootstrap_Unzip_Mode enum type that should be used
     *
     * @return DUPX_Bootstrap_Unzip_Mode	Returns the current mode of the bootstrapper
     */
	private function getUnzipMode()
	{
		$unzip_mode = DUPX_Bootstrap_Unzip_Mode::Auto;

		if (isset($_GET['unzipmode'])) {
			$unzipmode_string = $_GET['unzipmode'];
			$this->log("Unzip mode specified in querystring: $unzipmode_string");

			switch ($unzipmode_string) {
				case 'auto':
					$unzip_mode = DUPX_Bootstrap_Unzip_Mode::Auto;
					break;

				case 'ziparchive':
					$unzip_mode = DUPX_Bootstrap_Unzip_Mode::ZipArchive;
					break;

				case 'shellexec':
					$unzip_mode = DUPX_Bootstrap_Unzip_Mode::ShellExec;
					break;
			}
		}

		return $unzip_mode;
	}

	/**
     * Checks to see if a string starts with specific characters
     *
     * @return bool		Returns true if the string starts with a specific format
     */
	private function startsWith($haystack, $needle)
	{
		return $needle === "" || strrpos($haystack, $needle, - strlen($haystack)) !== false;
	}

	/**
     * Checks to see if the server supports issuing commands to shell_exex
     *
     * @return bool		Returns true shell_exec can be ran on this server
     */
	public function hasShellExec()
	{
		$cmds = array('shell_exec', 'escapeshellarg', 'escapeshellcmd', 'extension_loaded');

		//Function disabled at server level
		if (array_intersect($cmds, array_map('trim', explode(',', @ini_get('disable_functions'))))) return false;

		//Suhosin: http://www.hardened-php.net/suhosin/
		//Will cause PHP to silently fail
		if (extension_loaded('suhosin')) {
			$suhosin_ini = @ini_get("suhosin.executor.func.blacklist");
			if (array_intersect($cmds, array_map('trim', explode(',', $suhosin_ini)))) return false;
		}
		// Can we issue a simple echo command?
		if (!@shell_exec('echo duplicator')) return false;

		return true;
	}

	/**
     * Gets the possible system commands for unzip on Linux
     *
     * @return string		Returns unzip file path that can execute the unzip command
     */
	public function getUnzipFilePath()
	{
		$filepath = null;

		if ($this->hasShellExec()) {
			if (shell_exec('hash unzip 2>&1') == NULL) {
				$filepath = 'unzip';
			} else {
				$possible_paths = array(
					'/usr/bin/unzip',
					'/opt/local/bin/unzip'// RSR TODO put back in when we support shellexec on windows,
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
	 * Display human readable byte sizes such as 150MB
	 *
	 * @param int $size		The size in bytes
	 *
	 * @return string A readable byte size format such as 100MB
	 */
	public function readableByteSize($size)
	{
		try {
			$units = array('B', 'KB', 'MB', 'GB', 'TB');
			for ($i = 0; $size >= 1024 && $i < 4; $i++)
				$size /= 1024;
			return round($size, 2).$units[$i];
		} catch (Exception $e) {
			return "n/a";
		}
	}

	/**
     *  Returns an array of zip files found in the current executing directory
     *
     *  @return array of zip files
     */
    public static function getZipFiles()
    {
        $files = array();
        foreach (glob("*.zip") as $name) {
            if (file_exists($name)) {
                $files[] = $name;
            }
        }

        if (count($files) > 0) {
            return $files;
        }

        //FALL BACK: Windows XP has bug with glob,
        //add secondary check for PHP lameness
        if ($dh = opendir('.')) {
            while (false !== ($name = readdir($dh))) {
                $ext = substr($name, strrpos($name, '.') + 1);
                if (in_array($ext, array("zip"))) {
                    $files[] = $name;
                }
            }
            closedir($dh);
        }

        return $files;
    }
}

$boot  = new DUPX_Bootstrap();
$boot_error = $boot->run();
$auto_refresh = isset($_POST['auto-fresh']) ? true : false;

?>


<html>
<?php if ($boot_error == null) :?>

	<head>
		<meta http-equiv="refresh" content="2;url='<?php echo $boot->mainInstallerURL ?>'" />
		<script>
			window.location = "<?php echo $boot->mainInstallerURL ?>";
		</script>
	</head>

<?php else :?>

	<head>
		<style>
			body {font-family:Verdana,Arial,sans-serif; line-height:18px; font-size: 12px}
			h2 {font-size:20px; margin:5px 0 5px 0; border-bottom:1px solid #dfdfdf; padding:3px}
			div#content {border:1px solid #CDCDCD; width:750px; min-height:550px; margin:auto; margin-top:18px; border-radius:5px; box-shadow:0 8px 6px -6px #333; font-size:13px}
			div#content-inner {padding:10px 30px; min-height:550px}
			
			/* Header */
			table.header-wizard {border-top-left-radius:5px; border-top-right-radius:5px; width:100%; box-shadow:0 5px 3px -3px #999; background-color:#F1F1F1; font-weight:bold}
			table.header-wizard td.header {font-size:24px; padding:7px 0 7px 0; width:100%;}
			div.dupx-logfile-link {float:right; font-weight:normal; font-size:12px}
			.dupx-version {white-space:nowrap; color:#999; font-size:11px; font-style:italic; text-align:right;  padding:0 15px 5px 0; line-height:14px; font-weight:normal}
			.dupx-version a { color:#999; }

			div.errror-notice {text-align:center; font-style:italic; font-size:11px}
			div.errror-msg { color:maroon; padding: 10px 0 5px 0}
			.pass {color:green}
			.fail {color:red}
			table.settings {width:100%; font-size:12px}
			table.settings td {padding: 4px}
			table.settings td:first-child {font-weight: bold}
			.w3-light-grey,.w3-hover-light-grey:hover,.w3-light-gray,.w3-hover-light-gray:hover{color:#000!important;background-color:#f1f1f1!important}
			.w3-container:after,.w3-container:before,.w3-panel:after,.w3-panel:before,.w3-row:after,.w3-row:before,.w3-row-padding:after,.w3-row-padding:before,
			.w3-cell-row:before,.w3-cell-row:after,.w3-clear:after,.w3-clear:before,.w3-bar:before,.w3-bar:after
			{content:"";display:table;clear:both}
			.w3-green,.w3-hover-green:hover{color:#fff!important;background-color:#4CAF50!important}
			.w3-container{padding:0.01em 16px}
			.w3-center{display:inline-block;width:auto; text-align: center !important}
		</style>
	</head>
	<body>		
	<div id="content">

		<table cellspacing="0" class="header-wizard">
			<tr>
				<td class="header"> &nbsp; Duplicator Pro - Bootloader</div</td>
				<td class="dupx-version">
					version: <?php echo DUPX_Bootstrap::VERSION ?> <br/>
					&raquo; <a target='_blank' href='installer-bootlog.txt'>installer-bootlog.txt</a>
				</td>
			</tr>
		</table>

		<form id="error-form" method="post">
		<div id="content-inner">
			<h2 style="color:maroon">Setup Notice:</h2>
			<div class="errror-notice">An error has occurred. In order to load the full installer please resolve the issue below.</div>
			<div class="errror-msg">
				<?php echo $boot_error ?>
			</div>
			<br/><br/>

			<h2>Server Settings:</h2>
			<table class='settings'>
				<tr>
					<td>ZipArchive:</td>
					<td><?php echo $boot->hasZipArchive  ? '<i class="pass">Enabled</i>' : '<i class="fail">Disabled</i>'; ?> </td>
				</tr>
				<tr>
					<td>ShellExec Unzip:</td>
					<td><?php echo $boot->hasShellExecUnzip	? '<i class="pass">Enabled</i>' : '<i class="fail">Disabled</i>'; ?> </td>
				</tr>
				<tr>
					<td>Extraction Path:</td>
					<td><?php echo $boot->installerExtractPath; ?></td>
				</tr>
				<tr>
					<td>Installer Path:</td>
					<td><?php echo $boot->installerContentsPath; ?></td>
				</tr>
				<tr>
					<td>Archive Data:</td>
					<td style="line-height: 20px">
						<b>Name:</b> <?php echo DUPX_Bootstrap::ARCHIVE_FILENAME  ?><br/>
						<b>Expected Size:</b> <?php echo $boot->readableByteSize($boot->archiveExpectedSize); ?>  &nbsp;
						<b>Actual Size:</b>   <?php echo $boot->readableByteSize($boot->archiveActualSize); ?>
					</td>
				</tr>
				<tr>
					<td>Boot Log</td>
					<td><a target='_blank' href='installer-bootlog.txt'>installer-bootlog.txt</a></td>
				</tr>				
			</table>
			<br/><br/>

			<div style="font-size:11px">
				Please Note: Either ZipArchive or Shell Exec will need to be enabled for the installer to run automatically otherwise a manual extraction
				will need to be performed.  In order to run the installer manually follow the instructions to
				<a href='https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-015-q' target='_blank'>manually extract</a> before running the installer.
			</div>
			<br/><br/>

		</div>
		</form>

	</div>
	</body>

	<script>
		function AutoFresh() {
			document.getElementById('error-form').submit();
		}
		<?php if ($auto_refresh) :?>
			var duration = 10000; //10 seconds
			var counter  = 10;
			var countElement = document.getElementById('count-down');
		
			setTimeout(function(){window.location.reload(1);}, duration);
			setInterval(function() {
				counter--;
				countElement.innerHTML = (counter > 0) ? counter.toString() : "0";
			}, 1000);

		<?php endif; ?>
	</script>


<?php endif; ?>

<!--
Used for integrity check do not remove:
DUPLICATOR_PRO_INSTALLER_EOF  -->
</html>
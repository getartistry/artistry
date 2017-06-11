<?php
/*
  Duplicator Pro Website Installer
  Copyright (C) 2016, Snap Creek LLC
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
date_default_timezone_set('UTC'); // Some machines don’t have this set so just do it here.

$GLOBALS['DUPX_DEBUG'] = false;
$GLOBALS['DUPX_ROOT']  = str_replace("\\", '/', (realpath(dirname(__FILE__) . '/..')));
$GLOBALS['DUPX_INIT']  = "{$GLOBALS['DUPX_ROOT']}/dpro-installer";

if(!isset($_GET['archive']))
{
	// RSR TODO: Fail gracefully
	echo "Archive parameter not specified";
	exit(1);
}

if(!isset($_GET['bootloader']))
{
	// RSR TODO: Fail gracefully
	echo "Bootloader parameter not specified";
	exit(1);
}

require_once($GLOBALS['DUPX_INIT'] . '/main.download.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/config/class.constants.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/config/class.archive.config.php');

if(!DUPX_ArchiveConfig::initConfigGlobals())
{
	// RSR TODO: Fail 'gracefully'
	echo "Can't initialize config globals";
	exit(1);
}

require_once($GLOBALS['DUPX_INIT'] . '/classes/utilities/class.u.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/class.db.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/class.logging.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/class.http.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/class.server.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/config/class.conf.srv.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/config/class.conf.wp.php');
require_once($GLOBALS['DUPX_INIT'] . '/classes/class.engine.php');

$GLOBALS['_CURRENT_URL_PATH'] = $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$GLOBALS['_HELP_URL_PATH']    = "?view=help&archive={$GLOBALS['FW_PACKAGE_NAME']}&bootloader={$GLOBALS['BOOTLOADER_NAME']}&basic";
$GLOBALS['NOW_TIME']		  = @date("His");


if(!chdir($GLOBALS['DUPX_INIT']))
{
	// RSR TODO: Can't change directories
	echo "Can't change to directory " . $GLOBALS['DUPX_INIT'];
	exit(1);
}

if (isset($_POST['ctrl_action']))
{
    switch ($_POST['ctrl_action']) {
		case "ctrl-step1" :
			require_once($GLOBALS['DUPX_INIT'] . '/ctrls/ctrl.step1.php');
			break;
        case "ctrl-step2" :
			require_once($GLOBALS['DUPX_INIT'] . '/ctrls/ctrl.step2.php');
			break;
		case "ctrl-step3" :
			require_once($GLOBALS['DUPX_INIT'] . '/ctrls/ctrl.step3.php');
			break;
    }
    @fclose($GLOBALS["LOG_FILE_HANDLE"]);
    die("");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow">
	<title>Duplicator Professional</title>
	<link rel='stylesheet' href='assets/font-awesome/css/font-awesome.min.css' type='text/css' media='all' />
	<?php
		require_once($GLOBALS['DUPX_INIT'] . '/assets/inc.libs.css.php');
		require_once($GLOBALS['DUPX_INIT'] . '/assets/inc.css.php');
		require_once($GLOBALS['DUPX_INIT'] . '/assets/inc.libs.js.php');
		require_once($GLOBALS['DUPX_INIT'] . '/assets/inc.js.php');
	?>
</head>
<body>

<div id="content">

<!-- HEADER TEMPLATE: Common header on all steps -->
<table cellspacing="0" class="header-wizard">
	<tr>
		<td style="width:100%;">
			<div style="font-size:26px; padding:7px 0 7px 0">
				<!-- !!DO NOT CHANGE/EDIT OR REMOVE PRODUCT NAME!!
				If your interested in Private Label Rights please contact us at the URL below to discuss
				customizations to product labeling: https://snapcreek.com	-->
				&nbsp; <i class="fa fa-bolt"></i> Duplicator Pro
			</div>
		</td>
		<td class="wiz-dupx-version">
			version:	<?php echo $GLOBALS['FW_VERSION_DUP'] ?> <br/>
			&raquo; <a href="javascript:void(0)" onclick="DUPX.openServerDetails()">info</a>&nbsp;
			&raquo; <a href="?view=help&archive=<?php echo $GLOBALS['FW_PACKAGE_NAME']?>&bootloader=<?php echo $GLOBALS['BOOTLOADER_NAME']?>&basic" target="_blank">help</a>&nbsp;
			<a href="<?php echo $GLOBALS['_HELP_URL_PATH'];?>" target="_blank"><i class="fa fa-question-circle"></i></a>
		</td>
	</tr>
</table>

<!-- =========================================
FORM DATA: User-Interface views -->
<div id="content-inner">
	<?php
		switch ($GLOBALS["VIEW"]) {
			case "secure" :
				require_once($GLOBALS['DUPX_INIT'] . '/views/view.init1.php');
				break;

			case "step1"   :
				require_once($GLOBALS['DUPX_INIT'] . '/views/view.step1.php');
				break;

			case "step2" :
				require_once($GLOBALS['DUPX_INIT'] . '/views/view.step2.php');
				break;

			case "step3" :
				require_once($GLOBALS['DUPX_INIT'] . '/views/view.step3.php');
				break;

			case "step4"   :
				require_once($GLOBALS['DUPX_INIT'] . '/views/view.step4.php');
				break;

			case "help"   :
				require_once($GLOBALS['DUPX_INIT'] . '/views/view.help.php');
				break;

			default :
				echo "Invalid View Requested";
		}
	?>
</div>
</div>


<!-- SERVER INFO DIALOG -->
<div id="dialog-server-details" title="Setup Information" style="display:none">
	<!-- DETAILS -->
	<div class="dlg-serv-info">
		<?php
			$ini_path 		= php_ini_loaded_file();
			$ini_max_time 	= ini_get('max_execution_time');
			$ini_memory 	= ini_get('memory_limit');
		?>
         <div class="hdr">Server Information</div>
		<label>Try CDN Request:</label> 		<?php echo ( DUPX_U::tryCDN("ajax.aspnetcdn.com", 443) && DUPX_U::tryCDN("ajax.googleapis.com", 443)) ? 'Yes' : 'No'; ?> <br/>
		<label>Web Server:</label>  			<?php echo $_SERVER['SERVER_SOFTWARE']; ?><br/>
        <label>PHP Version:</label>  			<?php echo DUPX_Server::$php_version; ?><br/>
		<label>PHP INI Path:</label> 			<?php echo empty($ini_path ) ? 'Unable to detect loaded php.ini file' : $ini_path; ?>	<br/>
		<label>PHP SAPI:</label>  				<?php echo php_sapi_name(); ?><br/>
		<label>PHP ZIP Archive:</label> 		<?php echo class_exists('ZipArchive') ? 'Is Installed' : 'Not Installed'; ?> <br/>
		<label>PHP max_execution_time:</label>  <?php echo $ini_max_time === false ? 'unable to find' : $ini_max_time; ?><br/>
		<label>PHP memory_limit:</label>  		<?php echo empty($ini_memory)      ? 'unable to find' : $ini_memory; ?><br/>

        <br/>
        <div class="hdr">Package Build Information</div>
        <label>Plugin Version:</label>  		<?php echo $GLOBALS['FW_VERSION_DUP'] ?><br/>
        <label>WordPress Version:</label>  		<?php echo $GLOBALS['FW_VERSION_WP'] ?><br/>
        <label>PHP Version:</label>             <?php echo $GLOBALS['FW_VERSION_PHP'] ?><br/>
        <label>Database Version:</label>        <?php echo $GLOBALS['FW_VERSION_DB'] ?><br/>
        <label>Operating System:</label>        <?php echo $GLOBALS['FW_VERSION_OS'] ?><br/>

	</div>
</div>

<script>
DUPX.openServerDetails = function ()
{
	$("#dialog-server-details").dialog({
	  resizable: false,
	  height: "auto",
	  width: 700,
	  modal: true,
	  position: { my: 'top', at: 'top+150' },
	  buttons: {"OK": function() {$(this).dialog("close");} }
	});
}

$(document).ready(function ()
{
	//Disable href for toggle types
	$("a[data-type='toggle']").each(function() {
		$(this).attr('href', 'javascript:void(0)');
	});

});
</script>


<?php if ($_GET['debug']) :?>
	<form id="form-debug" method="post" action="?debug=1">
		<input id="debug-view" type="hidden" name="view" />
		DEBUG MODE ON:	<hr size="1" />
		<a href="javascript:void(0)" onclick="DUPX.debugNavigate('secure')">[Password]</a> &nbsp;
		<a href="javascript:void(0)" onclick="DUPX.debugNavigate('scan')">[Scanner]</a> &nbsp;
		<a href="javascript:void(0)" onclick="DUPX.debugNavigate('deploy')">[Deploy - 1]</a> &nbsp;
		<a href="javascript:void(0)" onclick="DUPX.debugNavigate('update')">[Update - 2]</a> &nbsp;
		<a href="javascript:void(0)" onclick="DUPX.debugNavigate('test')">[Test - 3]</a> &nbsp;
        <a href="//<?php echo $GLOBALS['_CURRENT_URL_PATH']?>/api/router.php" target="api">[API]</a> &nbsp;
		<br/><br/>
		<a href="javascript:void(0)"  onclick="$('#debug-vars').toggle()"><b>PAGE VARIABLES</b></a>
		<pre id="debug-vars"><?php print_r($GLOBALS); ?></pre>
	</form>

	<script>
		DUPX.debugNavigate = function(view)
		{
			$('#debug-view').val(view);
			$('#form-debug').submit();
		}
	</script>
<?php endif; ?>


<!-- Used for integrity check do not remove:
DUPLICATOR_PRO_INSTALLER_EOF -->
</body>
</html>
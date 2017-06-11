<?php
require_once($GLOBALS['DUPX_INIT'] . '/classes/config/class.archive.config.php');

//-- START OF VIEW STEP 2
$_POST['dbcharset'] = isset($_POST['dbcharset']) ? trim($_POST['dbcharset']) : $GLOBALS['DBCHARSET_DEFAULT'];
$_POST['dbcollate'] = isset($_POST['dbcollate']) ? trim($_POST['dbcollate']) : $GLOBALS['DBCOLLATE_DEFAULT'];

$archive_config = DUPX_ArchiveConfig::getInstance();
$show_multisite = ($archive_config->mu_mode !== 0) && (count($archive_config->subsites) > 0);
$multisite_disabled = ($archive_config->getLicenseType() != DUPX_LicenseType::BusinessGold);

$_POST['logging'] = isset($_POST['logging']) ? trim($_POST['logging']) : 1;

?>

<form id='s2-input-form' method="post" class="content-form"  data-parsley-validate="true" data-parsley-excluded="input[type=hidden], [disabled], :hidden">

	<div class="dupx-logfile-link">
		<a href="../installer-log.txt?now=<?php echo $GLOBALS['NOW_TIME']; ?>" target="dpro-installer">installer-log.txt</a>
	</div>
	<div class="hdr-main">
		Step <span class="step">2</span> of 4: Install Database
	</div>

	<div class="s2-btngrp">
		<input id="s2-basic-btn" type="button" value="Basic" class="active" onclick="DUPX.togglePanels('basic')" />
		<input id="s2-cpnl-btn" type="button" value="cPanel" class="in-active" onclick="DUPX.togglePanels('cpanel')" />
	</div>

	<!--  POST PARAMS -->
	<div class="dupx-debug">
		<input type="hidden" name="view" value="step2" />
		<input type="hidden" name="ctrl_action" value="ctrl-step2" />
		<input type="hidden" name="view_mode" id="s2-input-form-mode" />
		<input type="hidden" name="logging" id="logging" value="<?php echo $_POST['logging']; ?>" />
	</div>


	<!-- =========================================
	BASIC PANEL -->
	<div id="s2-basic-pane">
		<!-- DATABASE -->
		<div class="hdr-sub1">Database Setup</div>
		<table class="dupx-opts">
			<tr>
				<td>Action:</td>
				<td>
					<select name="dbaction" id="dbaction">
						<option value="create">Create New Database</option>
						<option value="empty">Connect and Remove All Data</option>
						<option value="rename">Connect and Backup Any Existing Data</option>
						<option value="manual">Manual SQL Execution (Advanced)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Host:</td>
				<td><input type="text" name="dbhost" id="dbhost" required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_DBHOST']); ?>" placeholder="localhost" /></td>
			</tr>
			<tr>
				<td>Database:</td>
				<td>
					<input type="text" name="dbname" id="dbname" required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_DBNAME']); ?>"  placeholder="new or existing database name"  />
					<div class="s2-warning-emptydb">
						Warning: The selected 'Action' above will remove <u>all data</u> from this database!
					</div>
					<div class="s2-warning-renamedb">
						Notice: The selected 'Action' will rename <u>all existing tables</u> from the database name above with a prefix '<?php echo $GLOBALS['DB_RENAME_PREFIX']; ?>'.
						The prefix is only applied to existing tables and not the new tables that will be installed.
					</div>
					<div class="s2-warning-manualdb">
						Notice: The 'Manual SQL execution' action will prevent the SQL script in the archive from running. The database above should already be
						pre-populated with data which will be updated in the next step. No data in the database will be modified until after Step 3 runs.
					</div>
				</td>
			</tr>
			<tr><td>User:</td><td><input type="text" name="dbuser" id="dbuser" required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_DBUSER']); ?>" placeholder="valid database username" /></td></tr>
			<tr><td>Password:</td><td><input type="text" name="dbpass" id="dbpass" value="<?php echo htmlspecialchars($GLOBALS['FW_DBPASS']); ?>"  placeholder="valid database user password"   /></td></tr>
		</table>

        <!-- DB TEST RESULTS -->
        <div class="s2-dbconn-area">
            <div class="s2-dbconn-result"></div>
		</div>

	</div>

	<!-- =========================================
	C-PANEL PANEL -->
	<div id="s2-cpnl-pane">
		<div class="hdr-sub1">
			cPanel Login: <a id="s2-cpnl-status-msg" href="javascript:void(0)" onclick="$('#s2-cpnl-status-details').toggle()"></a>
		</div>

		<div id="s2-cpnl-area">
			<table class="dupx-opts">
				<tr>
					<td>Host:</td>
					<td>
						<input type="text" name="cpnl-host" id="cpnl-host" required="true" value="<?php echo $GLOBALS['FW_CPNL_HOST']; ?>" placeholder="cPanel url" />
						 <a id="cpnl-host-get-lnk" href="javascript:DUPX.getcPanelURL('cpnl-host')" style="font-size:12px">get</a>
						<div id="cpnl-host-warn">
							Caution: The cPanel host name and URL in the browser address bar do not match, in rare cases this may be intentional.
							Please be sure this is the correct server to avoid data loss.
						</div>
					</td>
				</tr>
				<tr><td>Username:</td><td><input type="text" name="cpnl-user" id="cpnl-user" required="true" data-parsley-type="alphanum" value="<?php echo htmlspecialchars($GLOBALS['FW_CPNL_USER']); ?>" placeholder="cPanel username" /></td></tr>
				<tr><td>Password:</td><td><input type="text" name="cpnl-pass" id="cpnl-pass" value="<?php echo htmlspecialchars($GLOBALS['FW_CPNL_PASS']); ?>"  placeholder="cPanel password" required="true" /></td></tr>
			</table>

			<div id="s2-cpnl-connect">
				<input type="button" id="s2-cpnl-connect-btn" class="default-btn" onclick="DUPX.cpnlConnect()" value="Connect" />
				<input type="button" id="s2-cpnl-change-btn" onclick="DUPX.cpnlToggleLogin()" value="Change" class="default-btn"  style="display:none" />
				<div id="s2-cpnl-status-details" style="display:none">
					<div id="s2-cpnl-status-details-msg">
						Please click the connect button to connect to your cPanel.
					</div>
					<small style="font-style: italic">
						<a href="javascript:void()" onclick="$('#s2-cpnl-status-details').hide()">[Hide Message]</a> &nbsp;
						<a href='https://snapcreek.com/wordpress-hosting/' target='_blank'>[cPanel Supported Hosts]</a>
					</small>
				</div>
			</div>
		</div>

		<!-- CPNL MYSQL DATABASE -->
		<div class="hdr-sub1">Database Setup: <span id="s2-cpnl-db-opts-lbl">cPanel Login Required to enable</span> </div>
		<input type="hidden" name="cpnl-dbname-result" id="cpnl-dbname-result" />
		<input type="hidden" name="cpnl-dbuser-result" id="cpnl-dbuser-result" />
		<table id="s2-cpnl-db-opts" class="dupx-opts">
			<tr>
				<td>Action:</td>
				<td>
					<select name="cpnl-dbaction" id="cpnl-dbaction">
						<option value="create">Create New Database</option>
						<option value="empty">Connect and Delete Any Existing Data</option>
						<option value="rename">Connect and Backup Any Existing Data</option>
						<option value="manual">Manual SQL Execution (Advanced)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Host:</td>
				<td><input type="text" name="cpnl-dbhost" id="cpnl-dbhost" required="true" value="<?php echo htmlspecialchars($GLOBALS['FW_CPNL_DBHOST']); ?>" placeholder="localhost" /></td>
			</tr>
			<tr>
				<td>Database:</td>
				<td>
					<!-- EXISTING CPNL DB -->
					<div id="s2-cpnl-dbname-area1">
						<select name="cpnl-dbname-select" id="cpnl-dbname-select" required="true" data-parsley-pattern="^((?!-- Select Database --).)*$"></select>
						<div class="s2-warning-emptydb">
							Warning: This action will remove <u>all data</u> from the database name above!
						</div>
					</div>
					<!-- NEW CPNL DB -->
					<div id="s2-cpnl-dbname-area2">
						<table>
							<tr>
								<td id="cpnl-prefix-dbname"></td>
								<td><input type="text" name="cpnl-dbname-txt" id="cpnl-dbname-txt" required="true" data-parsley-pattern="/^[a-zA-Z0-9-_]+$/" data-parsley-errors-container="#cpnl-dbname-txt-error" value="<?php echo htmlspecialchars($GLOBALS['FW_CPNL_DBNAME']); ?>"  placeholder="new or existing database name"  /></td>
							</tr>
						</table>
						<div id="cpnl-dbname-txt-error"></div>
					</div>
					<div class="s2-warning-renamedb">
						Notice: This action will rename <u>all tables</u> in the database selected above with the prefix '<?php echo $GLOBALS['DB_RENAME_PREFIX']; ?>'.
					</div>
					<div class="s2-warning-manualdb">
						Notice: The 'Manual SQL execution' action will prevent the SQL script in the archive from running.  <br/>
						The database name above should already be pre-populated with data which will be updated in the next step. <br/>
						No data in the database will be modified until after Step 2 runs.
					</div>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="checkbox" name="cpnl-dbuser-chk" id="cpnl-dbuser-chk" style="margin-left:5px" /> <label for="cpnl-dbuser-chk">Create New Database User</label> </td>
			</tr>
			<tr>
				<td>User:</td>
				<td>
					<div id="s2-cpnl-dbuser-area1">
						<select name="cpnl-dbuser-select" id="cpnl-dbuser-select" required="true" data-parsley-pattern="^((?!-- Select User --).)*$"></select>
					</div>
					<div id="s2-cpnl-dbuser-area2">
						<table>
							<tr>
								<td id="cpnl-prefix-dbuser"></td>
								<td><input type="text" name="cpnl-dbuser-txt" id="cpnl-dbuser-txt" required="true" data-parsley-pattern="/^[a-zA-Z0-9-_]+$/" data-parsley-errors-container="#cpnl-dbuser-txt-error" data-parsley-cpnluser="16" value="<?php echo htmlspecialchars($GLOBALS['FW_CPNL_DBUSER']); ?>" placeholder="valid database username" /></td>
							</tr>
						</table>
						<div id="cpnl-dbuser-txt-error"></div>
					</div>
				</td>
			</tr>
			<tr><td>Password:</td><td><input type="text" name="cpnl-dbpass" id="cpnl-dbpass" required="true" placeholder="valid database user password" /></td></tr>
			<tr>
				<td>Prefix:</td>
				<td>
					<input type="checkbox" name="cpnl_ignore_prefix"  id="cpnl_ignore_prefix" value="1" onclick="DUPX.cpnlPrefixIgnore()" />
					<label for="cpnl_ignore_prefix">Ignore cPanel Prefix</label>
				</td>
			</tr>
		</table>

         <!-- DB TEST RESULTS -->
        <div class="s2-dbconn-area">
            <div class="s2-dbconn-result"></div>
		</div>

	</div>

	<!-- =========================================
	MULTISITE PANEL -->
	<?php if($show_multisite) : ?>
		<div class="hdr-sub1" style="margin-top:25px">
			<a data-type="toggle" data-target="#s2-multisite"><i class="fa fa-minus-square"></i> Multisite</a>
		</div>
		<div id="s2-multisite">
			<input id="full-network" onclick="DUPX.enableSubsiteList(false);" type="radio" name="multisite-install-type" value="0" checked>
			<label for="full-network">Restore entire multisite network</label><br>
			<input <?php if($multisite_disabled) { echo 'disabled'; } ?> id="multisite-install-type" onclick="DUPX.enableSubsiteList(true);"  type="radio" name="multisite-install-type" value="1">
			<label for="multisite-install-type">Convert subsite
				<select id="subsite-id" name="subsite-id" style="width:200px" disabled>
					<?php
					foreach($archive_config->subsites as $subsite) : ?>
					<option value="<?php echo $subsite->id; ?>"><?php echo "{$subsite->name}"?></option>
					<?php endforeach; ?>
				</select>
				<span>into a standalone site<?php if($multisite_disabled) { echo '*'; } ?></span>
			</label>
			<?php
				if($multisite_disabled)
				{
					$license_string = ' This installer was created using ';
					switch($archive_config->getLicenseType())
					{
						case DUPX_LicenseType::Unlicensed:
							$license_string .= "an unlicensed copy of Duplicator Pro.";
							break;

						case DUPX_LicenseType::Personal:
							$license_string .= "a Personal license of Duplicator Pro.";
							break;

						case DUPX_LicenseType::Freelancer:
							$license_string .= "a Freelancer license of Duplicator Pro.";
							break;

						default:
							$license_string = '';
					}

					echo "<p><small>*Requires a Business or Gold license. $license_string</small></p>";
				}
			?>
		</div>
		<br/><br/>
	<?php endif; ?>


	<!-- ADVANCED OPTS -->
	<div class="hdr-sub1">
		<a data-type="toggle" data-target="#s2-adv-opts"><i class="fa fa-plus-square"></i> Advanced Options</a>
	</div>
	<div id='s2-adv-opts' style="display:none;padding-top:0">
		<div class="help-target"><a href="<?php echo $GLOBALS['_HELP_URL_PATH'];?>#help-s2" target="_blank"><i class="fa fa-question-circle"></i></a></div>

		<table class="dupx-opts dupx-advopts">
			<tr>
				<tr>
				<td>Spacing:</td>
				<td><input type="checkbox" name="dbnbsp" id="dbnbsp" value="1" /> <label for="dbnbsp">Fix non-breaking space characters</label></td>
				</tr>
				<td style="vertical-align:top">Mode:</td>
				<td>
					<input type="radio" name="dbmysqlmode" id="dbmysqlmode_1" checked="true" value="DEFAULT"/> <label for="dbmysqlmode_1">Default</label> &nbsp;
					<input type="radio" name="dbmysqlmode" id="dbmysqlmode_2" value="DISABLE"/> <label for="dbmysqlmode_2">Disable</label> &nbsp;
					<input type="radio" name="dbmysqlmode" id="dbmysqlmode_3" value="CUSTOM"/> <label for="dbmysqlmode_3">Custom</label> &nbsp;
					<div id="dbmysqlmode_3_view" style="display:none; padding:5px">
						<input type="text" name="dbmysqlmode_opts" value="" /><br/>
						<small>Separate additional <a href="?help#help-mysql-mode" target="_blank">sql modes</a> with commas &amp; no spaces.<br/>
							Example: <i>NO_ENGINE_SUBSTITUTION,NO_ZERO_IN_DATE,...</i>.</small>
					</div>
				</td>
			</tr>
			<tr><td>Charset:</td><td><input type="text" name="dbcharset" id="dbcharset" value="<?php echo $_POST['dbcharset'] ?>" /> </td></tr>
			<tr><td>Collation: </td><td><input type="text" name="dbcollate" id="dbcollate" value="<?php echo $_POST['dbcollate'] ?>" /> </tr>
		</table>

	</div>
	<br/><br/><br/>
	<br/><br/><br/>


	<div class="footer-buttons">
        <button type="button" onclick="DUPX.testDBConnect()" class="default-btn" /><i class="fa fa-database"></i> Test Database</button>
		<button id="s2-deploy-btn" type="button" onclick="DUPX.confirmDeployment()" class="default-btn"> Next <i class="fa fa-caret-right"></i> </button>
	</div>

</form>


<!-- CONFIRM DIALOG -->
<div id="dialog-confirm" title="Install Confirmation" style="display:none">
	<div style="padding: 10px 0 25px 0">
		<b>Run installer with these settings?</b>
	</div>

	<b>Database Settings:</b><br/>
	<table style="margin-left:20px">
		<tr>
			<td><b>Server:</b></td>
			<td><i id="dlg-dbhost"></i></td>
		</tr>
		<tr>
			<td><b>Name:</b></td>
			<td><i id="dlg-dbname"></i></td>
		</tr>
		<tr>
			<td><b>User:</b></td>
			<td><i id="dlg-dbuser"></i></td>
		</tr>
	</table>
	<br/><br/>

	<small><i class="fa fa-exclamation-triangle"></i> WARNING: Be sure these database parameters are correct! Entering the wrong information WILL overwrite an existing database.
	Make sure to have backups of all your data before proceeding.</small><br/>
</div>


<!-- =========================================
VIEW: STEP 2 - AJAX RESULT
Auto Posts to view.step3.php  -->
<form id='s2-result-form' method="post" class="content-form" style="display:none">

	<div class="dupx-logfile-link"><a href="../installer-log.txt" target="dpro-installer">installer-log.txt</a></div>
	<div class="hdr-main">
		Step <span class="step">2</span> of 4: Install Database
	</div>

	<!--  POST PARAMS -->
	<div class="dupx-debug">
		<input type="hidden" name="view" value="step3" />
		<input type="hidden" name="dbaction" id="ajax-dbaction" />
		<input type="hidden" name="dbhost" id="ajax-dbhost" />
		<input type="hidden" name="dbname" id="ajax-dbname" />
		<input type="hidden" name="dbuser" id="ajax-dbuser" />
		<input type="hidden" name="dbpass" id="ajax-dbpass" />
		<input type="hidden" name="dbcharset" id="ajax-dbcharset" />
		<input type="hidden" name="dbcollate" id="ajax-dbcollate" />
		<input type="hidden" name="json"   id="ajax-json" />
		<input type="hidden" name="subsite-id" id="ajax-subsite-id" value="-1" />
		<input type="hidden" name="retain_config" value="<?php echo $_POST['retain_config']; ?>" />
		<input type="hidden" name="logging" id="ajax-logging"  />
		<input type='submit' value='manual submit'>
	</div>

	<!--  PROGRESS BAR -->
	<div id="progress-area">
		<div style="width:500px; margin:auto">
			<div style="font-size:1.7em; margin-bottom:20px"><i class="fa fa-circle-o-notch fa-spin"></i> Installing Database</div>
			<div id="progress-bar"></div>
			<h3> Please Wait...</h3><br/><br/>
			<i>Keep this window open during the creation process.</i><br/>
			<i>This can take several minutes.</i>
		</div>
	</div>

	<!--  AJAX SYSTEM ERROR -->
	<div id="ajaxerr-area" style="display:none">
		<p>Please try again an issue has occurred.</p>
		<div style="padding: 0px 10px 10px 0px;">
			<div id="ajaxerr-data">An unknown issue has occurred with the file and database setup process.  Please see the installer-log.txt file for more details.</div>
			<div style="text-align:center; margin:10px auto 0px auto">
				<input type="button" onclick="$('#s2-result-form').hide();  $('#s2-input-form').show(200);" value="&laquo; Try Again" class="default-btn" /><br/><br/>
				<i style='font-size:11px'>See online help for more details at <a href='https://snapcreek.com/' target='_blank'>snapcreek.com</a></i>
			</div>
		</div>
	</div>
</form>

<script>
var CPNL_TOKEN;
var CPNL_DBINFO			= null;
var CPNL_DBUSERS		= null;
var CPNL_CONNECTED		= false;
var CPNL_PREFIX			= false;

/**
 *  Toggles the cpanel Login area  */
DUPX.togglePanels = function (pane)
{
	$('#s2-basic-pane, #s2-cpnl-pane').hide();
	$('#s2-basic-btn, #s2-cpnl-btn').removeClass('active in-active');
	if (pane == 'basic') {
		$('#s2-input-form-mode').val('basic');
		$('#s2-basic-pane').show();
		$('#s2-basic-btn').addClass('active');
		$('#s2-cpnl-btn').addClass('in-active');
	} else {
		$('#s2-input-form-mode').val('cpnl');
		$('#s2-cpnl-pane').show();
		$('#s2-cpnl-btn').addClass('active');
		$('#s2-basic-btn').addClass('in-active');
	}
}

DUPX.enableSubsiteList = function(enable)
{
	if(enable) {
		$("#subsite-id").prop('disabled', false);
	} else {
		$("#subsite-id").prop('disabled', 'disabled');
	}
}

/**
 *  Bacic Action Change  */
DUPX.basicDBActionChange = function ()
{
	var action = $('#dbaction').val();
	$('#s2-basic-pane .s2-warning-manualdb').hide();
	$('#s2-basic-pane .s2-warning-emptydb').hide();
	$('#s2-basic-pane .s2-warning-renamedb').hide();
	switch (action)
	{
		case 'create'  :	break;
		case 'empty'   : $('#s2-basic-pane .s2-warning-emptydb').show(300);		break;
		case 'rename'  : $('#s2-basic-pane .s2-warning-renamedb').show(300);	break;
		case 'manual'  : $('#s2-basic-pane .s2-warning-manualdb').show(300);	break;
	}
};

/**
 * Shows results of database connection
 * Timeout (45000 = 45 secs) */
DUPX.testDBConnect = function ()
{
	var $resource = $('#s2-input-form-mode').val() == 'basic'
			? $('#s2-basic-pane .s2-dbconn-result')
			: $('#s2-cpnl-pane .s2-dbconn-result');
	$resource.html("Attempting Connection.  Please wait...").show(250);

	$.ajax({
		type: "POST",
		timeout: 45000,
		url: window.location.href + '&' + 'dbtest=1',
		data: $('#s2-input-form').serialize(),
		success: function (data) {
			$resource.html(data);
		},
		error: function () {
			alert('An error occurred while testing the database connection!  Please check your hosts documentation for the correct parameters to use.');
		}
	});
};

/**
 *  Performs cpnl connection and updates UI */
DUPX.cpnlConnect = function ()
{
	var $formInput = $('#s2-input-form');
	$formInput.parsley().validate();
	if (!$formInput.parsley().isValid()) {
		return;
	}

	$('#s2-cpnl-connect-btn').attr('readonly', 'true').val('Connecting... Please Wait!');
	$('a#s2-cpnl-status-msg').hide();

	var apiAccountActive = function(data)
	{
		var html	= "";
		var error	= "Unknown Error";
		var prefix	= "";
		var validHost  = false;
		var validUser  = false;

		if (typeof data == 'undefined')	{
			error = "Unknown error, unable to retrive data request.";
			CPNL_CONNECTED = false;
		}
		else if (data.hasOwnProperty('status') && data.status == 0)	{
			error = data.hasOwnProperty('statusText') ? data.statusText : "Unknown error, unable to retrive status text.";
			CPNL_CONNECTED = false;
		}
		else if (data.hasOwnProperty('result')) {
			validHost		= data.result.valid_host;
			validUser		= data.result.valid_user;
			CPNL_DBINFO		= data.result.hasOwnProperty('dbinfo')  ? data.result.dbinfo  : null;
			CPNL_DBUSERS	= data.result.hasOwnProperty('dbusers') ? data.result.dbusers : null;
			CPNL_CONNECTED	= validHost && validUser;
		}

		html += validHost	? "<b>Host:</b>  <div class='dupx-pass'>Success</div> &nbsp; "
							: "<b>Host:</b>  <div class='dupx-fail'>Unable to Connect</div> &nbsp;";
		html += validUser	? "<b>Account:</b> <div class='dupx-pass'>Found</div><br/>"
							: "<b>Account:</b> <div class='dupx-fail'>Not Found</div><br/>";

		if (CPNL_CONNECTED)
		{
			var setupDBName = '<?php echo strlen($GLOBALS['FW_CPNL_DBNAME']) > 0 ? $GLOBALS['FW_CPNL_DBNAME'] : 'null'; ?>';
			var setupDBUser = '<?php echo strlen($GLOBALS['FW_CPNL_DBUSER']) > 0 ? $GLOBALS['FW_CPNL_DBUSER'] : 'null'; ?>';
			var $dbNameSelect = $("#cpnl-dbname-select");
			var $dbUserSelect = $("#cpnl-dbuser-select");

			//Set Prefix data
			if(data.result.is_prefix_on.status)
			{
				prefix = $('#cpnl-user').val() + "_";
				var dbnameTxt = $("#cpnl-dbname-txt").val();
				var dbuserTxt = $("#cpnl-dbuser-txt").val();

				$("#cpnl-prefix-dbname, #cpnl-prefix-dbuser").show().html(prefix + "&nbsp;");
				if (dbnameTxt.indexOf(prefix) != -1) {
					$("#cpnl-dbname-txt").val(dbnameTxt.replace(prefix, ''));
				}
				if (dbuserTxt.indexOf(prefix) != -1) {
					$("#cpnl-dbuser-txt").val(dbuserTxt.replace(prefix, ''));
				}
				CPNL_PREFIX = true;
			} else {
				$("#cpnl-prefix-dbname, #cpnl-prefix-dbuser").hide().html("");
				$('#cpnl_ignore_prefix').attr('checked', 'true');
				$('#cpnl_ignore_prefix').attr('onclick', 'return false;');
				$('#cpnl_ignore_prefix').attr('onkeydown', 'return false;');
				var $label = $('label[for="cpnl_ignore_prefix"]');
				$label.css('color', 'gray');
				$label.html($label.text() + ' <i>(this option has been set to readonly by host)</i>');
				CPNL_PREFIX = false;
			}

			//Enable database inputs and show header green go icon
			DUPX.cpnlToggleLogin('on');
			$('a#s2-cpnl-status-msg').html('<div class="status-badge-pass">success</div>');
			$('div#s2-cpnl-status-details-msg').html(html);

			//Load DB Names
			$dbNameSelect.find('option').remove().end();
			$dbNameSelect.append($("<option selected></option>").val("-- Select Database --").text("-- Select Database --"));
			$.each(CPNL_DBINFO, function (key, value)
			{
				(setupDBName == value.db)
					? $dbNameSelect.append($("<option selected></option>").val(value.db).text(value.db))
					: $dbNameSelect.append($("<option></option>").val(value.db).text(value.db));
			});

			//Load DB Users
			$dbUserSelect.find('option').remove().end();
			$dbUserSelect.append($("<option selected></option>").val("-- Select User --").text("-- Select User --"));
			$.each(CPNL_DBUSERS, function (key, value)
			{
				(setupDBUser == value.user)
					? $dbUserSelect.append($("<option selected></option>").val(value.user).text(value.user))
					: $dbUserSelect.append($("<option></option>").val(value.user).text(value.user));
			});

			 //Warn on host name mismatch
			 var address = window.location.hostname.replace('www.', '');
			 ($('#cpnl-host').val().indexOf(address) == -1)
				? $('#cpnl-host-warn').show()
				: $('#cpnl-host-warn').hide();
		}
		else
		{
			//Auto message display
			html += "<b>Details:</b> Unable to connect. Error status is: '" + error + "'. <br/>";
			$('a#s2-cpnl-status-msg').html('<div class="status-badge-fail">failed</div>');
			$('div#s2-cpnl-status-details-msg').html(html);
			$('div#s2-cpnl-status-details').show(500);
			//Inputs
			DUPX.cpnlToggleLogin('off');
		}
		$('a#s2-cpnl-status-msg').show(200);
		$('#s2-cpnl-connect-btn').removeAttr('readonly').val('Connect');
		DUPX.cpnlSetResults();
	}

	DUPX.requestAPI({
		operation: '/cpnl/create_token/',
		timeout: 10000,
		params: {
			host: $('#cpnl-host').val(),
			user: $('#cpnl-user').val(),
			pass: $('#cpnl-pass').val()
		},
		callback: function (data) {
			CPNL_TOKEN = data.result;
			DUPX.requestAPI({
				operation: '/cpnl/get_setup_data/',
				timeout: 30000,
				params: {token: data.result},
				callback: apiAccountActive
			});
		}
	});
};

/**
 *  Enables/Disables database setup and cPanel login inputs  */
DUPX.cpnlToggleLogin = function (state)
{
	//Change btn enabled
	if (state == 'on') {
		$('#cpnl-host, #cpnl-user, #cpnl-pass').addClass('readonly').attr('readonly', 'true');
		$('#s2-cpnl-connect-btn').addClass('disabled').attr('disabled', 'true');
		$('#s2-cpnl-change-btn').removeAttr('disabled').removeClass('disabled').show();
		//Enable cPanel Database
		$('#s2-cpnl-db-opts td').css('color', 'black');
		$('#s2-cpnl-db-opts input, #s2-cpnl-db-opts select').removeAttr('disabled');
		$('#cpnl-host-get-lnk').hide();
	}
	//Change btn disabled
	else {
		$('#cpnl-host, #cpnl-user, #cpnl-pass').removeClass('readonly').removeAttr('readonly');
		$('#s2-cpnl-connect-btn').removeAttr('disabled', 'true').removeClass('disabled');
		$('#s2-cpnl-change-btn').addClass('disabled').attr('disabled', 'true');
		//Disable cPanel Database
		$('#s2-cpnl-db-opts td').css('color', 'silver');
		$('#s2-cpnl-db-opts input, #s2-cpnl-db-opts select').attr('disabled', 'true');
		$('#cpnl-host-get-lnk').show();
	}
}

/**
 *  Updates action status  */
DUPX.cpnlDBActionChange = function ()
{
	var action = $('#cpnl-dbaction').val();
	$('#s2-cpnl-db-opts .s2-warning-manualdb').hide();
	$('#s2-cpnl-db-opts .s2-warning-emptydb').hide();
	$('#s2-cpnl-db-opts .s2-warning-renamedb').hide();
	$('#s2-cpnl-dbname-area1, #s2-cpnl-dbname-area2').hide();

	switch (action) {
		case 'create' :	 $('#s2-cpnl-dbname-area2').show(300);	break;
		case 'empty' :
			$('#s2-cpnl-dbname-area1').show(300);
			$('#s2-cpnl-db-opts .s2-warning-emptydb').show(300);
		break;
		case 'rename' :
			$('#s2-cpnl-dbname-area1').show(300);
			$('#s2-cpnl-db-opts .s2-warning-renamedb').show(300);
		break;
		case 'manual' :
			$('#s2-cpnl-dbname-area1').show(300);
			$('#s2-cpnl-db-opts .s2-warning-manualdb').show(300);
		break;
	}
};

/**
 *  Set the cpnl dbname and dbuser result hidden fields  */
DUPX.cpnlSetResults = function()
{
   var action = $('#cpnl-dbaction').val();
   var dbname = $("#cpnl-dbname-txt").val();
   var dbuser = $("#cpnl-dbuser-txt").val();
   var prefix = $('#cpnl-user').val() + "_";

	if (CPNL_PREFIX) {
		dbname = prefix + $("#cpnl-dbname-txt").val();
		dbuser = prefix + $("#cpnl-dbuser-txt").val();
	}

   (action == 'create')
		? $('#cpnl-dbname-result').val(dbname)
		: $('#cpnl-dbname-result').val($('#cpnl-dbname-select').val());

	($('#cpnl-dbuser-chk').is(':checked'))
		? $('#cpnl-dbuser-result').val(dbuser)
		: $('#cpnl-dbuser-result').val($('#cpnl-dbuser-select').val());
}

DUPX.cpnlPrefixIgnore = function()
{
	if ($('#cpnl_ignore_prefix').prop('checked')) {
		CPNL_PREFIX = false;
		$("#cpnl-prefix-dbname, #cpnl-prefix-dbuser").hide();
	}
	else {
		CPNL_PREFIX = true;
		$("#cpnl-prefix-dbname, #cpnl-prefix-dbuser").show();
	}
	DUPX.cpnlSetResults();
}

/**
 *  Toggle the DB user name type  */
DUPX.cpnlDBUserToggle = function ()
{
	$('#s2-cpnl-dbuser-area1, #s2-cpnl-dbuser-area2').hide();
	 $('#cpnl-dbuser-txt, #cpnl-dbuser-select').removeAttr('disabled');
	 $('#cpnl-dbuser-txt, #cpnl-dbuser-select').removeAttr('required');

	//Use existing
	if ($('#cpnl-dbuser-chk').prop('checked')) {
		$('#s2-cpnl-dbuser-area2').show();
		$('#cpnl-dbuser-select').attr('disabled', 'true');
		$('#cpnl-dbuser-txt').attr('required', 'true');
		$('#cpnl-dbpass').attr('required', 'true');
		$('#cpnl-dbpass').attr('data-parsley-minlength', '7');
	//Create New
	} else {
		$('#s2-cpnl-dbuser-area1').show();
		$('#cpnl-dbuser-select').attr('required', 'true');
		$('#cpnl-dbuser-txt').attr('disabled', 'true');
		$('#cpnl-dbpass').removeAttr('required');
		$('#cpnl-dbpass').removeAttr('data-parsley-minlength');
	}
	DUPX.cpnlSetResults();
}

/**
 * Open an in-line confirm dialog*/
DUPX.confirmDeployment= function ()
{
	DUPX.cpnlSetResults();
	var dbhost = $("#dbhost").val();
	var dbname = $("#dbname").val();
	var dbuser = $("#dbuser").val();

	if ($('#s2-input-form-mode').val() == 'cpnl')  {
		dbhost = $("#cpnl-dbhost").val();
		dbname = $("#cpnl-dbname-result").val();
		dbuser = $("#cpnl-dbuser-result").val();
	}

	var $formInput = $('#s2-input-form');
	var $formResult = $('#s2-result-form');
	$formInput.parsley().validate();
	if (!$formInput.parsley().isValid()) {
		return;
	}

	$( "#dialog-confirm" ).dialog({
	  resizable: false,
	  height: "auto",
	  width: 550,
	  modal: true,
	  position: { my: 'top', at: 'top+150' },
	  buttons: {
		"OK": function() {
			DUPX.runDeployment();
			$(this).dialog("close");
		},
		Cancel: function() {
			$(this).dialog("close");
		}
	  }
	});

	$('#dlg-dbhost').html(dbhost);
	$('#dlg-dbname').html(dbname);
	$('#dlg-dbuser').html(dbuser);
}

/**
 * Performs Ajax post to extract files and create db
 * Timeout (10000000 = 166 minutes) */
DUPX.runDeployment = function ()
{
	var $formInput = $('#s2-input-form');
	var $formResult = $('#s2-result-form');

	var dbhost = $("#dbhost").val();
	var dbname = $("#dbname").val();
	var dbuser = $("#dbuser").val();

	if ($('#s2-input-form-mode').val() == 'cpnl')
	{
		dbhost = $("#cpnl-dbhost").val();
		dbname = $("#cpnl-dbname-result").val();
		dbuser = $("#cpnl-dbuser-result").val();
	}

	$.ajax({
		type: "POST",
		timeout: 10000000,
		dataType: "json",
		url: window.location.href,
		data: $formInput.serialize(),
		beforeSend: function () {
			DUPX.showProgressBar();
			$formInput.hide();
			$formResult.show();
		},
		success: function (data) {
			if (typeof (data) != 'undefined' && data.pass == 1)
			{
				if ($('#s2-input-form-mode').val() == 'basic') {
					$("#ajax-dbaction").val($("#dbaction").val());
					$("#ajax-dbhost").val(dbhost);
					$("#ajax-dbname").val(dbname);
					$("#ajax-dbuser").val(dbuser);
					$("#ajax-dbpass").val($("#dbpass").val());
				}
				else {
					$("#ajax-dbaction").val($("#cpnl-dbaction").val());
					$("#ajax-dbhost").val(dbhost);
					$("#ajax-dbname").val(dbname);
					$("#ajax-dbuser").val(dbuser);
					$("#ajax-dbpass").val($("#cpnl-dbpass").val());
				}

				<?php if($show_multisite) : ?>
					if($("#full-network").is(":checked")) {
						$("#ajax-subsite-id").val(-1);
					} else {
						$("#ajax-subsite-id").val($('#subsite-id').val());
					}
				<?php endif; ?>

				//Advanced Opts
				$("#ajax-dbcharset").val($("#dbcharset").val());
				$("#ajax-dbcollate").val($("#dbcollate").val());
				$("#ajax-logging").val($("#logging").val());
				$("#ajax-json").val(escape(JSON.stringify(data)));
				<?php if (! $GLOBALS['DUPX_DEBUG']) : ?>
					setTimeout(function () {$formResult.submit();}, 1000);
				<?php endif; ?>
				$('#progress-area').fadeOut(700);
			} else {
				DUPX.hideProgressBar();
			}
		},
		error: function (xhr) {
			var status  = "<b>Server Code:</b> "	+ xhr.status		+ "<br/>";
				status += "<b>Status:</b> "			+ xhr.statusText	+ "<br/>";
				status += "<b>Response:</b> "		+ xhr.responseText  + "<hr/>";

			if((xhr.status == 403) || (xhr.status == 500)) {
				status += "<b>Recommendation</b><br/>";
				status += "See <a target='_blank' href='https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-120-q'>this section</a> of the Technical FAQ for possible resolutions.<br/><br/>"
			}
			else if(xhr.status == 0) {
				status += "<b>Recommendation</b><br/>";
				status += "This may be a server timeout and performing a 'Manual Extract' install can avoid timeouts. See <a target='_blank' href='https://snapcreek.com/duplicator/docs/faqs-tech/?reload=1#faq-installer-015-q'>this section</a> of the FAQ for a description of how to do that.<br/><br/>"
			} else {
				status += "<b>Additional Troubleshooting Tips:</b><br/> ";
				status += "&raquo; <a target='_blank' href='https://snapcreek.com/duplicator/docs/'>Help Resources</a><br/>";
				status += "&raquo; <a target='_blank' href='https://snapcreek.com/duplicator/docs/faqs-tech/'>Technical FAQ</a>";
			}

			$('#ajaxerr-data').html(status);
			DUPX.hideProgressBar();
		}
	});
};

/**
 * Returns the windows active url */
DUPX.getcPanelURL = function(id)
{
	var loc      = window.location;
	var newVal	 = loc.protocol + '//' + loc.hostname + ':2038';
	$("#" + id).val(newVal);
};

//DOCUMENT LOAD
$(document).ready(function ()
{
	//Custom Validator
	window.Parsley.addValidator('cpnluser', {
		validateString: function(value) {
		  var prefix = CPNL_PREFIX
				? $('#cpnl-user').val() + "_" + value
				: value;
		  return (prefix.length <= 16);
		},
		messages: {
		  en: 'Database user cannot be more that 16 characters including prefix'
		}
	});

	//Attach Events
	$("#dbaction").on("change", DUPX.basicDBActionChange);
	$("#cpnl-dbaction").on("change", DUPX.cpnlDBActionChange);
	$("#cpnl-dbuser-chk").click(DUPX.cpnlDBUserToggle);
	$('#cpnl-dbname-select, #cpnl-dbname-txt').on("change", DUPX.cpnlSetResults);
	$('#cpnl-dbuser-select, #cpnl-dbuser-txt').on("change", DUPX.cpnlSetResults);

	//Init

	<?php echo ($GLOBALS['FW_CPNL_ENABLE'])  ? 'DUPX.togglePanels("cpanel");' : 'DUPX.togglePanels("basic");'; ?>
	<?php echo ($GLOBALS['FW_CPNL_CONNECT']) ? 'DUPX.cpnlConnect();' : ''; ?>
	$("#cpnl-dbaction").val(<?php echo strlen($GLOBALS['FW_CPNL_DBACTION']) > 0 ? "'{$GLOBALS['FW_CPNL_DBACTION']}'" : 'create'; ?>);
	DUPX.cpnlDBActionChange();
	DUPX.basicDBActionChange();
	DUPX.cpnlDBUserToggle();
	DUPX.cpnlToggleLogin('off');
	DUPX.cpnlSetResults();
	$("*[data-type='toggle']").click(DUPX.toggleClick);

	//MySQL Mode
	$("input[name=dbmysqlmode]").click(function() {
		if ($(this).val() == 'CUSTOM') {
			$('#dbmysqlmode_3_view').show();
		} else {
			$('#dbmysqlmode_3_view').hide();
		}
	});

	if ($("input[name=dbmysqlmode]:checked").val() == 'CUSTOM') {
		$('#dbmysqlmode_3_view').show();
	}
});
</script>
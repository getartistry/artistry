<?php

$html = "<div class='s2-dbconn-result-data'>";

if ($cpnl_dbcreateuser) {
	$html .= "<div class='s2-dbonn-result-newuser'>Unable to test connection when creating a new database user, because the user does not exist. ";
	$html .= "Please continue with the setup by clicking the 'Run Deployment' button. ";
	$html .= "If there are any issues with creating the new database user a message will be displayed on the next screen.</div>";
} else {
	$dbConn		 = DUPX_DB::connect($_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'], null, $_POST['dbport']);
	$dbConnError = (mysqli_connect_error()) ? 'Error: '.mysqli_connect_error() : 'Unable to Connect';

	if ($dbConn) {
		$tstHost = "<div class='dupx-pass'>Connected</div>";
		$dbFound = mysqli_select_db($dbConn, $_POST['dbname']);
		switch ($_POST['dbaction']) {
			case "create" :
				$tstDB	 = ($dbFound) ? "<div class='dupx-fail'>Database already exists.  Use the connect action or change the database name</div>" : "<div class='dupx-pass'>Create New Database '{$_POST['dbname']}'</div>";
				break;
			case "manual":
			case "rename":
			case "empty" :
				$tstDB	 = "<div class='dupx-pass'>Database '{$_POST['dbname']}' found</div>";
				if (!$dbFound) {
					$tstDB = "<div class='dupx-fail'>Unable to connect to database '{$_POST['dbname']}' with user '{$_POST['dbuser']}'.</div>";
					$tstDB .= "<small>The user will be assigned to the database on the next screen if not already assigned.</small>";
				}
				break;
		}
	} else {
		$tstHost = "<div class='dupx-fail'>{$dbConnError}</div>";
		$tstDB	 = "<div class='dupx-fail'>Unable to connect to Host</div>";
	}


	$dbversion_info		 = DUPX_DB::getInfo($dbConn);
	$dbversion_info		 = empty($dbversion_info) ? 'no connection' : $dbversion_info;
	$dbversion_info_fail = version_compare(DUPX_DB::getVersion($dbConn), '5.5.3') < 0;

	$dbversion_compat		 = DUPX_DB::getVersion($dbConn);
	$dbversion_compat		 = empty($dbversion_compat) ? 'no connection' : $dbversion_compat;
	$dbversion_compat_fail	 = version_compare($dbversion_compat, $GLOBALS['FW_VERSION_DB']) < 0;

	$tstInfo = ($dbversion_info_fail) ? "<div class='dupx-notice'>{$dbversion_info}</div>" : "<div class='dupx-pass'>{$dbversion_info}</div>";

	$tstCompat = ($dbversion_compat_fail) ? "<div class='dupx-notice'>This Server: [{$dbversion_compat}] -- Package Server: [{$GLOBALS['FW_VERSION_DB']}]</div>" : "<div class='dupx-pass'>This Server: [{$dbversion_compat}] -- Package Server: [{$GLOBALS['FW_VERSION_DB']}]</div>";


	$html .= <<<DATA
	<small>
		Using Connection String:<br/>
		Server={$_POST['dbhost']}; Database={$_POST['dbname']}; Uid={$_POST['dbuser']}; Pwd={$_POST['dbpass']}; Port={$_POST['dbport']}
	</small>
	<table class='details'>
		<tr>
			<td>Host:</td>
			<td>{$tstHost}</td>
		</tr>
		<tr>
			<td>Database:</td>
			<td>{$tstDB}</td>
		</tr>
		<tr>
			<td>Version:</td>
			<td>{$tstInfo}</td>
		</tr>
		<tr>
			<td>Compatibility:</td>
			<td>{$tstCompat}</td>
		</tr>
	</table>
DATA;

	//--------------------------------
	//WARNING: DB has tables with create option
	if ($_POST['dbaction'] == 'create') {
		$tblcount = DUPX_DB::countTables($dbConn, $_POST['dbname']);
		$html .= ($tblcount > 0) ? "<div class='warn-msg'><b>WARNING:</b> ".sprintf(ERR_DBEMPTY, $_POST['dbname'], $tblcount)."</div>" : '';
	}

	//WARNNG: Input has utf8 data
	$dbConnItems = array($_POST['dbhost'], $_POST['dbuser'], $_POST['dbname'], $_POST['dbpass']);
	$dbUTF8_tst	 = false;
	foreach ($dbConnItems as $value) {
		if (DUPX_U::isNonASCII($value)) {
			$dbUTF8_tst = true;
			break;
		}
	}

	//WARNING: UTF8 Data in Connection String
	$html .= (!$dbConn && $dbUTF8_tst) ? "<div class='warn-msg'><b>WARNING:</b> ".ERR_TESTDB_UTF8."</div>" : '';

	//NOTICE: Version Too Low
	$html .= ($dbversion_info_fail) ? "<div class='warn-msg'><b>NOTICE:</b> ".ERR_TESTDB_VERSION_INFO."</div>" : '';

	//NOTICE: Version Incompatibility
	$html .= ($dbversion_compat_fail) ? "<div class='warn-msg'><b>NOTICE:</b> ".ERR_TESTDB_VERSION_COMPAT."</div>" : '';
}

$html .= '<div class="s2-dbconn-result-faq"><a href="https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-100-q" target="_blank">Click here for connection issues?</a></div>';
$html .= '<input type="button" onclick="$(this).parents().eq(1).hide(500)" value="Hide Message"><br/>';
$html .= "</div>";
die($html);


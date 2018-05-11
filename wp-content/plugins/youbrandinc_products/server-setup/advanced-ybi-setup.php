<?php
	if (!function_exists('wp_verify_nonce')) { require_once(ABSPATH .'wp-includes/pluggable.php');  }
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	include_once( 'advanced-ybi-setup-functions.php' );
	
	$EDIT_LOADED_PHP_INI_FILE = '';
	$EDIT_LOADED_HTACCESS_FILE = '';
	
	// create some file specific constants, should go back and use these throughout but created them late to the file, maybe in a later build
	$ADMIN_URL = get_admin_url();
	$REAL_DOCUMENT_ROOT = realpath($_SERVER["DOCUMENT_ROOT"]);
	$SITE_URL = get_bloginfo('url') . '/';
	
	
	// this is the message returned on an action, like a php.ini file update	
	$returnMessage = '';
	
	$EDIT_LOADED_PHP_INI_FILE = '';
	$EDIT_LOADED_HTACCESS_FILE = '';
	$inipath = php_ini_loaded_file();

		// htaccess_file_to_edit
		// php_ini_file_to_edit
		
		// get_file_to_edit_form
		// get_file_to_edit - hidden
		// ybi_get_file_to_edit_nonce
		// name php_file or .htaccess_file
		
	if($_GET['manualCurateThisInstall'] == "yes")
	{
		$dbVersion = get_option('curate-this-version');
		if($dbVersion != '')
			update_option('curate-this-version',CURATETHIS_VERSION);	
		else
			add_option('curate-this-version',CURATETHIS_VERSION);
			
		$returnMessage .= '<p>CurateThis Manual Install Complete (note: this does not copy the file).</p>';
	}
	


	if($_GET['forceCurateThisRecopy'] == "yes")
	{
		delete_option( 'curate-this-version' );
			
		$returnMessage .= '<p>CurateThis will now be prompoted to re-copy.</p>';
	}
		
	$turnOffIoncube = $_GET['turnOffIoncubeCheck'];
	if($turnOffIoncube != '')
	{
		if($turnOffIoncube == 'yes')
		{
			update_option('ybi_turn_off_ioncube_check','yes');	
		}
		if($turnOffIoncube == 'no')
		{
			update_option('ybi_turn_off_ioncube_check','no');	
		}

		$returnMessage .= '<p>Ioncube check changed: ' . $turnOffIoncube . '</p>';
	}
	
	$ybi_cu_use_plugin_files = $_GET['ybi_cu_use_plugin_files'];
	if($ybi_cu_use_plugin_files != '')
	{
		if($ybi_cu_use_plugin_files == 'yes')
		{
			update_option('ybi_cu_use_plugin_files','yes');	
		}
		if($ybi_cu_use_plugin_files == 'no')
		{
			delete_option('ybi_cu_use_plugin_files');	
		}

		$returnMessage .= '<p>Local Curation Suite Files changed: ' . $ybi_cu_use_plugin_files . '</p>';
	}
	
	$ybi_turn_on_error_reporting = $_GET['ybi_turn_on_error_reporting'];
	if($ybi_turn_on_error_reporting != '')
	{
		if($ybi_turn_on_error_reporting == 'on')
		{
			update_option('ybi_turn_on_error_reporting','on');	
		}
		else
		{
			delete_option('ybi_turn_on_error_reporting');	
		}

		$returnMessage .= '<p>Error reporting set to: ' . $ybi_turn_on_error_reporting . '</p>';
	}
	
	$valueToChange = $_GET['ybi_ignore_licensing_issues'];
	if($valueToChange != '')
	{
		if($valueToChange == 'off')
		{
			update_option('ybi_ignore_licensing_issues','off');	
		}
		if($valueToChange == 'on')
		{
			delete_option('ybi_ignore_licensing_issues');	
		}

		$returnMessage .= '<p>Licesning Issues check changed: ' . $valueToChange . '</p>';
	}

	if($_POST['get_file_to_edit'] == 'Y'&&wp_verify_nonce($_POST['ybi_get_file_to_edit_nonce'], 'ybi_get_file_to_edit_nonce')) { 
		$EDIT_LOADED_HTACCESS_FILE = $_POST['htaccess_file'];
		$EDIT_LOADED_PHP_INI_FILE = $_POST['php_file'];
	
	}
	if($EDIT_LOADED_PHP_INI_FILE == '')
		$EDIT_LOADED_PHP_INI_FILE = $inipath;
			
	if($EDIT_LOADED_HTACCESS_FILE == '')
		$EDIT_LOADED_HTACCESS_FILE = $REAL_DOCUMENT_ROOT."/.htaccess";
	
	// update the .htaccess file
	if($_POST['ybi_adv_setup_hidden_htaccess'] == 'Y'&&wp_verify_nonce($_POST['ybi_adv_setup_nonce'], 'ybi_adv_setup_nonce')) { 
		// get the content
		$htaccess_content = $_POST['htaccess_content'];

		// for this we grab the document root
		$file = $_POST['htaccess_file_to_edit']; //$REAL_DOCUMENT_ROOT."/.htaccess";
		// get the current file contents
		$fileContents = file_get_contents($file);
		// here we create a backup in the same directory
		$returnMessage .= ybi_createFileLocationBackup($file, $fileContents);
		$returnMessage .= '<p>' . ybi_createFileTextDownloadBackup($file, "htaccess_backup") . '</p>';
		// now let's take the text from the textarea down below and create/overwrite the existing file
		file_put_contents($file, trim(stripslashes($htaccess_content)));
		// give a message
		$returnMessage = '.htaccess updated. Backup: ' . $returnMessage;
	}
	// updating or adding a php.ini file
	if($_POST['ybi_php_hidden'] == 'Y'&&wp_verify_nonce($_POST['ybi_php_nonce'], 'ybi_php_nonce')) { 
		// get the current loaded file
		$file = $_POST['php_ini_file_to_edit']; //php_ini_loaded_file();
		// get the content of the current loaded file
		$fileContents = file_get_contents($file);
		// create a backup in the same directory
		$theBackupMessage =ybi_createFileLocationBackup($file, $fileContents);
		$returnMessage .= '<p>' . ybi_createFileTextDownloadBackup($file, "php_ini_backup") . '</p>';
		// get the content from the php.ini textarea down below
		$php_ini_content = $_POST['php_ini_content'];
		
		// for some hosts there needs to be a php5.ini version, so here we check if that's what needs to be created
		$create_five_version = $_POST['create_five_version'];
		if($create_five_version)
			$file = str_replace("php.ini","php5.ini",$file); // if so, then we replace php.ini with php5.ini in the file name
	
		// do we create a php.ini file on the root of this install? 
		// For instance, there might be on on the public_html but we want to take what's in that one and change things and then put a new one in our directory
		$create_this_install_php_ini = $_POST['create_this_install_php_ini'];
		if($create_this_install_php_ini)
		{
			$phpIniFileName = "php.ini";
			if($create_five_version)
				$phpIniFileName = "php5.ini"; // php5.ini creation was selected so we do that here as well
	
			$file = $REAL_DOCUMENT_ROOT."/".$phpIniFileName;
		}
		// get the php.ini content from the textarea
		$php_ini_content = stripslashes($php_ini_content);
		// create the file
		file_put_contents($file, $php_ini_content);
		// add a message to be returned
		$returnMessage .= '<br>Updated or Created: ' . $file;
		// in addition to updating the file we can also choose to create a wp-admin php.ini file. For some installs this needs to be present to work
		$create_wp_admin_php_ini = $_POST['create_wp_admin_php_ini'];
		if($create_wp_admin_php_ini)
		{
			// create php.ini or if it needs to be php5 we create taht
			$phpIniFileName = "php.ini";
			if($create_five_version)
				$phpIniFileName = "php5.ini";
			
			// create the file and create or overwrite the file
			$file = $REAL_DOCUMENT_ROOT."/wp-admin/".$phpIniFileName;
			file_put_contents($file, $php_ini_content);
			// provide return message
			$returnMessage .= '<br>Created or Updated: ' . $file;
		}
		// here we are appending the backup messages so we know there were backups made
		$returnMessage .= $returnMessage . $theBackupMessage;
	}
?>
<style type="text/css">
.file_name_header {margin-left: 30px; float:left; font-weight:bold; font-size: 15px; }
.file_name {color:#900;}
.submit_row { padding: 10px 0; text-align:right;}
.textarea_w {width: 60%; float:left;}
.support_text_sidebar { width: 38%; float:left; margin-left: 2%; margin-top: 43px;}
.backup_files { display:none;}
.bad {color:#F00;}
.good { color:#0C0;}
.files_table, .files_table th, .files_table td {text-align:center;}
.files_table th {    padding: 14px 22px;
    text-align: center;}
.files_table td {padding: 5px;}
.files_table tr {}
.subtext {font-size: 11px; font-weight:normal;}
.odd {background: #D6D6D6;}
.even{ }
.odd td {}
.even td{}
.quick_links {    
	float: left;
    list-style-type: none;
    margin: 0;
    padding: 20px 0;
    text-align: center;}

.quick_links li {display: inline; padding: 0 15px;}
.quick_links .first {padding: 0 15px 0 0;}


.plugInListWrapper {clear:both; width:95%; margin:0 auto;}
.plugInListLine {clear:both;}
.plugInListHeadline {font-weight:bold;}
.plugInListHeadDescription {text-align:left; font-size:16px; padding: 10px 0;}
.plugInListNumber, .plugInListName, .plugInListVersion, plugInListDescription {float:left; padding:0 10px;}
.plugInListNumber {width:15px;}
.plugInListName {width:150px;}
.plugInListVersion {width:80px;}
.plugInListDescription {margin-left:305px; margin-bottom:15px;}
.plugInIsInactive {font-style:italic; text-decoration:line-through;}
.plugInBlock_active {display: none;}
.plugInBlock_inactive {display: none;}
</style>
<script>
jQuery(document).ready(function($){
	
	$(".show_backup_files").click(function() {
		   var elem = jQuery(this);
		   var theName = elem.attr('name')
			if($("."+theName).css('display') == 'block')
				$("."+theName).css({"display":"none","visibility":"hidden"});		
			else
				$("."+theName).css({"display":"block","visibility":"visible"});
	})
	
	$(".scroll_to_bottom").click(function() {
		   var elem = jQuery(this);
		   var theName = elem.attr('name')
	
			$("#"+theName).animate({
				scrollTop:$("#"+theName)[0].scrollHeight - $("#"+theName).height()
				},100,function(){
			})
	})
	
	$(".scroll_to_top").click(function() {
		   var elem = jQuery(this);
		   var theName = elem.attr('name')
	
			$("#"+theName).animate({
				scrollTop:$("#"+theName).height() - 1000
				},100,function(){
			})
	})
	
	$(function() {
	  $('.easy_copy_textarea').click(function() {
		   var elem = jQuery(this);
		   var theName = elem.attr('name')
    		$('.'+theName).select();
	  });
});
		
});
</script>
<div class="wrap">
    <div class="products_header main_heading" style="background: url(<?php echo plugins_url('youbrandinc_products/i/you-brand-guys-32.png');?>); background-repeat: no-repeat; background-position: 0px 7px;">
		 <h2><?php echo _e('Advanced You Brand, Inc. Setup Page'); ?></h2>
    </div>
    <?php if($returnMessage != ''): ?>
    <div id="message" class="updated">
    	<h1>Files Updated</h1>
        <p><?php echo $returnMessage; ?></p>
    </div>
    <?php endif; ?>
		<?php //echo $REAL_DOCUMENT_ROOT . 'wp-admin/php5.ini'; ?>
        <div>
        <ul class="quick_links">
	        <li class="single_link first"><strong>Turn Admin Off:</strong> <a href="admin.php?page=youbrandinc-support-news&success=yes&YBIAdmin=off">Turn Off</a></li>
        	<li class="single_link"><i class="fa fa-upload fa-lg"></i> <a href="<?php echo $ADMIN_URL; ?>plugin-install.php?tab=upload" target="_blank">Upload Plugin</a></li>
        	<li class="single_link"><i class="fa fa-upload fa-lg"></i> <a href="<?php echo $ADMIN_URL; ?>theme-install.php?tab=upload" target="_blank">Upload Theme</a></li>
    	    <li class="single_link"><a href="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/super-check-server.php" target="_blank">Advanced Server Information <i class="fa fa-external-link-square fa-lg"></i></a></li>
        </ul>
        </div>
        <div style="clear: both;">
        <ul class="quick_links">
            <?php 
			if(get_option('ybi_turn_off_ioncube_check')== 'yes'): ?>
		        <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&turnOffIoncubeCheck=no">Turn On Ioncube Check</a></li>
                <?php else: ?>
                <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&turnOffIoncubeCheck=yes">Turn Off Ioncube Check</a></li>
   			<?php endif; ?>
            <?php 
			if(get_option('ybi_ignore_licensing_issues')== 'off'): ?>
		        <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&ybi_ignore_licensing_issues=on">Turn On Licensing Problems</a></li>
                <?php else: ?>
                <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&ybi_ignore_licensing_issues=off">Turn Off Licensing Problems</a></li>
   			<?php endif; ?>
         
            <?php 
			if(get_option('ybi_turn_on_error_reporting')== ''): ?>
		        <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&ybi_turn_on_error_reporting=on">Turn On Error Reporting</a></li>
                <?php else: ?>
                <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&ybi_turn_on_error_reporting=off">Turn Off Error Reporting</a></li>
   			<?php endif; ?>
            
            <?php 
			if(get_option('ybi_cu_use_plugin_files')== ''): ?>
		        <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&ybi_cu_use_plugin_files=yes">Turn On CU Local Files</a></li>
                <?php else: ?>
                <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&ybi_cu_use_plugin_files=off">Turn Off CU Local Files</a></li>
   			<?php endif; ?>
        </ul>
        </div>
        <div style="clear: both;">
        <ul class="quick_links">  
	        <li class="single_link">Curation Traffic</li>
	        <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&manualCurateThisInstall=yes">Manual CurateThis</a></li>
	        <li class="single_link"><a href="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>&forceCurateThisRecopy=yes">Force Re-Copy CurateThis</a></li>
        </ul>
        </div>


	        <p style="clear:both;"><?php if(extension_loaded("IonCube Loader")):?> <span style="color: green; font-size: 18px;"><i class="fa fa-thumbs-o-up fa-lg"></i> IonCube is Active</span> 
			<?php else: ?><span style="color: red; font-size: 18px;"><i class="fa fa-exclamation-triangle fa-lg"></i> Ioncube Not Active</span><?php endif; ?>
            <span style="font-size: 18px; font-weight:bold; padding-left: 15px;">PHP: <?php echo phpversion(); ?></span>
            <span style="font-size: 14px; font-weight:bold; padding-left: 15px;">allow_url_fopen=<?php echo getOnOff(ini_get('allow_url_fopen') == 1,true); ?> | 
            allow_url_include=<?php echo getOnOff(ini_get('allow_url_include') == 1,true); ?> | 
            ioncube check: <?php echo getGoodBadText(get_option('ybi_turn_off_ioncube_check') == 'yes',true) ?>
            
            </span>
       
        
        </p>
        
        <p style="font-size: 17px;"><strong>Loaded php.ini file:</strong><code><?php echo $inipath; ?></code> - writeable: <?php echo getGoodBadText(is_writable($inipath),true); ?></p>
		<p></p>
        <p><strong>Your document root is:</strong><code><?php echo $REAL_DOCUMENT_ROOT; ?></code></p>
        <p><strong>PHP_uname</strong>: <?php echo php_uname(); ?> - <strong>PHP_OS</strong>:  <?php echo PHP_OS; ?></p>

<?php 	
	// get an array of all plugins installed
	$array_Plugins = get_plugins();
	// display the active ones (div is initally hidden)
	echo get_my_pluginlist($array_Plugins, "active");
	// display the inactive ones (div is initally hidden)
	echo get_my_pluginlist($array_Plugins, "inactive");

	// get the path
	$current_path = getcwd(); // get the current path to where the file is located
	$folder = explode("/", $current_path); // divide the path in parts (aka folders)
	$blog = $folder[8]; // the blog's folder is the number 8 on the path

	// below we loop thru an array of folder names, it should be the full path. We then check if files exist in those directories and if they are writable.
	// this way we have a full picture of all the php.ini's and .htaccess files that maybe on the server
?>
        <form name="get_file_to_edit_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php wp_nonce_field('ybi_get_file_to_edit_nonce', 'ybi_get_file_to_edit_nonce'); ?>
        <input type="hidden" name="get_file_to_edit" value="Y">
    <table style="" cellpadding="0" cellspacing="0" class="files_table">
    <tr class="title_row">
		<?php  ?>
        <th>File</th>
        <?php 
          foreach($folder as $value)
        {
                if($value != "")
                {
         ?>
         <th>
		 	<?php echo $value; ?><br />
			<span class="subtext">exists : writable</span>
		</th>
        <?php }} ?>
         <th>
		 	wp-content<br />
			<span class="subtext">exists : writable</span>
		</th>
	</tr>

<?php 
// this is creating a single table row for the file passed
// also note that the $currentFolder is being added too on each pass, that way your cycling through the directory structure that was exploded above.
	function getJustPHP($inText)
	{
		$inText = str_replace("5.ini","",$inText);
		$inText = str_replace(".ini","",$inText);
		$inText = str_replace(".","",$inText);
		return $inText;	
	}

function getFileRow($folder, $currentFile, $class, $in_EDIT_LOADED_HTACCESS_FILE, $in_EDIT_LOADED_PHP_INI_FILE)
{

 ?>
    <tr class="<?php echo $class; ?>">
        <td><?php echo $currentFile; ?></td>

		<?php
		// current folder
        $currentFolder = "/";
		// we'll use this at the end
		$wp_content_folder = "";
		$totalColumns = 1;
        foreach($folder as $value)
        {
            if($value != "")
            {
				// if the current folder is the wp-admin folder then we create the full path for the wp-content folder, that way we have it in the last column to check the files in that location
				if($value == "wp-admin")
					$wp_content_folder = $currentFolder . "wp-content/";

				// add this folder to the path with trailing slash
	            $currentFolder .= $value . "/";
				// does the file exist?
    	        $itExists = file_exists($currentFolder  . $currentFile);
				// this function returns Yes/NO and red, green based on if we say green is good
        	    $existsText = getGoodBadText($itExists,true);
                if($itExists)
                {
					if($currentFile == "php.ini" || $currentFile == "php5.ini")
						$currentValue = $in_EDIT_LOADED_PHP_INI_FILE;
					else
						$currentValue = $in_EDIT_LOADED_HTACCESS_FILE;
					
					$fullFileWithPath = $currentFolder .$currentFile;
					// if it exists we show yes and then check if it's writable  
					
			        echo '<td><label><input type="radio" value="'. $fullFileWithPath.'" name="'.getJustPHP($currentFile).'_file" '.checked($fullFileWithPath, $currentValue).' />';
                    echo ''. $existsText . ' | ';
                    echo '' . getGoodBadText(is_writable($currentFolder  . $currentFile),true) . '</label></td>';
                }
                else
                {
					// if it doesn't exist we say no
                    echo '<td>'. $existsText.'</td>';
                }
            }
			$totalColumns++;
        } // foreach($folder as $value)
			// finally before we finish this row we check the wp-content folder for these files
			$itExists = file_exists($wp_content_folder  . $currentFile);
			// get the good or bad text
			$existsText = getGoodBadText($itExists,true);
			if($itExists)
			{
				// same as above if exists we show and see if it's writable
					if($currentFile == "php.ini" || $currentFile == "php5.ini")
						$currentValue = $in_EDIT_LOADED_PHP_INI_FILE;
					else
						$currentValue = $in_EDIT_LOADED_HTACCESS_FILE;
					
					$fullFileWithPath = $wp_content_folder .$currentFile;

		        echo '<td><label><input type="radio" value="'. $fullFileWithPath .'" name="'.getJustPHP($currentFile).'_file" ' .checked($fullFileWithPath, $currentValue).' />';
				echo "". $existsText . " |  ";
				echo getGoodBadText(is_writable($wp_content_folder  . $currentFile),true) . "</label></td>";
			}
			else
			{
				// doesn't exist so no
				echo '<td>'. $existsText.'</td>';
			}
			$totalColumns++;
			return $totalColumns;

	?>  
    
	</tr>
<?php } ?>
	<?php getFileRow($folder,"php.ini","odd",$EDIT_LOADED_HTACCESS_FILE, $EDIT_LOADED_PHP_INI_FILE); ?>
   	<?php getFileRow($folder,"php5.ini","even",$EDIT_LOADED_HTACCESS_FILE, $EDIT_LOADED_PHP_INI_FILE); ?>
   	<?php $totalColunns = getFileRow($folder,".htaccess","odd",$EDIT_LOADED_HTACCESS_FILE, $EDIT_LOADED_PHP_INI_FILE); ?>
<tr>
	<td colspan="<?php echo $totalColunns; ?>"><div style="text-align: right;"><input type="submit" name="Submit" value="<?php _e('Load Files', 'ct_trdom' ) ?>" class="button button-primary button-large" /></div></td>
</tr>

    </table>

    </form>    

        <p><strong>Your script file name:</strong><code><?php echo $_SERVER['SCRIPT_FILENAME']; ?></code></p>
<div style="float:left; width: 406px;">
  		<p class="server_check"><a href="javascript:;" class="green_button load_ioncube" name="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/copy-ioncube.php">Copy Ioncube to Your Server <i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
		<iframe style="clear: both;" class="copy_ioncube_iframe" id="server_info_iframe" src=""></iframe>
</div> 
<div style="float:left; width: 310px;">
		<p class="server_check"><a href="javascript:;" class="green_button advanced_check" 
        name="<?php echo get_bloginfo('url'); ?>/wp-admin/check-server.php?checkType=all">Check Installation <i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
 		<?php getServerCheckIframe('advanced_check','all'); ?> 
</div>   
<div style="float: left;">
<?php 
/*
	$directory = $REAL_DOCUMENT_ROOT;
	// get the script filename
	$scriptFileName = $_SERVER['SCRIPT_FILENAME'];
	// replace where we are currently at (not the best as if they change wp-admin this would break).
	$scriptFileName = str_replace("wp-admin/admin.php","",$scriptFileName);
	// set the directory to, probably could use dirname(__FILE__) 
	$directory = $scriptFileName . 'wp-content/plugins/youbrandinc_products/server-setup/backups/';
*/
	// find the backups directory
	$directory = dirname(__FILE__) . '/backups/';
	// open this directory up biatch
	$handler = opendir($directory);
	// set some initial arrays
	$YBIhtaccessBackupArr = array();
	$YBIphpIniBackupArr = array();
	while ($file = readdir($handler)) {
		// if file isn't this directory or its parent, add it to the results
		if ($file != "." && $file != "..") {
				// check with regex that the file format is what we're expecting and not something else
				if (strpos($file,"htaccess_backup") !== false) {
					$YBIhtaccessBackupArr[] = $file;
				}
				if (strpos($file,"php_ini_backup") !== false) {
					$YBIphpIniBackupArr[] = $file;
				}
		}
	}
	// now we find any files in the root as backups
	$directory = $REAL_DOCUMENT_ROOT;
	// open this directory up biatch
	$handler = opendir($directory);
	// set some initial arrays
	$ROOThtaccessBackupArr = array();
	$ROOTphpIniBackupArr = array();
	while ($file = readdir($handler)) {
		// if file isn't this directory or its parent, add it to the results
		if ($file != "." && $file != "..") {
				// check with regex that the file format is what we're expecting and not something else
				if (strpos($file,"_htaccess_ybi_backup") !== false) {
					$ROOThtaccessBackupArr[] = $file;
				}
				if (strpos($file,"php_ini_ybi_backup") !== false) {
					$ROOTphpIniBackupArr[] = $file;
				}
		}
	}

	$totalHTAccessBackups = count($YBIhtaccessBackupArr) + count($ROOThtaccessBackupArr);
	$totalphpIniBackups = count($YBIphpIniBackupArr) + count($ROOTphpIniBackupArr);

 ?>
	<a href="javascript:;" name="htaccess_backup_files" class="show_backup_files"><i class="fa fa-files-o"></i> .htaccess file backups (<?php echo $totalHTAccessBackups; ?>)</a>
     | <a href="javascript:;" name="php_ini_backup_files" class="show_backup_files"><i class="fa fa-files-o"></i> php.ini file backups (<?php echo $totalphpIniBackups; ?>)</a>
    <div class="backup_files htaccess_backup_files">
        <ul>
			<?php 
			
			foreach ($YBIhtaccessBackupArr as &$value) { ?>
                <li><a href="<?php echo plugins_url() . '/youbrandinc_products/' ?>/server-setup/backups/<?php echo $value; ?>" target="_blank"><?php echo $value; ?></a></li>
                
            <?php }
			foreach ($ROOThtaccessBackupArr as &$value) { ?>
                <li><a href="<?php echo $SITE_URL . $value; ?>" target="_blank"><?php echo $value; ?> (root)</a></li>
            <?php } ?>
        </ul>
    </div>
	<div class="backup_files php_ini_backup_files">
        <ul>
			<?php foreach ($YBIphpIniBackupArr as &$value) { ?>
                <li><a href="<?php echo plugins_url() . '/youbrandinc_products/' ?>/server-setup/backups/<?php echo $value; ?>" target="_blank"><?php echo $value; ?></a></li>
            <?php }
				foreach ($ROOTphpIniBackupArr as &$value) { ?>
                <li><a href="<?php echo $SITE_URL . $value; ?>" target="_blank"><?php echo $value; ?> (root)</a></li>
            <?php } ?>
        </ul>
    </div>
</div>

<div style="clear: both; overflow:auto; margin: 0 auto"></div>       
    <div style="margin: 20px 0; clear:both;">
        <form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php wp_nonce_field('ybi_adv_setup_nonce', 'ybi_adv_setup_nonce'); ?>
        <input type="hidden" name="ybi_adv_setup_hidden_htaccess" value="Y">
        <input type="hidden" name="htaccess_file_to_edit" value="<?php echo $EDIT_LOADED_HTACCESS_FILE; ?>">
		<div>
        	<div class="textarea_w">
                <div class="submit_row">
                    <span class="file_name_header">.htaccess file: <span class="file_name"><?php echo $EDIT_LOADED_HTACCESS_FILE; ?></span></span>
                    <input type="submit" name="Submit" value="<?php _e('Update .htaccess File', 'ct_trdom' ) ?>" class="button button-primary button-large" />                
                </div>                
		        <textarea rows="15" style="width: 100%;" name="htaccess_content"><?php echo file_get_contents($EDIT_LOADED_HTACCESS_FILE);  ?></textarea>
            </div>
            <div class="support_text_sidebar">
            	<p class="advanced_server_check"><a href="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/super-check-server.php" target="_blank">Advanced Server Information <i class="fa fa-mail-forward fa-lg"></i></a></p>
		        <p>GoDaddy<br /><code>AddHandler x-httpd-php5-3 .php</code></p>
		        <p>HostGator<br /><code>AddType application/x-httpd-php53 .php</code></p>
		        <p>Bluehost/HostMonster<br /><code>AddHandler application/x-httpd-php54 .php</code></p>
            </div>
       </div>
        </form>
	</div>

    <div style="padding: 20px 0; clear:both;">
        <form name="oscimp_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <?php wp_nonce_field('ybi_php_nonce', 'ybi_php_nonce'); ?>
        <input type="hidden" name="ybi_php_hidden" value="Y">
        <input type="hidden" name="php_ini_file_to_edit" value="<?php echo $EDIT_LOADED_PHP_INI_FILE; ?>">
		<div>
        	<div class="textarea_w">
                <div class="submit_row">
				<div style="padding: 10px 0; font-size: 13px; float:left; clear:both; width: 100%;">
				<label style="padding: 10px;"><input type="checkbox" value="1" name="create_five_version" />Create PHP5.ini (works with <i class="fa fa-angle-double-right"></i>)</label>
				<label style="padding: 10px;"><input type="checkbox" value="1" name="create_this_install_php_ini" />Create local php.ini</label>
				<label style="padding: 10px;"><input type="checkbox" value="1" name="create_wp_admin_php_ini" />Create wp-admin php.ini</label>


                <input type="submit" name="Submit" value="<?php _e('Update php.ini File', 'ct_trdom' ) ?>" class="button button-primary button-large" />
                </div>
                <div class="submit_row">
                    <span class="file_name_header">PHP.ini file: <span class="file_name"><?php echo $EDIT_LOADED_PHP_INI_FILE; // file set above ?></span></span>
                <a href="javascript:;" name="php_ini_textarea" class="scroll_to_top"><i class="fa fa-arrow-up"></i> To Top</a> | 
					<a href="javascript:;" name="php_ini_textarea" class="scroll_to_bottom">To Bottom <i class="fa fa-arrow-down"></i></a>
                </div>

	       </div>
	        	<textarea rows="35" id="php_ini_textarea" style="width: 100%" name="php_ini_content"><?php echo file_get_contents($EDIT_LOADED_PHP_INI_FILE);  ?></textarea></form>
            </div>
            <div class="support_text_sidebar" style="margin-top: 101px;">
        <p style="clear:both;"><?php if(extension_loaded("IonCube Loader")):?> <span style="color: green; font-size: 18px;"><i class="fa fa-thumbs-o-up fa-lg"></i> IonCube is Active</span> 
			<?php else: ?><span style="color: red; font-size: 18px;"><i class="fa fa-exclamation-triangle fa-lg"></i> Ioncube Not Active</span><?php endif; ?>
            <span style="font-size: 18px; font-weight:bold; padding-left: 15px;">PHP: <?php echo phpversion(); ?></span>
            <span style="font-size: 14px; font-weight:bold; padding-left: 15px;">allow_url_fopen=<?php echo getOnOff(ini_get('allow_url_fopen') == 1,true); ?> | allow_url_include=<?php echo getOnOff(ini_get('allow_url_include') == 1,true); ?></span>
        </p>
                
                <p class="advanced_server_check"><a href="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/super-check-server.php" target="_blank">Advanced Server Information <i class="fa fa-mail-forward fa-lg"></i></a></p>
				<div style="clear: both; margin: 0 auto; overflow:auto;">
                <p><code>allow_url_fopen = On</code></p>
                <p><code>allow_url_include = On</code></p>                
				<textarea rows="2" style="width: 100%" name="all_url_textarea" class="easy_copy_textarea all_url_textarea">allow_url_fopen = On
allow_url_include = On</textarea>
		        <p><strong>GoDaddy (after copy)</strong></p>
				<textarea rows="3" style="width: 100%" name="godaddy_textarea" class="easy_copy_textarea godaddy_textarea">apc.enabled=0
[Zend]
zend_extension=<?php echo realpath($_SERVER["DOCUMENT_ROOT"]); ?>/ioncube_loader_lin_5.3.so
zend_extension=<?php echo realpath($_SERVER["DOCUMENT_ROOT"]); ?>/ioncube_loader_lin_5.4.so
</textarea>
<p><strong>HostGator</strong></p>
<textarea rows="3" style="width: 100%" name="hostgator_textarea" class="easy_copy_textarea hostgator_textarea">zend_extension="/usr/local/IonCube/ioncube_loader_lin_5.3.so
zend_extension_ts="/usr/local/IonCube/ioncube_loader_lin_5.3_ts.so</textarea>
<p><strong>Bluehost/HostMonster</strong></p>
<textarea rows="5" style="width: 100%" name="bluehost_textarea" class="easy_copy_textarea bluehost_textarea">zend_loader.disable_licensing=0
zend_extension=/usr/php/54/usr/lib64/php/modules/ioncube_loader_lin.so
zend_extension=/usr/php/54/usr/lib64/php/modules/ZendGuardLoader.so</textarea>

                </div>
                
            </div>
       </div>



    </div>
</div><!--wrap-->
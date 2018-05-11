<?php 
global $isIoncubeActive, $isLessThanPHP53;

echo $isIoncubeActive;
$serverCheckURL = get_bloginfo('url') . "/wp-admin/check-server.php";

function getServerInstallText()
{
	$current_path = getcwd(); // get the current path to where the file is located
	$folder = explode("/", $current_path); // divide the path in parts (aka folders)
	$blog = $folder[3]; // the blog's folder is the number 8 on the path

	// $root = path without the blog installation folder.
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
	
?>
    <p>PHP Version: <?php echo  phpversion(); ?></p>
    <p>Ioncube Installed: <?php if(extension_loaded("IonCube Loader")): echo "Yes"; else: echo "No"; endif; ?></p>
    <p><?php echo dirname(__FILE__); ?></p>
	<p>Your Root folder: <?php echo $root; ?></p>
	<p>Your Blog folder: <?php echo $blog; ?></p>
<?php
}


function getCheckTypeSupportText($inCheckType)
{
	$isIoncubeActive = extension_loaded("IonCube Loader");
	$isLessThanPHP53 = version_compare(phpversion(), '5.3', '<');
	if($inCheckType == "php")
	{
		if ($isLessThanPHP53)
			$theReturnText = '<span style="color: red;" class="support_text"><i class="fa fa-exclamation-triangle fa-lg"></i> Your server is running PHP Version ' . phpversion() . ' you need at least 5.3+</span>';
		else
			$theReturnText = '<span style="color: green;" class="support_text"><i class="fa fa-check-square-o fa-lg"></i> You\'re good here. Your server is running PHP Version ' . phpversion() . ' you can check off this step</span>';
	}
	if($inCheckType == "ioncube")
	{
		if ($isIoncubeActive)
			$theReturnText = '<span style="color: green;" class="support_text"><i class="fa fa-check-square-o fa-lg"></i> You\'re good here. Ioncube appears to be installed and you can check off this step.</span>';
		else
			$theReturnText = '<span style="color: red;" class="support_text"><i class="fa fa-exclamation-triangle fa-lg"></i> You need to install Ioncube</span>';
	}
	return $theReturnText;
}
function getStepLine($inTitle, $inStepNumber, $inPrefix, $inCheckType, $isLast)
{
	if($isLast)
		$isLast = " last";
	else
		$isLast = "";
?>
        <h4 class="<?php echo $inPrefix ?>_step_<?php echo $inStepNumber; ?>"><label><input type="checkbox" class="step_check" name="<?php echo $inPrefix ?>_step_<?php echo $inStepNumber; ?>" />
        Step <?php echo $inStepNumber; ?> - <?php echo $inTitle ?></label></h4>
<div class="<?php echo $inPrefix ?>_step_<?php echo $inStepNumber; ?>_directions<?php echo $isLast; ?>">
        <p><?php echo getCheckTypeSupportText($inCheckType); ?></p>
<?php } ?>
<style type="text/css">

</style>
<div class="wrap">
    <div class="products_header" style="background: url(<?php echo plugins_url('youbrandinc_products/i/you-brand-guys-32.png');?>); background-repeat: no-repeat; background-position: 0px 7px;">
		 <h3><?php echo _e('Installing Ioncube'); ?></h3>
         <div style="clear: both; overflow:auto; margin: 0 auto;"></div>
    </div>
	<div style="clear: both;">
		<p>IonCube is supported by&nbsp;almost&nbsp; 99% of hosting companies and many have it activated by default.</p>
		<p>This page covers how to set up IonCube on some of the most popular hosts.</p>
		<p class="support_request_w"><a href="<?php echo YBI_SUPPORT_URL; ?>" target="_blank" class="green_button green_button_support"><i class="fa fa-users fa-lg"></i>&nbsp;&nbsp;Create Support Request</a></p>
<div id="ybi-accordian">
    <h3>Quick IonCube Overview</h3>
    <div class="ybi_accordian_row">
        <div style="float: right; width: 520px; padding-left: 20px;">
	        <script type="text/javascript" src="http://youbrandinc.evsuite.com/player/aW5zdGFsbGluZy1pb25jdWJlLXF1aWNrc3RhcnQubXA0/?container=evp-LOWFDB5WFD"></script><div id="evp-LOWFDB5WFD" data-role="evp-video" data-evp-id="aW5zdGFsbGluZy1pb25jdWJlLXF1aWNrc3RhcnQubXA0"></div>
        </div>
        <strong>Activating ioncube is pretty simple and 99% of all hosting companies support it and many have it installed but not activated by default.</strong>
        <p>Below is are quick set-up guides that will walk your through checking your current server settings and ensuring ioncube and PHP 5.3 + are installed and running. </p>
        <p>If you have any questions or would like some assistance anywhere along the way, please don’t hesitate to send us the support request by the support button above. </p>



       
<?php 
	$inipath = php_ini_loaded_file();
	$php_ini_text_backup = ybi_createFileTextDownloadBackup($inipath, "php_ini_backup");
	//$_SERVER["DOCUMENT_ROOT"]."/.htaccess
	$htaccess_file = $_SERVER["DOCUMENT_ROOT"]."/.htaccess";
	$htaccess_text_backup = ybi_createFileTextDownloadBackup($htaccess_file, "htaccess_backup");
 ?>
 <h3>Requirements</h3>
<p>At least PHP5.3 and IonCube</p>
<h4>Your Current Server Information</h4>
        <p><span style="font-size: 18px; font-weight:bold;"><?php if(ybi_checkPHPVersionGood()) : ?><span style="color: green;"><?php else : ?><span style="color: red;"><?php endif; ?>Your PHP Version: <?php echo phpversion(); ?></span>
        <?php if(!ybi_checkPHPVersionGood()) : ?>PHP5.3+ required<?php endif; ?></span></p>
          <p><?php if(extension_loaded("IonCube Loader")):?> <span style="color: green; font-size: 18px;"><i class="fa fa-thumbs-o-up fa-lg"></i> IonCube is Active</span> 
		  <?php else: ?><span style="color: red; font-size: 18px;"><i class="fa fa-exclamation-triangle fa-lg"></i> Ioncube Not Active</span><?php endif; ?></p>
        <p><strong>Your loaded php.ini file:</strong><code><?php echo $inipath; ?></code></p>
        <p><strong>Your document root is:</strong><code><?php echo realpath($_SERVER["DOCUMENT_ROOT"]); ?></code></p>
    </div>
    
    <h3>HostGator Installation</h3>
    <div class="ybi_accordian_row">
        <h4>Overview</h4>
        <p>We have created a 2 step install process for Hostgator that should get you up and running in a matter of seconds. </p>
		<p>99% of the time our quick set-up will work without issues but nothing is perfect.  Before making these changes, it is recommended that you have FTP/cpanel access to your server and always make file back-ups.</p>
		<p>If something goes wrong while making the changes, you will want to upload your original PHP.ini and .htaccess files to your server in order restore your original settings.</p>
        
        <ul class="requirements_list">
        	<li><strong>Files You Will Change</strong>: php.ini, .htaccess files</li>
        </ul>
        
        
        <p class="hint_text"><i class="fa fa-info-circle fa-lg hint"></i> Check off each item as you complete it or if it's says you're good to go.</p>
		<?php
			$currentPrefix = "hostgator";
			getStepLine("Download Backup Files",1,$currentPrefix, "server", false);?>
		<p>Click below to grab backups. <strong>To be extra safe you should FTP into your server and download backups</strong></p>
        <ol>
            <li><a href="<?php echo plugins_url() ?>/youbrandinc_products/server-setup/backups/<?php echo $php_ini_text_backup; ?>">Download your PHP.ini Backup</a> (right click and select save as)</li>
            <li><a href="<?php echo plugins_url() ?>/youbrandinc_products/server-setup/backups/<?php echo $htaccess_text_backup; ?>">Download your .htaccess Backup</a> (right click and select save as)</li>
        </ol>
</div>

        <?php 
			$currentPrefix = "hostgator";
			getStepLine("Click Button Below to Apply Updates to Your .htaccess and php.ini files",2,$currentPrefix, "server", true);?>
			<p>This will make changes to your .htaccess and your php.ini files. <strong>Before changes are made backups will be created on your server, for more information see About Automatic File Back-Ups below.</strong></p>


  		<p class="server_check"><a href="javascript:;" class="button_red hostgator_setup" 
        name="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/hostgator-ioncube-setup.php?doFileChanges=yes">Set Up Ioncube and PHP&nbsp;&nbsp;<i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
		<iframe style="clear: both;" class="hostgator_setup_iframe" id="server_info_iframe" src="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/hostgator-ioncube-setup.php?doFileChanges=no"></iframe>

        </div>    
        <p class="last_action" id="<?php echo $currentPrefix; ?>_step_2_last_action"><a href="admin.php?page=youbrandinc-support-news&success=yes" class="green_button"><i class="fa fa-smile-o"></i> Yeah! Now Click Here to Finish</a></p>
        
        <div class="trouble_shooting">
        <hr />
          <h1>Troubleshooting HostGator and IonCube</h1>
          <h4>About Automatic File Back-Ups</h4>
            <p>Just in case, we always back-up your php.ini and .htaccess files before making any changes. This allows you to easily revert back to the original files if something goes wrong.</p>
			<p>We already created backups and they are listed above for you to download as .txt files. Once changes are made you'll find other backups in the same directory your 2 files (php.ini and .htaccess).</p>
            <p>These files will be named .htaccess_ybi_backup_TIMESTAMP and php.ini_ybi_backup_TIMESTAMP (the timestamp will be the current time the backup was made).</p>
			<p>If you come accross an error the easiest way to get things back in order to delete your current .htaccess and php.ini file and replace them with the backups. Don't forget to rename the backups to the same file names.</p>
			<p>While rare, it is possible for errors to occur or even your site to go down by editing the PHP.ini and .htaccess files. </p>
			<p>Please know that if this happens you can contact our support team and we will fix the issue and finish the installation for you.</p>
            <p>To ensure the quickest possible support include WordPress admin & server access information with your support request.</p>  

          <h4>Multiple File Issue</h4>
          <p>When editing both the .htaccess file and the php.ini file, you may have more than one file on your server so you want to edit the file that is farthest down the directory file path </p>
			<strong>Example</strong>
		  <p>The root directory for the particular URL you are installing is <strong>public_html/curation_traffic_rocks</strong>
		  <p>If you see a php.ini or .htaccess file in both the <strong>curation_traffic_rocks</strong> and <strong>the public_html</strong> directory, then you want to edit the file in the <strong>root directory of the site you are installing Curation Traffic on</strong>, so in this case the root directory is <strong>curation_traffic_rocks</strong>.</p>
            <p>If you don't see a php.ini or .htaccess file in the curation_traffic_rocks root directory then edit the file in the public_html directory.</p>
            <p>Make sure you back-up you files incase something goes wrong, you can revert back to the original files!</p>
			<p>Typically, the php.ini file can be found in the public_html directory and the .htaccess file is usually in the root directory for the domain you are installing on.</p>
			<p>This will depend on your settings.</p>
            <p><i class="fa fa-lightbulb fa-lg hint"></i> The Fix: </p>
		
       	  <h4>The Don't Allow Changes Issue</h4>
   	      <p>Sometimes for security reasons there are things put into your .htaccess file that don't allow any files to be overwritten.</p>
       	  <p>If you see these lines in your <strong>.htaccess file</strong>:</p>
          <p><code>&lt;IfModule mod_suphp.c&gt;<br />
            suPHP_ConfigPath /home/YOURSITE<br />
            &lt;Files php.ini&gt;<br />
            order allow,deny<br />
            deny from all<br />
            &lt;/Files&gt;<br />
            &lt;/IfModule&gt;</code></p>
          <p>This line tells your server to not allow changes to your php.ini file, when you remove it the server will then allow the php.ini file to be overwritten.</p>
          <p><i class="fa fa-lightbulb fa-lg hint"></i> The Fix: <strong>Remove these temporarily and then re-copy your PHP.ini file</strong>. 
          Once your done and the plugin works recopy your old php.ini.</p>

  </div>
        
        
</div>
    <h3>GoDaddy Installation</h3>
    <div class="ybi_accordian_row">
    <h4>GoDaddy Overview</h4>
    <p>We have created a 4 step install process for Go Daddy that  should help you get up and running in no time.<strong>Please Note:</strong></p>
    <p>It is possible for errors to occur or even your site to go  down by editing the PHP.ini and .htaccess files. Before making these changes,  it is recommended that you have FTP/cpanel access to your server and always  make file back-ups.</p>
    <p>If something goes wrong while making the changes, you will  want to upload your original PHP.ini and .htaccess files to your server in  order restore your original settings.</p>
    <p>If you have questions or something happens, feel free to  contact our support team and we will be happy to help and even finish the  installation for you (please include WordPress &amp; server access information  with your support request). </p>
	
    <p class="hint_text"><i class="fa fa-info-circle fa-lg hint"></i> Check off each item as you complete it or if it's says you're good to go.</p>
<p><img style="margin-left: 40px;" src="<?php echo plugins_url('youbrandinc_products/i/check-off-step-1.png'); ?>" /></p>
        <?php 
			$currentPrefix = "godaddy";
			getStepLine("Visit Link and Follow Instructions to Update to PHP5.3",1,$currentPrefix, "php", false);?>
<p style="font-size:18px;">Visit this link for detailed instructions on <a href="http://support.godaddy.com/help/article/3937/viewing-or-changing-your-php-language-version?locale=en" target="_blank">How to Update to PHP5.3</a></p>
<p>Once done, click the green button below to check if it has been updated.</p>
		<p class="server_check"><a href="javascript:;" class="green_button reload_godaddy_php_check" 
        name="<?php echo $serverCheckURL; ?>?checkType=versioncheck">Check PHP Installation <i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
		<?php getServerCheckIframe('go_daddy_php','versioncheck'); ?> 
        </div>
        <?php 
			getStepLine("Click Below to Copy IonCube Files to Your Server",2,$currentPrefix, "ioncube", false);?>
          		<p class="server_check"><a href="javascript:;" class="green_button load_ioncube" name="<?php echo plugins_url() . '/youbrandinc_products/' ?>server-setup/copy-ioncube.php">Copy Ioncube to Your Server <i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
		<iframe style="clear: both;" class="copy_ioncube_iframe" id="server_info_iframe" src=""></iframe>
        </div>
        <?php 
			getStepLine("Edit Your php.ini File to Turn on Ioncube",3,$currentPrefix, "ioncube", false);?>
          <ol>
                <li>FTP into your server and find your <strong>php.ini</strong> file. You'll find your php.ini file here: <code><?php echo $inipath; ?></code></li>
                <li>Create a backup on your computer before in case you need it</li>
				<li>Copy and paste the text below in the bottom of your php.ini file.</li>
                <ul>
                	<li>
	                    <p><code>apc.enabled=0<br />[Zend]<br />zend_extension=<?php echo realpath($_SERVER["DOCUMENT_ROOT"]); ?>/ioncube_loader_lin_5.3.so</code></p>
                    </li>
                </ul>
                <li>Re-upload your php.ini file to your server overwriting the file.</li>
          </ol>
			<p>Note: <strong>your document root is:</strong><code><?php echo realpath($_SERVER["DOCUMENT_ROOT"]); ?></code></p>
        </div>
        <?php 
			getStepLine("Test Your Settings Below",4,$currentPrefix, "server", true);?>
        		<p class="server_check"><a href="javascript:;" class="green_button reload_final_godady_check" 
                	name="<?php echo $serverCheckURL; ?>?checkType=all">
        Final Check For Installation <i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
		<?php getServerCheckIframe("reload_final_godady_check","all"); ?> 
        <p>If Your PHP Version doesn't say "good" or Ioncube Intalled not YES recheck steps above or see trouble-shooting below.</p>
        </div>    
        
        <p class="last_action" id="<?php echo $currentPrefix ?>_step_4_last_action"><a href="admin.php?page=youbrandinc-support-news&success=yes" class="green_button"><i class="fa fa-smile-o"></i> Yeah! Now Click to Finish</a></p>
        
        <div class="trouble_shooting">
        <hr />
          <h1>Troubleshooting GoDaddy and IonCube</h1>
          <h4>Changes Aren't Being Picked up By Your Server?</h4>
			<p>Sometimes GoDaddy does not pick up changes when you FTP files or make updates to files via FTP. To ensure your GoDaddy server picks up these changes you just have to restart your web service.</p>
			<p>Note: this will briefly bring down your website (usually no longer than 30-60 seconds).</p>
			<p>You can restart your web service by going to the HOSTING control panel, find the icon that says:</p>
            <ol class="requirements_list">
                <li>Go to Hosting Control Panel</li>
                <li>Find Icon: System Processes</li>
                <li>Click on End Web</li>
                <li>Wait for that to complete and your changes should be updated.</li>
            </ol>

          
         </div><!--trouble_shooting-->        


        </div>
    <h3>Bluehost & HostMonster Installation</h3>
    <div class="ybi_accordian_row">
    <h4>Step 1 - Login Your Bluehost cPanel</h4>
        <p>You can be up and running with Bluehost with 4 clicks.</p>
        <h4>Step 2 - Go to PHP Config</h4>
        <img src="<?php echo plugins_url('youbrandinc_products/i/bluehost-step1.jpg'); ?>" />
        <h4>Step 3 - Select Settings As Shown Below</h4>
        <img src="<?php echo plugins_url('youbrandinc_products/i/bluehost-step2.jpg'); ?>" />
        <h4>Step 4 - After You've Saved Changes Click Below</h4>
        <p><a href="admin.php?page=youbrandinc-support-news&success=yes" class="green_button"><i class="fa fa-smile-o"></i> Yeah! Now Click to Finish</a></p>
    </div>
    <h3>IonCube and PHP5.3+ on Other Hosts</h3>
    <div class="ybi_accordian_row">
	    <h4>Overview of the Process</h4>
		<p>Getting IonCube and PHP5.3+ running on your host should be fairly straightforward.</p>
        <p>Here's what you typically need to do:</p>
        <ul class="links_list">
	        <li>For most hosts you can do all this in your cPanel. See Bluehost tab above for example of what your host most likely will look like and follow the steps below.</li>
            <li>Updating to PHP5.3+ - You will typically do this in your cPanel or by changing a setting in your php.ini or .htaccess file on your server. You'll have to most likely FTP to your server to get to those files.</li>
            <li>Ioncube - You'll have to either turn on IonCube (usually by editing your php.ini file). You also might have to download IonCube, FTP the IonCube files to your server then edit your php.ini file.</li>
        </ul>
        <p>Start with the first step by doing a little research.</p>
        <p class="hint_text"><i class="fa fa-info-circle fa-lg hint"></i> Check off each item as you complete it or if it's says you're good to go.</p>
		<p><img style="margin-left: 40px;" src="<?php echo plugins_url('youbrandinc_products/i/check-off-step-1.png'); ?>" /></p>
        <?php 
			$currentPrefix = "otherhosts";
			getStepLine("Perform Research on How to Activate PHP5.3+ and Ioncube on Your Host",1,$currentPrefix, "", false);?>
            <p>We suggest you search 2 places to find step by step instructions for your host if it is not listed above:</p>
            <ol class="requirements_list">
				<li>Start with a support request, mosts hosts are famaliar with Ioncube and either will  turn it on for you or will have documentation on how you can do it yourself.</li>
            	<li>Search your hosts support forum or support system for the words "ioncube" or "installing ioncube". You also want to check on how to upgrade to php5.3 or PHP5.4, whichever your host supports.</li>
                <li>Also use Google. Search for: "Intalling Ioncube on YourHostName".</li>
            </ol>
            <p>We've also curated the best installation instructions we've found for some popular hosts:</p>
            <ul class="links_list">
            	<li><a href="http://www.ioncube.com/loaders.php" target="_blank">Download the Loaders for IonCube</a> (if you need them)</li>
            	<li><a href="http://ahappycustomer.dreamhosters.com/dreamhost-ioncube.html" target="_blank">DreamHost - Install ionCube on DreamHost Server</a></li>
            	<li><a href="http://help.1and1.com/hosting-c37630/linux-c85098/php-c37728/manually-install-ioncube-loader-a726806.html" target="_blank">1and1.com - Install ionCube Loader on 1and1.com Hosting</a></li>
            	<li><a href="https://support.lunarpages.com/knowledge_bases/article/317" target="_blank">LunarPages - Ioncube on LunarPages</a></li>
            	<li><a href="https://my.justhost.com/cgi/help/149" target="_blank">JustHost.com Ioncube Installation</a></li>
            </ul>

        <?php 
			getStepLine("Update to PHP5.3+ & Turn on IonCube. Then Check Settings Below",2,$currentPrefix, "all", true);?>
            <p>Follow instructions on setting up php5.3+ and IonCube. If you keep this page open you can click the button below to ensure everything is set correctly.</p>
		<p class="server_check"><a href="javascript:;" class="green_button reload_other_php_check" 
        name="<?php echo $serverCheckURL; ?>?checkType=all">Check PHP Installation <i class="fa fa-caret-square-o-down fa-lg"></i></a></p>
		<?php getServerCheckIframe('reload_other_php_check','all'); ?> 
        </div>

        <p class="last_action" id="<?php echo $currentPrefix ?>_step_2_last_action"><a href="admin.php?page=youbrandinc-support-news&success=yes" class="green_button"><i class="fa fa-smile-o"></i> Yeah! Now Click to Finish</a></p>
        </div>
</div>



	</div>
<script id="IntercomSettingsScriptTag">
  window.intercomSettings = {
    // TODO: The current logged in user's full name
    name: "<?php 
			global $current_user;
    	get_currentuserinfo();
	echo $current_user->user_firstname . ' ' . $current_user->user_lastname; ?>",
    // TODO: The current logged in user's email address.

    email: "<?php echo bloginfo('admin_email'); ?>",
	'site_url' : "<?php echo bloginfo('url'); ?>",
    // TODO: The current logged in user's sign-up date as a Unix timestamp.
    created_at: <?php echo time(); ?>,
	PHP_uname: "<?php echo php_uname(); ?>",
	PHP_OS: "<?php echo PHP_OS; ?>",
	PAGE: "Install Ioncube",
	
    app_id: "zmxie9mk"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://static.intercomcdn.com/intercom.v1.js';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
</div><!--wrap-->
<?php 
//include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//require_once(__DIR__.'/../../../../admin.php');
//D:/wamp/www/plugin_dev/wp-content/plugins/youbrandinc_products/server-setup/check-server.php
//require_once dirname(__FILE__) . '/admin.php';
//require_once dirname(__FILE__) . '/admin.php';
/*$fileLocation = $_SERVER['SCRIPT_FILENAME'];
$fileLocation = str_replace("/wp-content/plugins/youbrandinc_products/server-setup/check-server.php","",$fileLocation);
//echo $fileLocation;
require_once $fileLocation .'/wp-admin/admin.php';
	_wp_admin_html_begin();
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');*/
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>sticky option</title>
</head>

<body>
<?php 
$checkIoncube = false;
$checkPHP = false;
$checkType = $_GET["checkType"];
if($checkType == 'all'):
	$checkIoncube = true;
	$checkPHP = true;
endif;

if($checkType == 'ioncube')
	$checkIoncube = true;

if($checkType == 'versioncheck')
	$checkPHP = true;


$date = date('H:i:s');
$versionText = '';
$ionCubeText = '';
$showFinish = false;
if($checkPHP)
{
	if (version_compare(phpversion(), '5.3', '<')) {
		$versionText = '<span style="color: red;">' . phpversion() . ' need at least 5.3+</span>';
	}
	else
	{
		$versionText = '<span style="color: green;">' . phpversion() . ' - Good</span>';
		$showFinish = true;
	}
} // checkPHP
if($checkIoncube)
{
	if(extension_loaded("IonCube Loader"))
	{
		$ionCubeText = '<span style="color: green;">Yes</span>';
		// if it's true then php5.3 is active, if it's false then it's not
		if($showFinish)
			$showFinish = true;
	}
	else
	{
		$ionCubeText = '<span style="color: red;">Ioncube not installed</span>';
		$showFinish = false;
	}
}
 ?>
	<p class="last_check"><strong>Last Check:</strong> <?php echo $date; ?></p>
    <?php if($checkPHP): ?><p><strong>PHP Version:</strong> <?php echo $versionText; ?></p><?php endif; ?>
    <?php if($checkIoncube): ?><p><strong>Ioncube Installed:</strong> <?php echo $ionCubeText; ?></p><?php endif; ?>
    <?php if($showFinish): ?><p style="color: #090; font-weight:bold;">You're good! Check off this step above!</p><?php endif; ?>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>sticky option</title>
<style type="text/css">
.green_button {
	-moz-box-shadow:inset 0px 1px 0px 0px #caefab;
	-webkit-box-shadow:inset 0px 1px 0px 0px #caefab;
	box-shadow:inset 0px 1px 0px 0px #caefab;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #77d42a), color-stop(1, #5cb811) );
	background:-moz-linear-gradient( center top, #77d42a 5%, #5cb811 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#77d42a', endColorstr='#5cb811');
	background-color:#77d42a;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #268a16;
	display:inline-block;
	color: #333;
	font-family:arial;
	font-size:11px;
	font-weight:bold;
	padding:10px 20px;
	text-decoration:none;
	margin-left: 10px;
}.green_button:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #5cb811), color-stop(1, #77d42a) );
	background:-moz-linear-gradient( center top, #5cb811 5%, #77d42a 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#5cb811', endColorstr='#77d42a');
	background-color:#5cb811;
	color:#306108;
}.green_button:active {
	position:relative;
	top:1px;
	color:#306108;
}

</style>
</head>

<body>
<?php 

	function createYBIBackup($file, $fileContents)
	{
		$ts = date('m-d-Y_H-i-s');
		$newFilename = $file.'_ybi_backup_'.$ts;
		file_put_contents($newFilename, $fileContents);
		return $newFilename;
	}

	function append($file, $data)
	{
	  // Append if the fila already exists...
	  if (file_exists($file))
	  {
		file_put_contents($file,  $data, FILE_APPEND);
		// Note: use LOCK_EX if an exclusive lock is needed.
		// file_put_contents($file,  $data, FILE_APPEND | LOCK_EX);
	  }
	  // Otherwise write a new file...
	  else
	  {
		file_put_contents($file, $data);
	  }
	}
	/**
	* prepend a file with data, checks to see if data exiss, if so it doesn't update. This function also backups the file.
	* 
	* @param string $file 
	* @param string $data
	* @param $checkfor - search the existing file content to see if changes are already exist
	* @param @fileShortName - the prefix for the file backup
	* @return string|boolean string on success; boolean on failure
	*/
	function prepend($file, $data, $checkfor, $fileShortName)
	{
		$theReturn = '';
	  // Append if the fila already exists...
	  if (file_exists($file))
	  {
	
			$fileContents = file_get_contents($file);
			if (strpos($fileContents,$checkfor) === false) {
				$backupFilename = createYBIBackup($file, $fileContents);
				file_put_contents($file, $data . $fileContents);
				$theReturn = 'Changes applied to '.$fileShortName . '<br>Back filename: '.$backupFilename;
			}
			else
				$theReturn = 'No Changes to '.$fileShortName.' it appears to be good.';
	  }
	  // Otherwise write a new file...
	  else
	  {
			file_put_contents($file, $data);
			$theReturn = 'a '.$fileShortName.' file was created.';
	  }
	  return $theReturn;
	}
	
		$doFileChanges = $_GET["doFileChanges"];
		if($doFileChanges == "yes")
		{
			$addToHtAccess = '# Use PHP 5.3'. PHP_EOL;
			$addToHtAccess .= '#AddType application/x-httpd-php53 .php'. PHP_EOL;
			
			$file = $_SERVER["DOCUMENT_ROOT"]."/.htaccess";
			$htAccessMessage = prepend($file, $addToHtAccess, 'application/x-httpd-php53','.htaccess');
		
			
			$php_ini_content = '';
			$php_ini_content .= 'zend_extension="/usr/local/IonCube/ioncube_loader_lin_5.3.so"' . PHP_EOL;
			$php_ini_content .= 'zend_extension_ts="/usr/local/IonCube/ioncube_loader_lin_5.3_ts.so"' . PHP_EOL;
			$file = php_ini_loaded_file();
			$theIniMessage = prepend($file,$php_ini_content,'ioncube_loader_lin_5.3.so','php.ini');
		}


		$date = date('H:i:s');
		$versionText = '';
		$showFinish = false;
		if (version_compare(phpversion(), '5.3', '<=')) {
			$versionText = '<span style="color: red;">' . phpversion() . ' need at least 5.3+</span>';
			$showFinish = false;

		}
		else
		{
			$versionText = '<span style="color: green;">' . phpversion() . ' - Good</span>';
			$showFinish = true;
		}
		if(extension_loaded("IonCube Loader"))
		{
			// only show finish if it's true because that means that php is good
			if($showFinish)
				$showFinish = true;
		}
		   
?>
<?php if($doFileChanges == "yes"): ?>
	<p><strong>Results of Changes:</strong></p>
    <ul>
	    <li><?php echo $htAccessMessage; ?></li>
	    <li><?php echo $theIniMessage; ?></li>    
    </ul>


	<script>
//        window.location.reload();
    </script>
    <p><a href="?doFileChanges=no" class="green_button">Click Here to Re-Check Settings</a></p>
    <?php  
	else :
	?>
	<p class="last_check"><strong>Check:</strong> <?php echo $date; ?></p>
    <p><strong>PHP Version:</strong> <?php echo $versionText; ?></p>
    <p><strong>Ioncube Installed:</strong> <?php if(extension_loaded("IonCube Loader")): echo '<span style="color: green;">Yes</span>'; else: echo '<span style="color: red;">No</span>'; endif; ?></p>
    <?php if($showFinish): ?><p style="color: #090; font-weight:bold;">You're good! Check off this step!</p><?php endif; ?>
    <?php endif; ?>
</body>

</html>
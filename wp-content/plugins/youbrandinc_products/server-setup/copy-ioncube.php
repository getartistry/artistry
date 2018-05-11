<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>sticky option</title>
</head>

<body>
<?php 
	$server_root = $_SERVER['DOCUMENT_ROOT'];
	$dirname = $server_root . "/ioncube";
	if (!is_dir($dirname)) {
		$directMade = mkdir($_SERVER['DOCUMENT_ROOT'].'ioncube',0755);
	}

	$remoteLocation = 'http://media.youbrandinc.com.s3.amazonaws.com/ioncube/';
	$fileNameArr = array(
		'ioncube_loader_lin_5.3.so',
		'ioncube_loader_lin_5.3_ts.so',
		'ioncube_loader_lin_5.4.so',
		'ioncube_loader_lin_5.4_ts.so'
		);


	foreach($fileNameArr as $fileName)
	{
		$file = $remoteLocation . $fileName;
		$newfile = $server_root . '/' . $fileName;
		$showSuccess = false;
		if ( copy($file, $newfile) ) {
			echo $fileName . " Copy success!";
			$showSuccess = true;
		}else{
			echo $fileName . " Copy failed.";
			$showSuccess = false;
		}
		
	}
?>
<?php if($showSuccess): ?><p><span style="color: green;">This step good, check off.</span></p><?php endif; ?>
</body>
<script>
//document.getElementsByClassName("last_check").fadeIn(1000).fadeOut(1000).fadeIn(1000).fadeOut(1000);
</script>
</html>
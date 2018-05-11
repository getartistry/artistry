<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>sticky option</title>
</head>

<body>
<?php 
$date = date('H:i:s');
$versionText = '';
$showFinish = false;
if (version_compare(phpversion(), '5.3', '<')) {
	$versionText = '<span style="color: red;">' . phpversion() . ' need at least 5.3+</span>';
}
else
{
	$versionText = '<span style="color: green;">' . phpversion() . ' - Good</span>';
    $showFinish = true;
}
  

 ?>
	<p class="last_check"><strong>Last Check:</strong> <?php echo $date; ?></p>
    <p><strong>PHP Version:</strong> <?php echo $versionText; ?></p>
    <?php if($showFinish): ?><p style="color: #090; font-weight:bold;">You're good! Check off this step!</p><?php endif; ?>
</body>
<script>
//document.getElementsByClassName("last_check").fadeIn(1000).fadeOut(1000).fadeIn(1000).fadeOut(1000);
</script>
</html>
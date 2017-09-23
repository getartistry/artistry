<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db078_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/reducing-divi-pagebuilder-module-spacing/');
	$plugin->checkbox(__FILE__); 
	echo "Row spacing:";
	?>
<table style="margin-left:50px">
<tr><td style="width:50px">top:</td><td><?php $plugin->numberpicker(__FILE__, 'top', 30); ?>px</td></tr>
</table>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db078_add_setting');
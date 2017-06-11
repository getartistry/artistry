<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db052_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Logo height:
<table style="margin-left:50px">
<tr><td>Normal:</td><td><?php $plugin->numberpicker(__FILE__, 'normal', 43, 0); ?>px</td></tr>
<tr><td>Shrunk:</td><td><?php $plugin->numberpicker(__FILE__, 'shrunk', 30, 0); ?>px</td></tr>
</table>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db052_add_setting');
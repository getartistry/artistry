<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db066_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-the-height-of-the-divi-header/'); ?>
<?php $plugin->checkbox(__FILE__); ?> Header minimum height:
<table style="margin-left:50px">
<tr><td>Normal:</td><td><?php $plugin->numberpicker(__FILE__, 'normal', 43, 0); ?>px</td></tr>
<tr><td>Shrunk:</td><td><?php $plugin->numberpicker(__FILE__, 'shrunk', 30, 0); ?>px</td></tr>
</table>
<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db066_add_setting');
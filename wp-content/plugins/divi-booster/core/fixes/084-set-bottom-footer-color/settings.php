<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db084_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); 
	echo "Bottom footer bar color:"; 
	$plugin->colorpicker(__FILE__, 'bgcol', '#000000'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db084_add_setting');

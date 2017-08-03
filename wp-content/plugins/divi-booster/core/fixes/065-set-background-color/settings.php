<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db065_add_setting($plugin) { 
	$plugin->setting_start();
	$plugin->techlink('https://divibooster.com/changing-the-background-color-in-divi/'); 
	$plugin->checkbox(__FILE__); 
	echo "Background color:";
	$plugin->colorpicker(__FILE__, 'bgcol'); 	
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi23', 'db065_add_setting');


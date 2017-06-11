<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db069_add_setting($plugin) { 
	$plugin->setting_start();
	$plugin->checkbox(__FILE__); 
	echo "Hide the search icon";	
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi23', 'db069_add_setting');
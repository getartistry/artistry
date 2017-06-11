<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db085_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-the-divi-secondary-menu-text-bold/'); 
	$plugin->checkbox(__FILE__); 
	echo "Make the secondary header text bold";
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db085_add_setting');
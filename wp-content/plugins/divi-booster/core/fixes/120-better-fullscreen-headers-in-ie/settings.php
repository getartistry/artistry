<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db120_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-divi-fullscreen-headers-work-in-ie/'); 
	$plugin->checkbox(__FILE__); ?> Fix fullscreen mode display issues in IE<?php 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-headerfullwidth', 'db120_add_setting');

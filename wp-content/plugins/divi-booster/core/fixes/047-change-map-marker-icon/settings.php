<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db047_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-pin-icon-in-the-divi-module/'); 
	$plugin->checkbox(__FILE__); ?> Change map pin icon (46 x 43px) <?php $plugin->imagePicker(__FILE__, 'url'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-map', 'db047_add_setting');
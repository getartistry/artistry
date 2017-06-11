<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db074_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-the-divi-header-menu-link-hover-color/'); 
	$plugin->checkbox(__FILE__); ?> Menu link hover color: <?php 
	$plugin->colorpicker(__FILE__, 'col', 'rgba(0,0,0,0.42)', true); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db074_add_setting');



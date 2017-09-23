<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db121_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-divi-search-icon-hover-color/'); 
	$plugin->checkbox(__FILE__); ?> Search icon hover color: <?php $plugin->colorpicker(__FILE__, 'hovercol'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db121_add_setting');


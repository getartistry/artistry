<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db113_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-the-divi-logo-link/'); 
	$plugin->checkbox(__FILE__); ?> Change logo link URL to: <?php $plugin->textpicker(__FILE__, 'logourl'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db113_add_setting');


<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db003_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/replace-divi-header-links-with-widget-area/'); 
	$plugin->checkbox(__FILE__); ?> Add new widget area below the navigation links<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db003_add_setting');
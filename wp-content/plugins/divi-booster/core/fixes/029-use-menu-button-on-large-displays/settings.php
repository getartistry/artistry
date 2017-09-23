<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db029_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/show-divi-header-menu-button-on-large-screens/');  
	$plugin->checkbox(__FILE__); ?> Use mobile header menu button on desktops too<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db029_add_setting');
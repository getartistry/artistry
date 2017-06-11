<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db093_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/show-divi-header-social-icons-on-mobiles-divi-2-4'); 
	$plugin->checkbox(__FILE__); ?> Show secondary nav bar social icons on mobiles<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-top', 'db093_add_setting');

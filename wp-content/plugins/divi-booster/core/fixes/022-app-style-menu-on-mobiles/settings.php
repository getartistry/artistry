<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db022_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/use-an-app-style-header-layout-for-divi-on-mobiles/'); 
	$plugin->checkbox(__FILE__); ?> Use an "app-style" header layout on mobiles<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-mobile', 'db022_add_setting');
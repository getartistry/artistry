<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db021_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/fixing-the-divi-header-menu-magnifying-glass-issue/'); 
	$plugin->checkbox(__FILE__); ?> Fix header menu width / magnifying glass issue<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db021_add_setting');
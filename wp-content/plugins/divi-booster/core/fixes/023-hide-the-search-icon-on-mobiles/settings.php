<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db023_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-the-divi-search-icon-on-mobile-devices/'); 
	$plugin->checkbox(__FILE__); ?> Hide the search icon on mobiles<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-mobile', 'db023_add_setting');
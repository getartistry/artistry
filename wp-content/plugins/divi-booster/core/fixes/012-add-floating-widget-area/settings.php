<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db012_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/add-a-floating-sidebar-area-in-divi-theme/'); 
	$plugin->checkbox(__FILE__); ?> Add "sticky" widget area to left of screen<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-layout', 'db012_add_setting');
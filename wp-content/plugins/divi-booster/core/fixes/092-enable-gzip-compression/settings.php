<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db092_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/enable-compression-for-the-divi-theme/'); 
	$plugin->checkbox(__FILE__); ?> Enable compression to reduce download times<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-speed', 'db092_add_setting');
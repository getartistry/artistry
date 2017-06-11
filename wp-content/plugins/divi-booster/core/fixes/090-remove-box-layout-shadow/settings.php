<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db090_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/remove-divi-theme-box-layout-shadow-lines/'); 
	$plugin->checkbox(__FILE__); ?> Remove box layout shadow<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-layout', 'db090_add_setting');
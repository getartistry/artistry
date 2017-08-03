<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db049_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/right-align-the-divi-top-header-icons/'); 
	$plugin->checkbox(__FILE__); ?> Put all icons on the right<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-top', 'db049_add_setting');
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db098_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/split-divi-secondary-header-icons-to-left-and-right/'); 
	$plugin->checkbox(__FILE__); ?> Put social icons on the right<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-top', 'db098_add_setting');
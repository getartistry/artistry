<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db008_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/vertically-center-the-divi-header-links/'); 
	$plugin->checkbox(__FILE__); ?> Vertically center the header links<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db008_add_setting');
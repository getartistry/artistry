<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db058_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/stop-the-divi-header-from-shrinking-on-scroll/'); 
	$plugin->checkbox(__FILE__); ?> Don't shrink header on scroll<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db058_add_setting');
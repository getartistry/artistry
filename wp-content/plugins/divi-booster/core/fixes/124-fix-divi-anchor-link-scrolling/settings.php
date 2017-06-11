<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db124_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/fix-divi-anchor-links-not-working-correctly/'); 
	$plugin->checkbox(__FILE__); ?> Fix Divi anchor link scrolling<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-links', 'db124_add_setting');
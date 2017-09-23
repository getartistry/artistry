<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db083_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Open social media icon links in a new tab<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('general-icons', 'db083_add_setting');
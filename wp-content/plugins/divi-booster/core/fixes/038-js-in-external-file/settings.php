<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db038_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Use inline JavaScript<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-js', 'db038_add_setting');
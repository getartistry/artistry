<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db001_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Hide the logo<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db001_add_setting');	
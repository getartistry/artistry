<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db036_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Disable CSS minification<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-css', 'db036_add_setting');
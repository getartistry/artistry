<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db035_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Use inline CSS<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-css', 'db035_add_setting');
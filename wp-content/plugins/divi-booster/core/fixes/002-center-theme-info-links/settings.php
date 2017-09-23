<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db002_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Center the footer links<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('footer', 'db002_add_setting');	
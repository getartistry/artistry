<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db039_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Disable JavaScript minification<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-js', 'db039_add_setting');
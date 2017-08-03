<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db045_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Strike-through unavailable features<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-pricing', 'db045_add_setting');
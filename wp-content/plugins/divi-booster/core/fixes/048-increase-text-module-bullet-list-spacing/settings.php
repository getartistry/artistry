<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db048_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Increase spacing around bullet lists<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-text', 'db048_add_setting');
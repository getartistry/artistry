<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db055_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Put slider images on the right<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db055_add_setting');
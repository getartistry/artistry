<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db013_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/using-the-divi-page-builder-on-posts/'); 
	$plugin->checkbox(__FILE__); ?> Enable Page Builder for posts (pre Divi 2.4)<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db013_add_setting');
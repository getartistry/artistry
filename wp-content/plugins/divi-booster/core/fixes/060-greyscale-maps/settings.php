<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db060_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/greyscale-google-maps-in-the-divi-map-module/'); 
	$plugin->checkbox(__FILE__); ?> Display maps in greyscale<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db060_add_setting');
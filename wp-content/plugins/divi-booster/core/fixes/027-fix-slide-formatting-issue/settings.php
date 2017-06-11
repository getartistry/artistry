<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db027_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/fix-slider-order-and-overlapping-text-issue/'); 
	$plugin->checkbox(__FILE__); ?> Fix slider overlapping text issue<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db027_add_setting');
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db117_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/disable-divi-gallery-image-slide-in-effect/'); 
	$plugin->checkbox(__FILE__); ?> Disable gallery image "slide in" effect<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-gallery', 'db117_add_setting');
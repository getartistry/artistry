<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db076_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/disable-divi-slider-image-slide-in-effect/'); 
	$plugin->checkbox(__FILE__); ?> Disable slider image "slide in" effect<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db076_add_setting');
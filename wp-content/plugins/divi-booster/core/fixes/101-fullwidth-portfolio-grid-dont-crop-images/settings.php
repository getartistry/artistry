<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db101_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/stop-fullwidth-portfolio-images-from-being-cropped/'); 
	$plugin->checkbox(__FILE__); ?> Stop project images from being stretched / cropped<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-portfoliofullwidth', 'db101_add_setting');
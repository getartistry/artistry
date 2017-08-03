<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db108_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-the-divi-slider-controls-square/'); 
	$plugin->checkbox(__FILE__); ?> Make slider controls square<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db108_add_setting');
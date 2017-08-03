<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db005_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-height-of-the-divi-slider/'); 
	$plugin->checkbox(__FILE__); ?> Set default slider height:<?php
	$plugin->numberpicker(__FILE__, 'sliderheight', 500); ?> pixels<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db005_add_setting');


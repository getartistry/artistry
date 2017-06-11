<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db112_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-the-divi-slider-controls-hollow/'); 
	$plugin->checkbox(__FILE__); ?> Make post slider controls hollow<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-postslider', 'db112_add_setting');
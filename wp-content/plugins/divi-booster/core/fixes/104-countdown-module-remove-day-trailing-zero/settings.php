<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db104_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/remove-the-leading-zero-on-countdown-module-days/'); 
	$plugin->checkbox(__FILE__); ?> Hide leading zero on days<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-countdown', 'db104_add_setting');
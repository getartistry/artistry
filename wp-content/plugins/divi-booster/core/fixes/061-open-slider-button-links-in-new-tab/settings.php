<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db061_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/open-divi-slider-button-link-in-new-tab/'); 
	$plugin->checkbox(__FILE__); ?> Open slider links in new tab<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-slider', 'db061_add_setting');
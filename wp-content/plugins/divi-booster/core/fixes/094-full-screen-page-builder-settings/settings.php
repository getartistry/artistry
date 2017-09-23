<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db094_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-module-settings-editor-full-screen/'); 
	$plugin->checkbox(__FILE__); ?> Make Divi Builder settings pop-ups full screen<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-divi', 'db094_add_setting');

<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db139_add_setting($plugin) {  
	$plugin->setting_start(); 
	//$plugin->techlink('https://divibooster.com/make-divi-module-settings-editor-full-screen/'); 
	$plugin->checkbox(__FILE__); ?> Show mobile icon on hover in module settings<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-divi', 'db139_add_setting');

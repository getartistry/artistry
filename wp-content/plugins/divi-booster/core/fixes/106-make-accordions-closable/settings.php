<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db106_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-accordion-module-closable/'); 
	$plugin->checkbox(__FILE__); ?> Make accordions closable<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-accordion', 'db106_add_setting');
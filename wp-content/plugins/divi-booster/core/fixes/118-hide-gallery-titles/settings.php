<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db118_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-divi-gallery-titles-but-not-the-captions/'); 
	$plugin->checkbox(__FILE__); ?> Hide gallery image titles (but not the captions)<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-gallery', 'db118_add_setting');	
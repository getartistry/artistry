<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db070_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-simple-lightbox-work-with-divi/'); 
	$plugin->checkbox(__FILE__); ?> Fix <a href="https://wordpress.org/plugins/simple-lightbox/" target="_blank">Simple LightBox</a> overlap with Divi top header<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('plugins-other', 'db070_add_setting');
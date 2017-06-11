<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db086_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/fixing-the-divi-sub-menu-hover-colors-on-single-page-layouts/'); 
	$plugin->checkbox(__FILE__); ?> Fix sub-menu link colors on single page navigation<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db086_add_setting');
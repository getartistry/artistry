<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db080_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-comment-button-mobile-responsive/'); 
	$plugin->checkbox(__FILE__); ?> Fix comment button responsiveness<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('posts', 'db080_add_setting');
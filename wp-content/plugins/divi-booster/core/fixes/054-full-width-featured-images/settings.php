<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db054_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-divi-featured-images-full-width/'); 
	$plugin->checkbox(__FILE__); ?> Make featured images as wide as the content area<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('posts', 'db054_add_setting');
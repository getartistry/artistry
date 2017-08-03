<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db097_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/stop-divi-featured-images-from-stretching/'); 
	$plugin->checkbox(__FILE__); ?> Don't stretch featured images<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('posts', 'db097_add_setting');
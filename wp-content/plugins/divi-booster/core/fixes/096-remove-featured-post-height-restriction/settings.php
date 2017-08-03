<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db096_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/stop-divi-from-cropping-feature-post-heights/'); 
	$plugin->checkbox(__FILE__); ?> Prevent featured post height cropping<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('posts', 'db096_add_setting');
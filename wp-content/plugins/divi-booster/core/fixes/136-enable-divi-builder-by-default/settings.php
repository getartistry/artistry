<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db136_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/enable-divi-builder-by-default-on-new-posts-pages/'); 
	$plugin->checkbox(__FILE__); ?> Enable Divi Builder by default on new pages / posts<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-divi', 'db136_add_setting');


<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db007_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/removing-the-divi-theme-dividing-line/'); 
	$plugin->checkbox(__FILE__); ?> Hide dividing line<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('sidebar', 'db007_add_setting');
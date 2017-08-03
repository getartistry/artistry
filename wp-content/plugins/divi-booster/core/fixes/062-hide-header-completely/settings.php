<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db062_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-the-divi-theme-header/'); 
	$plugin->checkbox(__FILE__); ?> Hide header completely<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db062_add_setting');
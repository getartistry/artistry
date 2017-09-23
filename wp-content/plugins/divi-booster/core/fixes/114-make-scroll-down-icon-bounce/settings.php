<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db114_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/make-full-width-header-scroll-down-icon-bounce/'); 
	$plugin->checkbox(__FILE__); ?> Make scroll down icon bounce<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-headerfullwidth', 'db114_add_setting');
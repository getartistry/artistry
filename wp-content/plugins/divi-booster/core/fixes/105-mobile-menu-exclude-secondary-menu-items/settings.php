<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db105_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-secondary-menu-items-in-mobile-menu/'); 
	$plugin->checkbox(__FILE__); ?> Hide secondary menu items in mobile menu<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-mobile', 'db105_add_setting');
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db071_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-divi-submenu-link-hover-color/'); 
	$plugin->checkbox(__FILE__); ?> Submenu item hover background color: <?php $plugin->colorpicker(__FILE__, 'bgcol'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db071_add_setting');


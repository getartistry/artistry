<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db025_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-divi-mobile-header-menu-button/'); 
	$plugin->checkbox(__FILE__); ?> Mobile menu icon color: <?php $plugin->colorpicker(__FILE__, 'bgcol'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db025_add_setting');


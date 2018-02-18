<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db142_add_setting($plugin) { 
	$plugin->setting_start();  
	$plugin->techlink('https://divibooster.com/changed-centered-menu-select-page-background-color/');
	$plugin->checkbox(__FILE__); ?> Change centered menu "Select Page" background color: <?php $plugin->colorpicker(__FILE__, 'bgcol', 'rgba(0,0,0,0.05)', true);
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-mobile', 'db142_add_setting');	
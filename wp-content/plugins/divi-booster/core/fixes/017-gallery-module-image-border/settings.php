<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db017_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/add-border-to-divi-image-gallery-images/'); 
	$plugin->checkbox(__FILE__); ?> Grid layout border color: <?php $plugin->colorpicker(__FILE__, 'bordercol'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-gallery', 'db017_add_setting');


<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db095_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Link hover color: <?php $plugin->colorpicker(__FILE__, 'hovercol'); 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-top', 'db095_add_setting');
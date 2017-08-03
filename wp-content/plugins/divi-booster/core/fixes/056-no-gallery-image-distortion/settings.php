<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db056_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Center the images in grid view thumbnails<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-gallery', 'db056_add_setting');
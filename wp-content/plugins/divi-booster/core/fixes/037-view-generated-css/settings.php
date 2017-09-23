<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db037_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->hiddencheckbox(__FILE__); ?> Generated CSS: <a href="<?php echo htmlentities($plugin->cacheurl); ?>wp_head.css" target="_blank">wp_head.css</a><?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-css', 'db037_add_setting');
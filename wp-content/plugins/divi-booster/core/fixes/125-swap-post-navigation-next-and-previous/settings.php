<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db125_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/swap-the-post-navigation-module-next-and-previous-links/'); 
	$plugin->checkbox(__FILE__); ?> Swap the next and previous links<?php 
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-postnav', 'db125_add_setting');


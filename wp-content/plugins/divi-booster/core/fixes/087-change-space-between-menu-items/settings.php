<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db087_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/change-space-between-divi-menu-items/'); 
	$plugin->checkbox(__FILE__); ?> Space between menu items: <?php $plugin->numberpicker(__FILE__, 'menuitempadding', 22); ?>px<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db087_add_setting');
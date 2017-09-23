<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db019_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/increasing-the-width-of-the-divi-sidebar/'); 
	$plugin->checkbox(__FILE__); ?> Sidebar width:<?php $plugin->numberpicker(__FILE__, 'sidebarwidth', 285, 0); ?>px<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db019_add_setting');
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db103_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-divi-mobile-menu-font-size/'); 
	$plugin->checkbox(__FILE__); ?> Mobile menu font size: <?php $plugin->numberpicker(__FILE__, 'menufontsize', 14); ?>px<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-mobile', 'db103_add_setting');
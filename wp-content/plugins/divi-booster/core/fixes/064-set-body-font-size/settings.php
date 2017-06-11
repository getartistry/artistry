<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db064_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/changing-the-default-divi-font-size/'); 
	$plugin->checkbox(__FILE__); ?> Set default font size: <?php $plugin->numberpicker(__FILE__, 'fontsize', 100, 0); ?>%<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db064_add_setting');
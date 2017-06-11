<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db024_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/making-the-divi-header-shrink-further-down-the-page/'); 
	$plugin->checkbox(__FILE__); ?> Don't shrink the header until user scrolls down by <?php $plugin->numberpicker(__FILE__, 'offset', 500, 0); ?>px<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db024_add_setting');
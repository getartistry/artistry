<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db020_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/preventing-the-divi-theme-horizontal-scroll-bar-bug/'); 
	$plugin->checkbox(__FILE__); ?> Fix horizontal scroll-bar bug<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db020_add_setting');
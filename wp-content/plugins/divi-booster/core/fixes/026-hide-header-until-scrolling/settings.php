<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db026_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/hide-divi-theme-header-until-scrolled/'); 
	$plugin->checkbox(__FILE__); ?> Hide fixed header until user scrolls down<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db026_add_setting');
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db133_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/display-site-title-and-tagline-text-in-header/'); 
	$plugin->checkbox(__FILE__); ?> Show site title and tagline in header<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db133_add_setting');	
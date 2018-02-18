<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db143_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/a-collapsible-sidebar-in-divi/'); 
	$plugin->checkbox(__FILE__); ?> Make the sidebar collapsible<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('sidebar', 'db143_add_setting');	
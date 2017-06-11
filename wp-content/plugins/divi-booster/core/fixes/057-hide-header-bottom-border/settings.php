<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db057_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Hide header bottom border<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('header-main', 'db057_add_setting');
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db129_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/divi-visual-editor-adding-a-hover-border-to-modules/'); 
	$plugin->checkbox(__FILE__); ?> Show hover border on modules<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-visual', 'db129_add_setting');	
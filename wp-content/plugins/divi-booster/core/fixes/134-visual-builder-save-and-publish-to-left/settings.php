<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db134_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Move publish buttons to left<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-visual', 'db134_add_setting');	
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db132_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->checkbox(__FILE__); ?> Hide visual builder<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-visual', 'db132_add_setting');	
<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db009_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/fixing-the-team-member-description-bug/'); 
	$plugin->checkbox(__FILE__); ?> Fix team member margin bug<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('deprecated-divi24', 'db009_add_setting');
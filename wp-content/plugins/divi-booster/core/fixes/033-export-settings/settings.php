<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db033_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->hiddencheckbox(__FILE__); ?> Export plugin settings to file: <a href="<?php esc_attr_e(plugin_dir_url(__FILE__).'export.php'); ?>">download settings file</a><?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-export', 'db033_add_setting');
	
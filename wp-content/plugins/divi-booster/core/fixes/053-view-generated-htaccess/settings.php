<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db053_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->hiddencheckbox(__FILE__); ?> Generated .htaccess rules: <a href="<?php echo htmlentities($plugin->cacheurl); ?>htaccess.txt" target="_blank">htaccess.txt</a><?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-htaccess', 'db053_add_setting');	
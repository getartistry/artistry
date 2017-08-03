<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db040_add_setting($plugin) { 
	$plugin->setting_start(); 
	$plugin->hiddencheckbox(__FILE__); ?> Generated JavaScript: <a href="<?php echo htmlentities($plugin->cacheurl); ?>wp_footer.js" target="_blank">wp_footer.js</a><?php
		$plugin->setting_end(); 
} 
$wtfdivi->add_setting('developer-js', 'db040_add_setting');
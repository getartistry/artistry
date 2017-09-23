<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db126_add_setting($plugin) {  
	$plugin->hiddencheckbox(__FILE__);
	$plugin->hiddenfield(__FILE__, 'icons'); 
} 
$wtfdivi->add_setting('general-icons', 'db126_add_setting');
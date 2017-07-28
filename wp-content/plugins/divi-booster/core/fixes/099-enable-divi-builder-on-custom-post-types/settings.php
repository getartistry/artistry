<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db099_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/enable-divi-builder-on-custom-post-types/'); 
	$plugin->checkbox(__FILE__); ?> Enable Divi Builder on Custom Post Types
	<div style="margin-left: 50px"><?php $plugin->checkbox(__FILE__, 'shared-library'); ?> Use main layout library</div>
	<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('pagebuilder-divi', 'db099_add_setting');
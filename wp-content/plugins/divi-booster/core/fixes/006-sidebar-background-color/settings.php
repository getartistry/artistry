<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db006_add_setting($plugin) {  
	$plugin->setting_start(); 
	$plugin->techlink('https://divibooster.com/setting-the-divi-theme-sidebar-background-color/'); ?>
<?php $plugin->checkbox(__FILE__); ?> Sidebar background color: <?php $plugin->colorpicker(__FILE__, 'bgcol', '#fff', true);
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('sidebar', 'db006_add_setting');


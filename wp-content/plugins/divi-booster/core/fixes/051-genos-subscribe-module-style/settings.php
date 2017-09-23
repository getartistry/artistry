<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db051_add_setting($plugin) { 
	$plugin->setting_start(); ?>
<a href="http://quiroz.co/customizing-the-subscribe-module-in-divi/" target="_blank" style="float:right;margin-top:6px;text-decoration:none">by Geno</a>
<?php $plugin->checkbox(__FILE__); ?> Use horizontal subscribe module style<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-subscribe', 'db051_add_setting');
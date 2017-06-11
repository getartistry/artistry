<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db043_add_setting($plugin) { 
	$plugin->setting_start(); 
	?><a href="http://quiroz.co/customizing-pricing-table-divi/" target="_blank" style="float:right;margin-top:6px;text-decoration:none">by Geno</a>
<?php $plugin->checkbox(__FILE__); ?> Use rounded pricing table corners<?php
	$plugin->setting_end(); 
} 
$wtfdivi->add_setting('modules-pricing', 'db043_add_setting');
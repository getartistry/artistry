<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

:not(.et_pb_fullwidth_section).et_pb_section { 
	padding-top: <?php echo intval(@$option['top']); ?>px !important; 
	padding-bottom: <?php echo intval(@$option['bottom']); ?>px !important; 
}

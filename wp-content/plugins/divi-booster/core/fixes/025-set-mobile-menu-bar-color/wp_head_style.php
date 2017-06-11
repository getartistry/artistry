<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

span.mobile_menu_bar:before {
	color: <?php echo htmlentities(@$option['bgcol']); ?> !important;
}  
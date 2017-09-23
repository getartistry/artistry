<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

ul#top-menu li.menu-item:not(:last-child) { 
	padding-right: <?php echo intval(@$option['menuitempadding']); ?>px !important; 
}
#et_top_search { 
	margin-left: <?php echo intval(@$option['menuitempadding']); ?>px !important; 
}

<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#top-menu-nav #top-menu a:hover,
#mobile_menu_slide a:hover { 
	color: <?php echo htmlentities(@$option['col']); ?> !important; 
	opacity:1 !important; 
}

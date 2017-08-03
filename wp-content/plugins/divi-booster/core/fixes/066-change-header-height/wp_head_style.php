<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

@media only screen and ( min-width:981px ) {
	#main-header { min-height: <?php echo htmlentities(@$option['normal']); ?>px !important; }
	#main-header.et-fixed-header { min-height: <?php echo htmlentities(@$option['shrunk']); ?>px !important;  }
}
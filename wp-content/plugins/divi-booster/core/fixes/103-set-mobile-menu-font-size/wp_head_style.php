<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

.et_mobile_menu li a { font-size: <?php echo intval(@$option['menufontsize']); ?>px !important; }

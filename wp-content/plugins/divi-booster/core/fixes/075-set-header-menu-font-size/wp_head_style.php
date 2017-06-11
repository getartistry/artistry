<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#top-menu a { font-size: <?php echo intval(@$option['menufontsize']); ?>px; }

<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#et_search_icon:hover:before { color: <?php echo htmlentities(@$option['hovercol']); ?> !important; }


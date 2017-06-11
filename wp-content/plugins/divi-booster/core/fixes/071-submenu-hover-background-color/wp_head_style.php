<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

#top-menu li li a:hover { background-color: <?php echo htmlentities(@$option['bgcol']); ?>; }


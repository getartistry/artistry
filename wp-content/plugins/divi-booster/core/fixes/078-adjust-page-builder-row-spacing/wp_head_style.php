<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

.et_pb_row { padding-top: <?php echo intval(@$option['top']); ?>px !important; }

<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

.et_pb_gallery_grid .et_pb_gallery_image 
{ 
    box-sizing:border-box;
    border:1px solid <?php echo htmlentities(@$option['bordercol']); ?>; 
}
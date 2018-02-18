<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>

.et_header_style_centered .mobile_nav,
.et_header_style_split .mobile_nav { 
    background-color: <?php esc_html_e(@$option['bgcol']); ?> !important; 
}


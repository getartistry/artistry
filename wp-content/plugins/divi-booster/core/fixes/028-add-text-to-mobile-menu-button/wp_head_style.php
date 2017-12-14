<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__); ?>


.et_header_style_left .mobile_menu_bar { display:inline-block !important; }

/* Mobile */
@media only screen and (max-width: 980px) {
	
	/* Default header */
	.et_header_style_left .mobile_nav::before,
	.et_header_style_slide #et-top-navigation:before, 
	.et_header_style_fullscreen #et-top-navigation:before { 
		content:'<?php esc_html_e(@$option['menubuttontext']); ?>'; 
		vertical-align:top; 
		line-height:2.2em; 
	}
}

/* Desktop */
@media only screen and (min-width: 981px) {
	
	/* Fixed headers */
	/*.et_header_style_left.et-fixed-header .mobile_nav::before { line-height:1.3em; }*/
	
	/* Slide-in and fullscreen header */
	.et_header_style_slide #et-top-navigation:before, 
	.et_header_style_fullscreen #et-top-navigation:before { 
		content: 'Menu'; 
		vertical-align: top; 
		line-height: 2.2em; 
	}
}
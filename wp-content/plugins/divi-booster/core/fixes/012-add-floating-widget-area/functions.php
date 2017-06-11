<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

// Create the new widget area
function wtfdivi012_widget_area() {
   register_sidebar(array(
   'name' => 'Sticky Sidebar (Left)',
   'id' => 'wtfdivi012-widget-area',
   'before_widget' => '<div id="%1$s" class="wtfdivi012_widget %2$s">',
   'after_widget' => '</div>',
   'before_title' => '<h4 class="widgettitle">',
   'after_title' => '</h4>',
   ));
}
add_action('widgets_init', 'wtfdivi012_widget_area');

function wtfdivi012_add_widget_area_to_footer() { ?>
<div id="wtfdivi012-widget-area-wrap"><?php dynamic_sidebar('wtfdivi012-widget-area'); ?></div>
<?php
}
add_action('wp_footer', 'wtfdivi012_add_widget_area_to_footer');
 
function db012_user_css($plugin) { ?>
@media only screen and ( min-width: 981px ) {
	#wtfdivi012-widget-area-wrap { 
		visibility:visible;	z-index:1000; display:block !important; float:left; position:fixed; 
		background-color:white; margin-top:2px;
	}
	.wtfdivi012_widget { padding:16px; }
}
@media only screen and ( max-width: 980px ) { 
	#wtfdivi012-widget-area-wrap { display:none; }
}
<?php
}
add_action('wp_head.css', 'db012_user_css');

function db012_user_js($plugin) { ?>
jQuery(function($){
	$("#et-main-area").prepend($("#wtfdivi012-widget-area-wrap"));
});
<?php 
}
add_action('wp_footer.js', 'db012_user_js');
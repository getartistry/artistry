<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

// Create the new widget area
function wtfdivi003_widget_area() {
   register_sidebar(array(
   'name' => 'Header',
   'id' => 'wtfdivi003-widget-area',
   'before_widget' => '<div id="%1$s" class="et_pb_widget %2$s">',
   'after_widget' => '</div> <!-- end .et_pb_widget -->',
   'before_title' => '<h4 class="widgettitle">',
   'after_title' => '</h4>',
   ));
}
add_action('widgets_init', 'wtfdivi003_widget_area');

function wtfdivi003_add_widget_area_to_footer() { ?>
<div style="display:none"><div id="wtfdivi003-widget-area-wrap"><?php dynamic_sidebar('wtfdivi003-widget-area'); ?></div></div>
<?php
}
add_action('wp_footer', 'wtfdivi003_add_widget_area_to_footer');

function db003_user_css($plugin) { ?>
#wtfdivi003-widget-area-wrap { display:none; float:right; max-width: 500px; clear:right; position:relative; }
#wtfdivi003-widget-area-wrap .et_pb_widget { margin-right:0px; }
#wtfdivi003-widget-area-wrap .et_pb_widget:last-child { margin-bottom: 18px; }
.et-fixed-header #wtfdivi003-widget-area-wrap .et_pb_widget:last-child { margin-bottom: 10px; }
@media only screen and ( max-width: 980px ) { 
	#wtfdivi003-widget-area-wrap .et_pb_widget:last-child { margin-bottom: 0px; }
}
@media only screen and ( max-width: 768px ) {
	#wtfdivi003-widget-area-wrap .et_pb_widget:first-child { margin-top: 18px; }
}
/* Move the sub menus up slightly to avoid unselectable sub menu issue */
ul.sub-menu { margin-top: -3px; }
<?php 
}
add_action('wp_head.css', 'db003_user_css');

function db003_user_js($plugin) { ?>
jQuery(function($){
	$("#et-top-navigation").after($("#wtfdivi003-widget-area-wrap"));
	$("#wtfdivi003-widget-area-wrap").first().show();
});
<?php 
}
add_action('wp_footer.js', 'db003_user_js');
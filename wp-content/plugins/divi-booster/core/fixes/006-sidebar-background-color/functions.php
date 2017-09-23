<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function db006_user_css($plugin) { 
	list($name, $option) = $plugin->get_setting_bases(__FILE__); ?>
	@media (min-width: 981px) {
		#main-content div.container:before{
			-moz-background-clip: padding;   
			-webkit-background-clip: padding; 
			background-clip: padding-box;     
		}
		.et_right_sidebar #main-content div.container:before { 
			border-right-style: solid !important; 
			border-right-color: <?php esc_html_e(@$option['bgcol']); ?> !important;
			border-right-width: 0px !important; 
		}
		.et_left_sidebar #main-content div.container:before { 
			border-left-style: solid !important; 
			border-left-color: <?php esc_html_e(@$option['bgcol']); ?> !important;
			border-left-width: 0px !important; 
		}
		#sidebar { position: relative; }
	}
	@media (max-width: 980px) {
		#sidebar { 
			background-color: <?php esc_html_e(@$option['bgcol']); ?>; 
			padding: 30px !important; 
		}
		#main-content { padding-bottom: 30px; }
	}
<?php
}
add_action('wp_head.css', 'db006_user_css');

function db006_user_js($plugin) { ?>
jQuery(function($){
	
	db006_update_sidebar_bg_width();
	$(window).resize(function(){ db006_update_sidebar_bg_width(); });	
	
	function db006_update_sidebar_bg_width() { 
		db006_inlinecss('db006_sidebar_width', '@media (min-width: 981px) { .et_right_sidebar #main-content div.container:before { border-right-width:'+($('#sidebar').width()+60)+'px !important; right:-30px !important;}.et_left_sidebar #main-content div.container:before { border-left-width:'+($('#sidebar').width()+60)+'px !important; left:-30px !important;}}');
	}
	
	function db006_inlinecss(id, css) {
		$('#'+id).remove();
		jQuery('head').append('<style id="'+id+'">'+css+'</style>');
	}
});
<?php 
}
add_action('wp_footer.js', 'db006_user_js');
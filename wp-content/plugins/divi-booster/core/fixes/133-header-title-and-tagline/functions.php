<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

// === Try to do it with output buffering === //

function db133_textlogo_set_up_buffer(){
    if ( is_feed() || is_admin() ){ return; }
	try { 
		if (ini_get('output_buffering')) {
			ob_start('db133_textlogo_filter_page');
		}
	} catch (Exception $e) { }
}
add_action('wp', 'db133_textlogo_set_up_buffer', 10, 0);
 
function db133_textlogo_filter_page($content){
	$title = esc_html(get_bloginfo('name'));
	$tagline = esc_html(get_bloginfo('description'));
	$content = preg_replace('#(<img.*id="logo".*/>)#U','\\1<h1 id="logo-text">'.$title.'</h1> <h5 id="logo-tagline">'.$tagline.'</h5>', $content); 
    return $content;
}

// === jQuery fallback if unable to do via output buffering === //

function db133_user_js($plugin) { ?>
jQuery(function($) {
	if (!$('#logo-text').length) {
		$('#logo').after('<h1 id="logo-text"><?php esc_html_e(get_bloginfo('name')); ?></h1> <h5 id="logo-tagline"><?php esc_html_e(get_bloginfo('description')); ?></h5>');
	}
});
<?php 
}
add_action('wp_footer.js', 'db133_user_js');

function db133_user_css($plugin) { ?>
#logo { 
	padding-right: 10px; 
}
#logo-text, #logo-tagline { 
	margin:0; 
	padding:0; 
	display:inline;
	vertical-align: middle;
}
#logo-tagline { 
	opacity: 0.7; 
	margin-left: 16px; 
	vertical-align: sub; 
}
@media only screen and (max-width: 767px) { 
	#logo-tagline { 
		display: none; 
	}
}
.et_hide_primary_logo .logo_container { 
	height: 100% !important; 
	opacity: 1 !important; 
}
.et_hide_primary_logo .logo_container #logo { 
	display: none; 
}
<?php 
}
add_action('wp_head.css', 'db133_user_css'); 

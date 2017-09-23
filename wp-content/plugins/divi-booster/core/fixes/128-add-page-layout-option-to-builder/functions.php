<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

function divibooster128_admin_css() { 
?>
<style>
/* Show the layout settings */
.et_pb_page_layout_settings { 
	display:block !important; 
}</style>
<?php
};

function divibooster128_admin_js() { 
?>
<script>
jQuery(function($){
	$('#et_pb_toggle_builder:not(.et_pb_builder_is_used)').click(function(){
		 $('#et_pb_page_layout').val('et_full_width_page');
	});
	
	$(document).on('click', '[data-action="deactivate_builder"] .et_pb_prompt_proceed', function() { 
		$('#et_pb_page_layout').val('et_right_sidebar');
	});
});
</script>
<?php
};

function divibooster128_user_css() {  ?>
/* make the rows fill the content area */
.page-template-default.et_pb_pagebuilder_layout:not(.et_full_width_page) #content-area #left-area .et_pb_row {
	width: 100%;
}

/* Hide the page title / featured image */
.page-template-default.et_pb_pagebuilder_layout:not(.et_full_width_page) .et_featured_image, 
.page-template-default.et_pb_pagebuilder_layout:not(.et_full_width_page) .main_title { 
	display: none; 
}

/* Remove excess padding at start */
.page-template-default.et_pb_pagebuilder_layout:not(.et_full_width_page) #main-content .container { 
	padding-top: 0px; 
}
.page-template-default.et_pb_pagebuilder_layout:not(.et_full_width_page) #sidebar { 
	margin-top: 58px; 
}
<?php
};

// Only make available in Divi. Would kill extra as et_pb_is_pagebuilder_used() not pluggable.
if (divibooster_is_divi()) {
	
	// Register the user CSS
	add_action('wp_head.css', 'divibooster128_user_css');	
	
	// When on pages...
	if ((isset($_GET['post_type']) and $_GET['post_type'] == 'page') || 
		(isset($_GET['post']) and get_post_type($_GET['post']) == 'page')) {
			
		// Register the admin / CSS
		add_action('admin_head', 'divibooster128_admin_css');
		add_action('admin_head', 'divibooster128_admin_js');
		
		// Fix the right sidebar default issue
		//add_filter('get_post_metadata', 'db128_fix_right_sidebar_default', 10, 4);
		//add_action('save_post', 'db128_save_post_function', 1000, 3); // Run after main post save
	}
	
	// Override et_pb_is_pagebuilder_used() to make page.php think pagebuilder not used
	if (!function_exists('et_pb_is_pagebuilder_used')) {
		
		function et_pb_is_pagebuilder_used( $page_id ) {
			
			try {
				// Get the function caller
				$bt = debug_backtrace();
				$caller = array_shift($bt);
				
				// If called from within page.php template, 
				if (isset($caller['file']) and basename($caller['file'])==='page.php') {
					$layout = get_post_meta($page_id, '_et_pb_page_layout', true);
					
					// and we are using a sidebar
					if ($layout!=='et_full_width_page') {
						
						// pretend that this isn't pagebuilder
						return false;
					}
				}
			} catch (Exception $e) {}
			
			// Otherwise, return normal result
			return ( 'on' === get_post_meta( $page_id, '_et_pb_use_builder', true ) );
		}
	}
}

// === Fix right sidebar default on existing pages ===

// Filter result to return full-width instead of right sidebar default (unless user has actually chosen right sidebar)
function db128_fix_right_sidebar_default($null, $object_id, $meta_key, $single) {
	global $post; 
	
	static $using_builder;
	
	if (!isset($using_builder)) { 	
		$using_builder = ('on' === get_post_meta($post->ID, '_et_pb_use_builder', true));
	}
	
	if ($using_builder) {
		
		// Check if already fixed
		remove_filter('get_post_metadata', 'db128_fix_right_sidebar_default', 10);
		$fixed = get_post_meta($post->ID, '_et_pb_page_layout_db_right_sidebar_fixed', true);
		add_filter('get_post_metadata', 'db128_fix_right_sidebar_default', 10, 4);
		
		// If not fixed, override right sidebar default
		if (!$fixed && $meta_key === '_et_pb_page_layout') {
			return array('et_full_width_page');
		}
	}
	
	return null; // Go on with normal execution
}

// If builder post updated, record that right sidebar setting now fixed
function db128_save_post_function($post_id, $post, $update) {
	
	$using_builder = ('on' === get_post_meta($post_id, '_et_pb_use_builder', true));
	
	if ($update && $using_builder) {

		update_post_meta($post_id, '_et_pb_page_layout_db_right_sidebar_fixed', true);
	}
}



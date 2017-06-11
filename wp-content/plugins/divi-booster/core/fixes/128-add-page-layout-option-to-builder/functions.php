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
	
	// Register the user and admin CSS
	add_action('wp_head.css', 'divibooster128_user_css');	
	if ((isset($_GET['post_type']) and $_GET['post_type'] == 'page') || 
		(isset($_GET['post']) and get_post_type($_GET['post']) == 'page')) {
		add_action('admin_head', 'divibooster128_admin_css');
		add_action('admin_head', 'divibooster128_admin_js');
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



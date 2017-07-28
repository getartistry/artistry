<?php
if (!defined('ABSPATH')) { exit(); } // No direct access

list($name, $option) = $this->get_setting_bases(__FILE__);

// =========== General - All Post Types with an Editor Box ==================== //

/* Handle option to use single shared libary */
if (isset($option['shared-library']) && $option['shared-library']==='1') {
	add_filter('et_pb_show_all_layouts_built_for_post_type', 'db099_use_page_layout_library_for_cpts');
}
function db099_use_page_layout_library_for_cpts() { return 'page'; }

/* Enable Divi Builder on all post types with an editor box */
function wtfdivi099_add_post_types($post_types) {
	foreach(get_post_types() as $pt) {
		if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
			$post_types[] = $pt;
		}
	} 
	return $post_types;
}
add_filter('et_builder_post_types', 'wtfdivi099_add_post_types');

/* Add Divi Custom Post Settings box */
function wtfdivi099_add_meta_boxes() {
	foreach(get_post_types() as $pt) {
		if (post_type_supports($pt, 'editor') and function_exists('et_single_settings_meta_box')) {
			add_meta_box('et_settings_meta_box', __('Divi Custom Post Settings', 'Divi'), 'et_single_settings_meta_box', $pt, 'side', 'high');
		}
	} 
}
add_action('add_meta_boxes', 'wtfdivi099_add_meta_boxes');

/* Ensure Divi Builder appears in correct location */
function wtfdivi099_admin_js() { 
	$s = get_current_screen();
	if(!empty($s->post_type) and $s->post_type!='page' and $s->post_type!='post') { 
?>
<script>
jQuery(function($){
	$('#et_pb_layout').insertAfter($('#et_pb_main_editor_wrap'));
});
</script>
<style>
#et_pb_layout { margin-top:20px; margin-bottom:0px }
</style>
<?php
	}
}
add_action('admin_head', 'wtfdivi099_admin_js');

// Ensure that Divi Builder framework is loaded - required for some post types when using Divi Builder plugin
add_filter('et_divi_role_editor_page', 'db099_load_builder_on_all_page_types');
function db099_load_builder_on_all_page_types($page) { 
	return isset($_GET['page'])?$_GET['page']:$page; 
}

// =========== CPT-Specific - Lifter LMS Courses ==================== //
// NB: General method works on lessons, but not on courses

// Set up once lifterlms loaded
function db099_lifterlms_init() {
	add_filter('et_builder_post_types', 'db099_lifterlms_add_post_types');
	add_action('admin_head', 'db099_lifterlms_admin_head', 11); // Run after main booster feature
}
add_action('lifterlms_loaded', 'db099_lifterlms_init');

/* Enable Divi Builder on all post types with an editor box */
function db099_lifterlms_add_post_types($post_types) {
	if (in_array('course', get_post_types())) {
		$post_types[] = 'course';
	} 
	return $post_types;
}

/* Ensure Divi Builder appears in correct location */
function db099_lifterlms_admin_head() { 
	global $post;
	$s = get_current_screen();
	if(!empty($s->post_type) and $s->post_type=='course') { 
?>
<script>
jQuery(function($){
	$('.et_pb_toggle_builder_wrapper').insertBefore($('#wp-content-wrap')).show();
	$('#et_pb_layout').insertAfter($('#wp-content-wrap'));
	
	$(document).on('mouseup', '#et_pb_toggle_builder:not(.et_pb_builder_is_used)', function() {
		$('#wp-content-wrap').hide();
	});	
	$(document).on('click', '[data-action="deactivate_builder"] .et_pb_prompt_proceed', function() {
		$('#wp-content-wrap').show();
	});
	<?php
	$is_builder_used = 'on' === get_post_meta( $post->ID, '_et_pb_use_builder', true ) ? true : false;
	if ($is_builder_used) {
	?>
	$('#wp-content-wrap').hide();
	<?php 
	} else {
	?>
	$('#wp-content-wrap').show();
	<?php
	}
	?>
});
</script>
<style>
#et_pb_layout { margin-top:20px; margin-bottom:0px }
.et_pb_toggle_builder_wrapper { display:none; }
</style>
<?php
	}
}


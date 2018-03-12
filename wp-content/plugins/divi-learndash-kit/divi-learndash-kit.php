<?php
/*
Plugin Name: Divi Learndash Kit
Plugin URI: 
Description: Improves integration between Divi and Learndash
Author: Divi Booster
Version: 1.0.5
Author URI: http://www.divibooster.com
*/

define('DLK_VERSION', '1.0.5');
define('DLK_VERSION_OPTION', 'divi-learndash-kit');
define('DLK_PATH', dirname(__FILE__).'/');

// === Updates ===
$updatename = 'divi-learndash-kit';
$updateurl = 'https://d2hmoepawdq173.cloudfront.net/updates.json'; 
include_once(DLK_PATH.'updates/plugin-update-checker.php');
try {
	$MyUpdateChecker = new DMWP_PluginUpdateChecker_1_0_0($updateurl, __FILE__, $updatename);
} catch (Exception $e) { echo "Update error: ".$e->getMessage(); exit; }


// Load the helper functions
include_once(DLK_PATH.'functions.php');

// Supported post types
function dlk_get_learndash_post_types() {
	return array(
		'sfwd-courses',			// learndash courses
		'sfwd-lessons',			// learndash lessons,
		'sfwd-quiz',			// learndash quizzes
		'sfwd-topic',			// learndash topics
		'sfwd-certificates',	// learndash certificates
		'groups'				// learndash groups
	);
}

// Load the settings
include_once(DLK_PATH.'admin/settings-page.php');

// Custom modules
function dlk_get_custom_modules() {
	return array(
		'et_pb_ld_profile',
		'et_pb_ld_course_list',
		'et_pb_ld_lesson_list',
		'et_pb_ld_topic_list',
		'et_pb_ld_quiz_list',
		'et_pb_ld_course_progress',
		'et_pb_ld_course_info',
		'et_pb_ld_user_course_points',
		'et_pb_ld_video',
		'et_pb_ld_user_groups',
		'et_pb_ld_payment_buttons',
		'et_pb_ld_course_content',
		'et_pb_ld_course_expire_status'
	);
}

// Enable Divi Builder / Page Layout options
add_filter('et_builder_post_types', 'dlk_add_post_types');
add_action('add_meta_boxes', 'dlk_add_meta_boxes');
add_action('wp_enqueue_scripts', 'dlk_enqueue_user_scripts');
add_action('admin_enqueue_scripts', 'dlk_enqueue_admin_css');


// Do update actions
include_once(DLK_PATH.'update_actions.php');

// Testing
if (defined('DLK_DISABLE_LOCAL_CACHING')) { 
	dlk_clear_module_local_storage();
}

// Load custom modules
add_action('et_builder_ready', 'dlk_load_custom_modules');
function dlk_load_custom_modules() {
	include_once(DLK_PATH.'modules/module-template.php');
	foreach(dlk_get_custom_modules() as $slug) {
		include_once(DLK_PATH."modules/{$slug}.php");
	}
}

// === Enqueue LearnDash Course Grid files if needed ===

add_action("plugins_loaded", "dlk_enqueue_course_grid_files");
function dlk_enqueue_course_grid_files() {
	if (defined('LEARNDASH_COURSE_GRID_FILE')) {
		wp_enqueue_style( 'learndash_course_grid_css', plugins_url( 'style.css', LEARNDASH_COURSE_GRID_FILE) );
		wp_enqueue_script( 'learndash_course_grid_js', plugins_url( 'script.js', LEARNDASH_COURSE_GRID_FILE), array('jquery' ) );
		wp_enqueue_style( 'ld-cga-bootstrap', plugins_url( 'bootstrap.min.css', LEARNDASH_COURSE_GRID_FILE) );
	}
}


// === Enable Divi Builder on Learndash CPTs === //

/* Handle option to use single shared libary */
$shared_library = false;
if ($shared_library) {
	add_filter('et_pb_show_all_layouts_built_for_post_type', 'dlk_use_page_layout_library_for_cpts');
}
function dlk_use_page_layout_library_for_cpts() { return 'page'; }

/* Enable Divi Builder on all post types with an editor box */
function dlk_add_post_types($post_types) {
	foreach(dlk_get_learndash_post_types() as $pt) {
		if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
			$post_types[] = $pt;
		}
	} 
	return $post_types;
}

/* Make [user_groups] shortcode render Divi Builder content from Group CPT */
add_filter( 'do_shortcode_tag','dlk_process_shortcodes_in_user_groups',10,3);
function dlk_process_shortcodes_in_user_groups($output, $tag, $attr){
  if('user_groups' != $tag){ //make sure it is the right shortcode
    return $output;
  }
  return do_shortcode($output);
}

/* Add Divi Custom Post Settings box */
function dlk_add_meta_boxes() {
	foreach(dlk_get_learndash_post_types() as $pt) {
		if (post_type_supports($pt, 'editor') and function_exists('et_single_settings_meta_box')) {
			add_meta_box('et_settings_meta_box', __('Divi Custom Post Settings', 'Divi'), 'et_single_settings_meta_box', $pt, 'side', 'high');
		}
	} 
}

// Admin css to enable page layout settings
function dlk_enqueue_admin_css() { 
	wp_enqueue_style('divi-learndash-kit', plugins_url('admin/admin.css', __FILE__), array(), DLK_VERSION);
};

function dlk_enqueue_user_scripts() { 
	wp_enqueue_style('divi-learndash-kit', plugins_url('style.css', __FILE__), array(), DLK_VERSION);	
	wp_enqueue_script('divi-learndash-kit', plugins_url('script.js', __FILE__), array('jquery'), DLK_VERSION);	
};

/* Add option to show / hide the post title */
add_action('admin_head', 'dlk_add_show_title_option_to_metabox');
add_filter('body_class', 'dlk_add_show_title_body_class');

function dlk_add_show_title_option_to_metabox() { 
	global $post;
	
	if (in_array($post->post_type, dlk_get_learndash_post_types())) {
		$post_id = get_the_ID();
		$show_title = get_post_meta( $post_id, '_et_pb_show_title', true );
		?>
		<script>
		jQuery(function($){
			$('#et_settings_meta_box .inside').append($('<p class="et_pb_page_settings et_pb_single_title"><label for="et_single_title" style="display: block; font-weight: bold; margin-bottom: 5px;"><?php esc_html_e( 'Post Title', 'Divi' ); ?>: </label><select id="et_single_title" name="et_single_title"><option value="on" <?php echo str_replace("'", '"', selected( 'on', $show_title, false) ); ?>><?php esc_html_e( 'Show', 'Divi' ); ?></option><option value="off" <?php echo str_replace("'", '"', selected( 'off', $show_title, false)); ?>><?php esc_html_e( 'Hide', 'Divi' ); ?></option></select></p>'));
		});
		</script>
		<?php
	}
}

function dlk_add_show_title_body_class($classes) {
	global $post;
	
	if (in_array($post->post_type, dlk_get_learndash_post_types())) {
		$show_default_title = get_post_meta( $post->ID, '_et_pb_show_title', true );
		if ($show_default_title !== 'on') {
			$classes[] = 'dlk_hide_post_title';
		}
	}
	return $classes;
}

/* === End enable page layout option for learndash === */
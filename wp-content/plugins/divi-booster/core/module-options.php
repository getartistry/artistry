<?php

// === Init ===

$divibooster_module_shortcodes = array(
	'et_pb_team_member'=>'db_pb_team_member',
	'et_pb_gallery'=>'db_pb_gallery',
	'et_pb_slide'=>'db_pb_slide'
);

// Register shortcodes
divibooster_register_module_shortcodes(); // Register shortcodes

// Clear modified modules in local storage as necessary
add_action('booster_update', 'divibooster_clear_module_local_storage');
if (defined('DB_DISABLE_LOCAL_CACHING')) { 
	divibooster_clear_module_local_storage();
}

// Register custom db_filter_et_pb_layout filter for global content
add_filter('the_posts', 'divibooster_filter_global_modules');

// Add filters to module fields
add_action('et_builder_ready', 'db_add_module_field_filter', 11);

// Wrap the shortcodes
add_filter('the_content', 'divibooster_module_options_process_shortcodes');
add_filter('db_filter_et_pb_layout', 'divibooster_module_options_process_shortcodes_global');

// === Load the module options ===

$MODULE_OPTIONS_DIR = plugin_dir_path(__FILE__).'/module_options/';
include_once($MODULE_OPTIONS_DIR.'et_pb_team_member.php');
include_once($MODULE_OPTIONS_DIR.'et_pb_gallery.php');
include_once($MODULE_OPTIONS_DIR.'et_pb_slide.php');

// === Module option filters ===

// Add filters to builder elements
function db_add_module_field_filter() {
	if (isset($GLOBALS['shortcode_tags'])) {
		foreach($GLOBALS['shortcode_tags'] as $slug=>$data){
			if (is_array($data) && array_key_exists(0, $data)) {
				$obj = $data[0];
				if ($obj instanceof ET_Builder_Element) {
					$obj->whitelisted_fields = apply_filters("dbmo_{$slug}_whitelisted_fields", $obj->whitelisted_fields); 
					$obj->fields_unprocessed = apply_filters("dbmo_{$slug}_fields", $obj->fields_unprocessed); 
					$GLOBALS['shortcode_tags'][$slug][0] = $obj;
				}
			}
		}
	}
}


// === Shortcode wrapping ===

function divibooster_module_options_process_shortcodes($content) {
    $pattern = get_shortcode_regex();
	return preg_replace_callback('/'.$pattern.'/s', 'divibooster_module_options_sc_wrapper', $content);
}

function divibooster_module_options_process_shortcodes_global($content) {
    $pattern = get_shortcode_regex();
	return preg_replace_callback('/'.$pattern.'/s', 'divibooster_module_options_sc_wrapper_global', $content);
}

// Process outermost shortcodes in global modules - doesn't wrap outermost shortcodes as already done externally in the_content
function divibooster_module_options_sc_wrapper_global($match) {
	
	global $divibooster_module_shortcodes;
	
	$inner = isset($match[5])?$match[5]:'';
	$outer = isset($match[0])?$match[0]:'';
	
	$has_nested_shortcodes = (strpos($inner, '[et_pb_') !== false);
	
	// Recursively process nested shortcodes
	if ($has_nested_shortcodes) {
		$outer = str_replace($inner, divibooster_module_options_process_shortcodes($inner), $outer);
	} 
	
	return $outer;
}

function divibooster_module_options_sc_wrapper($match) {
	
	global $divibooster_module_shortcodes;
	
	$slug = isset($match[2])?$match[2]:'';
	$inner = isset($match[5])?$match[5]:'';
	$outer = isset($match[0])?$match[0]:'';
	$attr_str = isset($match[3])?$match[3]:'';
	$attrs = shortcode_parse_atts($attr_str);
	
	$is_global_module = isset($attrs['global_module']);
	$has_nested_shortcodes = (strpos($inner, '[et_pb_') !== false);
	
	// Recursively process nested shortcodes
	if (!$is_global_module && $has_nested_shortcodes) {
		$outer = str_replace($inner, divibooster_module_options_process_shortcodes($inner), $outer);
	} 
	
	// Wrap the shortcode, if module options exist for it
	if (isset($divibooster_module_shortcodes[$slug])) {
		$wrapper = $divibooster_module_shortcodes[$slug];
		$outer = "[{$wrapper}{$attr_str}]{$outer}[/{$wrapper}]";
	} 
	
	return $outer;
}

// === Register shortcodes ===

function divibooster_register_module_shortcodes(){
	
	global $divibooster_module_shortcodes;
	
	if (!empty($divibooster_module_shortcodes) and is_array($divibooster_module_shortcodes)) {
		foreach($divibooster_module_shortcodes as $etsc=>$dbsc) {
			add_shortcode($dbsc, 'divibooster_module_shortcode_callback');
		}
	}
}

// Shortcode callback
function divibooster_module_shortcode_callback($atts, $content, $tag) {
	$content = do_shortcode($content);
	$content = apply_filters("{$tag}_content", $content, $atts);
	return $content;
}

// === Avoid local caching === 

function divibooster_clear_module_local_storage() { 
	add_action('admin_head', 'divibooster_remove_from_local_storage');
}
function divibooster_remove_from_local_storage() { 

	global $divibooster_module_shortcodes;
	
	foreach($divibooster_module_shortcodes as $etsc=>$dbsc) {
		echo "<script>localStorage.removeItem('et_pb_templates_".esc_attr($etsc)."');</script>"; 
	}
}


// === Helper filters === 

// Add "db_filter_et_pb_layout" filter for builder layouts returned by WP_Query (on front end only)
function divibooster_filter_global_modules($posts) {
	
	// Apply filters to builder layouts
	if (!is_admin() && !empty($posts) && count($posts)==1) { // If have one single result
		
		$is_et_pb_layout = (isset($posts[0]->post_type) && $posts[0]->post_type == 'et_pb_layout');
		
		if ($is_et_pb_layout) { 
			$content = isset($posts[0]->post_content)?$posts[0]->post_content:''; 
			$posts[0]->post_content = apply_filters('db_filter_et_pb_layout', $content);
		}
	}
	
	return $posts;
}

// === Shortcode content functions ===

// get the classes assigned to the module
function divibooster_get_classes_from_content($content) {
	preg_match('#<div class="(et_pb_module [^"]*?)">#', $content, $m);
	$classes = empty($m[1])?array():explode(' ', $m[1]);
	return $classes;
}

// Get the order class from a list of module classes
// Return false if no order class found
function divibooster_get_order_class_from_content($module_slug, $content) {
	$classes = divibooster_get_classes_from_content($content);
	foreach($classes as $class) {
		if (preg_match("#^{$module_slug}_\d+$#", $class)) { return $class; }
	}
	return false;
}

function divibooster_module_options_credit() {
	return apply_filters('divibooster_module_options_credit', 'Added by Divi Booster');
}


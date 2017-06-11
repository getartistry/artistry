<?php

// === General ===

$divibooster_module_shortcodes = array(
	'et_pb_team_member'=>'db_pb_team_member',
	'et_pb_slider'=>'db_pb_slider',
	'et_pb_slide'=>'db_pb_slide',
	'et_pb_gallery'=>'db_pb_gallery'
);

// Wrap selected shortcodes
add_filter('the_content', 'divibooster_wrap_shortcodes'); 
function divibooster_wrap_shortcodes($content) { 
	global $divibooster_module_shortcodes;
	foreach($divibooster_module_shortcodes as $etsc=>$dbsc) {
		
		// Self-closing shortcode
		$content = preg_replace('#\['.$etsc.'(\s+[^\]]*?)\/\]#', '['.$dbsc.'\\1]['.$etsc.'\\1 /][/'.$dbsc.']', $content);
		
		// Non-self-closing shortcode
		$attrs = '\s+[^\]]*?[^\/]';
		$content = preg_replace('#\['.$etsc.'('.$attrs.')\](.*?)\[\/'.$etsc.'\]#s', 
								'['.$dbsc.'\\1]['.$etsc.'\\1]\\2[/'.$etsc.'][/'.$dbsc.']', $content);
	}
	
    return $content;
}

// Register shortcodes
add_action('init', 'divibooster_register_module_shortcodes', 1000);
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
	
	return apply_filters("{$tag}_content", $content, $atts);
}

// Clear modified modules in local storage as necessary
add_action('booster_update', 'divibooster_clear_module_local_storage');
if (defined('DB_DISABLE_LOCAL_CACHING')) { 
	divibooster_clear_module_local_storage();
}
function divibooster_clear_module_local_storage() { 
	add_action('admin_head', 'divibooster_remove_from_local_storage');
}
function divibooster_remove_from_local_storage() { 
	global $divibooster_module_shortcodes;
	foreach($divibooster_module_shortcodes as $etsc=>$dbsc) {
		echo "<script>localStorage.removeItem('et_pb_templates_".esc_attr($etsc)."');</script>"; 
	}
}

// Add module styling
add_action('admin_head', 'divibooster_module_setting_css');
function divibooster_module_setting_css() { 
?><style>.db_pb_credit { position:absolute;left:40px;margin-top:-16px; }</style><?php 
}

// === Shortcode content parsing ===

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

// === Gallery Module ===

// Add gallery options
add_filter('et_builder_module_fields_et_pb_gallery', 'db_pb_gallery_add_fields');
function db_pb_gallery_add_fields($fields) {
	$new_fields = array(); 
	foreach($fields as $k=>$v) {
		$new_fields[$k] = $v;
		if ($k === 'posts_number') { // Add after post number option
			$new_fields['db_images_per_row'] = array(
				'label' => 'Images Per Row',
				'type' => 'text',
				'option_category' => 'layout',
				'description' => '<span class="db_pb_credit">by Divi Booster</span>Define the number of images to show per row',
				'default' => '',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced'
				
			);
			$new_fields['db_images_per_row_tablet'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			$new_fields['db_images_per_row_phone'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
		}
	}
	return $new_fields;
}

// Apply gallery options
add_filter('db_pb_gallery_content', 'db_pb_gallery_filter_content', 10, 2);
function db_pb_gallery_filter_content($content, $args) {
	
	// Check options set
	if (empty($args['db_images_per_row'])) { return $content; }

	// Get the class
	$class = divibooster_get_order_class_from_content('et_pb_gallery', $content);
	if (!$class) { return $content; }
	
	// === Add CSS to the content ===
	
	$css = '';
	
	// Desktop
	$media_queries = array(
		'db_images_per_row'=>'(min-width: 981px)', 
		'db_images_per_row_tablet'=>'(min-width: 768px) and (max-width: 980px)', 
		'db_images_per_row_phone'=>'(max-width: 767px)'
	);
	foreach($media_queries as $k=>$mq) {
		if (!empty($args[$k]) && ($num = abs(intval($args[$k])))) {
			
			$width = 100/$num;

			$css.="
				@media only screen and {$mq} {
					.et_pb_column .{$class} .et_pb_gallery_item {
						margin: 0 !important;
						width: {$width}% !important;
						clear: none !important;
					}
					.et_pb_column .{$class} .et_pb_gallery_item:nth-of-type({$num}n+1) {
						clear: both !important; 
					}
				}
			";	
			
		}
	}
	
	if (!empty($css)) { $content.="<style>$css</style>"; }
	
	return $content;
}

// === Person Module ===

// Add website url field to module options
add_filter('et_builder_module_fields_et_pb_team_member', 'db_pb_team_member_add_fields');
function db_pb_team_member_add_fields($fields) {
	$new_fields = array(); 
	foreach($fields as $k=>$v) {
		if ($k === 'facebook_url') { // Add before facebook option
			$new_fields['website_url'] = array(
				'label' => 'Website Url',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => '<span class="db_pb_credit">by Divi Booster</span>Input Website Url',
				'default' => ''
			);
		}
		$new_fields[$k] = $v;
	}
	return $new_fields;
}

// Inject website icon into module's html
add_filter('db_pb_team_member_content', 'db_pb_team_member_filter_content', 10, 2);
function db_pb_team_member_filter_content($content, $args) {
	if (!empty($args['website_url'])) { 
	
		// Get url
		$url = $args['website_url'];
		$url = ($parts=parse_url($url) and empty($parts['scheme']))?"http://$url":$url; // Add http if missing
		
		// Ensure the social links list exists
		if (strpos($content, 'class="et_pb_member_social_links"')===false) { 
			$content = preg_replace('#(</div>\s*<!-- .et_pb_team_member_description -->)#', '<ul class="et_pb_member_social_links"></ul>\\1', $content);
		}
		
		// Add the website icon to the social links list
		$content = preg_replace('#(<ul[^>]*class="et_pb_member_social_links"[^>]*>)#', '\\1<li><a href="'.esc_attr($url).'" class="et_pb_font_icon db_pb_team_member_website_icon"></a></li>', $content);
	}
	return $content;
}

add_action('wp_head', 'db_pb_team_member_css');
function db_pb_team_member_css() { ?><style>.db_pb_team_member_website_icon:before{content:"\e0e3";}</style><?php }



// === Slide Module ===

// Add website url field to module options
add_filter('et_builder_module_fields_et_pb_slide', 'db_pb_slide_add_fields');
function db_pb_slide_add_fields($fields) {
	$new_fields = array(); 
	foreach($fields as $k=>$v) {
		$new_fields[$k] = $v;
		
		// Add second button
		if ($k === 'button_link') { 
			$new_fields['button_text_2'] = array(
				'label' => 'Button #2 Text',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => '<span class="db_pb_credit">by Divi Booster</span>Define the text for the second slide button',
				'default' => ''
			);
			$new_fields['button_link_2'] = array(
				'label' => 'Button #2 Url',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => '<span class="db_pb_credit">by Divi Booster</span>Input a destination URL for the second slide button.',
				'default' => ''
			);
		}
		
		// Add slide URL option
		if ($k === 'background_image') {
			$new_fields['db_background_url'] = array(
				'label' => 'Background Link URL',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => '<span class="db_pb_credit">by Divi Booster</span>Input a destination URL for clicks on the slide background',
				'default' => ''
			);
		}
		
	}
	return $new_fields;
}

// Inject website icon into module's html
add_filter('db_pb_slide_content', 'db_pb_slide_filter_content', 10, 2);
function db_pb_slide_filter_content($content, $args) {
	
	// Add second button to slide
	if (!empty($args['button_text_2'])) {
		
		// Get url
		if (!empty($args['button_link_2'])) { 
			$url = $args['button_link_2'];
			$url = ($parts=parse_url($url) and empty($parts['scheme']))?"http://$url":$url; // Add http if missing
		} 
		
		$content = preg_replace('#(<a href=".*?" class="et_pb_more_button et_pb_button">.*?</a>)#', '\\1<a '.((isset($url))?'href="'.esc_attr($url).'"':'').' class="et_pb_more_button et_pb_button db_pb_button_2">'.esc_html($args['button_text_2']).'</a>', $content);
	}
	
	// Make slide background clickable link
	if (!empty($args['db_background_url'])) {
		 
		$url = $args['db_background_url'];
		$url = ($parts=parse_url($url) and empty($parts['scheme']) and $args['db_background_url'][0]!='/' and $args['db_background_url'][0]!='#')?"http://$url":$url; // Add http if missing 
		
		// Add jquery to make correct slide clickable
		preg_match('#div class="et_pb_slide [^"]*? (et_pb_slide_\d+)\b#', $content, $m);

		if (!empty($m[1])) {
			
			$content.='<script>jQuery(function($){$(".'.esc_html($m[1]).'").click(function(){document.location="'.esc_attr($url).'";});});</script>';
			$content.='<style>.'.esc_html($m[1]).':hover{cursor:pointer;}</style>';
		}
	
	}
	
	return $content;
}


add_action('wp_head', 'db_pb_slide_css');
function db_pb_slide_css() { ?><style>#et_builder_outer_content .db_pb_button_2,.db_pb_button_2{margin-left:30px}</style><?php }

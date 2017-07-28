<?php
add_filter('dbmo_et_pb_slide_whitelisted_fields', 'dbmo_et_pb_slide_register_fields');
add_filter('dbmo_et_pb_slide_fields', 'dbmo_et_pb_slide_add_fields');
add_filter('db_pb_slide_content', 'db_pb_slide_filter_content', 10, 2);
add_action('wp_head', 'db_pb_slide_css');

function dbmo_et_pb_slide_register_fields($fields) {
	$fields[] = 'button_text_2';
	$fields[] = 'button_link_2';
	$fields[] = 'db_background_url';
	return $fields;
}

function dbmo_et_pb_slide_add_fields($fields) {
	$new_fields = array(); 
	foreach($fields as $k=>$v) {
		$new_fields[$k] = $v;
		
		// Add second button
		if ($k === 'button_link') { 
			$new_fields['button_text_2'] = array(
				'label' => 'Button #2 Text',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => 'Define the text for the second slide button. '.divibooster_module_options_credit(),
				'default' => '',
				'toggle_slug'=>'main_content'
			);
			$new_fields['button_link_2'] = array(
				'label' => 'Button #2 URL',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => 'Input a destination URL for the second slide button. '.divibooster_module_options_credit(),
				'default' => '',
				'toggle_slug'=>'link'
			);
		}
		
		// Add slide URL option
		if ($k === 'background_image') {
			$new_fields['db_background_url'] = array(
				'label' => 'Background Link URL',
				'type' => 'text',
				'option_category' => 'basic_option',
				'description' => 'Input a destination URL for clicks on the slide background. '.divibooster_module_options_credit(),
				'default' => '',
				'toggle_slug'=>'background'
			);
		}
		
	}
	return $new_fields;
}

// Process slide options
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


function db_pb_slide_css() { ?><style>#et_builder_outer_content .db_pb_button_2,.db_pb_button_2{margin-left:30px}</style><?php }

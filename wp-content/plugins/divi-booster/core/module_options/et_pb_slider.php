<?php
add_filter('dbmo_et_pb_slider_whitelisted_fields', 'dbmo_et_pb_slider_register_fields');
add_filter('dbmo_et_pb_slider_fields', 'dbmo_et_pb_slider_add_fields');
add_filter('db_pb_slider_content', 'db_pb_slider_filter_content', 10, 2);

function dbmo_et_pb_slider_register_fields($fields) {
	$fields[] = 'db_height';
	return $fields;
}

function dbmo_et_pb_slider_add_fields($fields) {

	// Add slider height option
	$fields['db_height'] = array(
		'label' => 'Height',
		'type' => 'range',
		'range_settings'  => array(
			'min'  => 0,
			'max'  => 1000,
			'step' => 1,
		),
		'option_category' => 'layout',
		'description' => 'Set a minimum height for the slider. '.divibooster_module_options_credit(),
		'mobile_options'  => true,
		'tab_slug'        => 'advanced',
		'toggle_slug'        => 'width',
		'validate_unit'       => true,
		'fixed_unit'          => 'px',
		'default'             => '500px'
	);	

	return $fields;
}

// Process slider options
function db_pb_slider_filter_content($content, $args, $module='et_pb_slider') {
	
	// Slider height
	if (!empty($args['db_height'])) {
		
		if (!function_exists('et_pb_generate_responsive_css')) { return; }
		
		// Check responsive status
		$responsive = empty($args['db_height_last_edited'])?false:et_pb_get_responsive_status($args['db_height_last_edited']);
		
		// Get the height on different devices
		$heights = array();
		$heights['desktop'] = max(0, intval($args['db_height']));
		if ($responsive && !empty($args['db_height_tablet'])) {
			$heights['tablet'] = max(0, intval($args['db_height_tablet']));
		}
		if ($responsive && !empty($args['db_height_phone'])) {
			$heights['phone'] = max(0, intval($args['db_height_phone']));
		}
		
		// Set min-height on the header
		et_pb_generate_responsive_css(
			$heights, 
			'%%order_class%% .et_pb_slide .et_pb_container', 
			'min-height', 
			$module
		);
		
		// Remove bottom padding on slide (Divi adds it based on min-height)
		et_pb_generate_responsive_css(
			array(
				'desktop'=>'0px'
			), 
			'%%order_class%% .et_pb_slide', 
			'padding-bottom', 
			$module,
			' !important;'
		);
		
		// Reduce padding round description
		// - Allows smaller sliders
		// - Just specific enough to override default, but not custom padding setting
		// - Don't make it zero, to avoid slide getting flagged as empty
		et_pb_generate_responsive_css(
			array(
				'desktop'=>array(
					'padding-bottom' => '1%',
					'padding-top' => '1%'
				)
			), 
			'%%order_class%% div.et_pb_slide_description', 
			'', 
			$module
		);
		
	}
	
	return $content;
}


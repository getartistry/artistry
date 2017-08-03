<?php

add_filter('dbmo_et_pb_gallery_whitelisted_fields', 'dbmo_et_pb_gallery_register_fields');
add_filter('dbmo_et_pb_gallery_fields', 'dbmo_et_pb_gallery_add_fields');
add_filter('db_pb_gallery_content', 'db_pb_gallery_filter_content', 10, 2);

function dbmo_et_pb_gallery_register_fields($fields) {
	$fields[] = 'db_images_per_row';
	$fields[] = 'db_images_per_row_tablet';
	$fields[] = 'db_images_per_row_phone';
	$fields[] = 'db_image_max_width';
	$fields[] = 'db_image_max_width_tablet';
	$fields[] = 'db_image_max_width_phone';
	$fields[] = 'db_image_max_height';
	$fields[] = 'db_image_max_height_tablet';
	$fields[] = 'db_image_max_height_phone';
	$fields[] = 'db_image_row_spacing';
	$fields[] = 'db_image_row_spacing_tablet';
	$fields[] = 'db_image_row_spacing_phone';
	$fields[] = 'db_image_center_titles';
	$fields[] = 'db_image_object_fit';
	return $fields;
}

function dbmo_et_pb_gallery_add_fields($fields) {
	$new_fields = array(); 
	foreach($fields as $k=>$v) {
		$new_fields[$k] = $v;
		if ($k === 'posts_number') { // Add after post number option
		
			// Images per row
			$new_fields['db_images_per_row'] = array(
				'label' => 'Images Per Row',
				'type' => 'text',
				'option_category' => 'layout',
				'description' => 'Define the number of images to show per row. '.divibooster_module_options_credit(),
				'default' => '',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'        => 'layout'
				
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
			
			// Max width
			$new_fields['db_image_max_width'] = array(
				'label' => 'Image Max Width',				
				'type' => 'range',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'option_category' => 'layout',
				'description' => 'Define the max width of images (as % of normal image width). '.divibooster_module_options_credit(),
				'default' => '',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'        => 'layout'
				
			);
			$new_fields['db_image_max_width_tablet'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			$new_fields['db_image_max_width_phone'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			
			
			// Max height
			$new_fields['db_image_max_height'] = array(
				'label' => 'Image Max Height',				
				'type' => 'range',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '1000',
					'step' => '1',
				),
				'option_category' => 'layout',
				'description' => 'Define the max height of images (as % of normal image width). '.divibooster_module_options_credit(),
				'default' => '',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'        => 'layout'
				
			);
			$new_fields['db_image_max_height_tablet'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			$new_fields['db_image_max_height_phone'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			
			
			// Row spacing
			$new_fields['db_image_row_spacing'] = array(
				'label' => 'Image Row Spacing',				
				'type' => 'range',
				'range_settings'  => array(
					'min'  => '1',
					'max'  => '100',
					'step' => '1',
				),
				'option_category' => 'layout',
				'description' => 'Define the space between rows (as % of content width). '.divibooster_module_options_credit(),
				'default' => '',
				'mobile_options'  => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'        => 'layout'
				
			);
			$new_fields['db_image_row_spacing_tablet'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			$new_fields['db_image_row_spacing_phone'] = array(
				'type' => 'skip',
				'tab_slug' => 'advanced',
				'default'=>'',
			);
			
			// Center titles
			$new_fields['db_image_center_titles'] = array(
				'label' => 'Title Alignment',
				'type'            => 'select',
				'option_category' => 'layout',
				'options' => array(
					'left'   => esc_html__( 'Left', 'et_builder' ),
					'center' => esc_html__( 'Center', 'et_builder' ),
					'right'  => esc_html__( 'Right', 'et_builder' ),
				),
				'default'           => 'off',
				'description' => 'Adjust the image title text alignment. '.divibooster_module_options_credit(),
				'default' => '',
				'tab_slug' => 'advanced',
				'toggle_slug'        => 'title'
			);
			
			// Object fit
			$new_fields['db_image_object_fit'] = array(
				'label' => 'Image Scaling',
				'type' => 'select',
				'options'         => array(
					'initial' => esc_html__( 'Fill', 'et_builder' ),
					'cover'   => esc_html__( 'Cover', 'et_builder' ),
					'contain' => esc_html__( 'Fit', 'et_builder' ),
					'none' => esc_html__( 'Actual Size', 'et_builder' ),
				),
				'default'         => 'initial',
				'option_category' => 'layout',
				'description' => 'Choose how the image fills its bounding box. '.divibooster_module_options_credit(),
				'default' => '',
				'tab_slug' => 'advanced',
				'toggle_slug'        => 'layout'
			);
			
		}
	}
	return $new_fields;
}

// Apply gallery options
function db_pb_gallery_filter_content($content, $args) {
	
	// Check options set
	//if (empty($args['db_images_per_row'])) { return $content; }

	// Get the class
	$class = divibooster_get_order_class_from_content('et_pb_gallery', $content);
	if (!$class) { return $content; }
	
	// === Add CSS to the content ===
	
	$css = '';
	
	// Images per row
	if (!empty($args['db_images_per_row'])) {
	
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
	}
	
	// Max width
	if (!empty($args['db_image_max_width'])) {
	
		$media_queries = array(
			'db_image_max_width'=>'(min-width: 981px)', 
			'db_image_max_width_tablet'=>'(min-width: 768px) and (max-width: 980px)', 
			'db_image_max_width_phone'=>'(max-width: 767px)'
		);
		foreach($media_queries as $k=>$mq) {
			if (!empty($args[$k]) && ($num = abs(intval($args[$k])))) {

				$css.="
					@media only screen and {$mq} {
						
						/* Max width */
						.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_title, 
						.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_image { 
							max-width: {$num}%; 
							margin-left: auto !important; 
							margin-right: auto !important; 
						}
						.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_image img {
							width: 100%; 
						}
					}
				";	
				
			}
		}
	}
	
	// Max Height
	if (!empty($args['db_image_max_height'])) {

		$media_queries = array(
			'db_image_max_height'=>'(min-width: 981px)', 
			'db_image_max_height_tablet'=>'(min-width: 768px) and (max-width: 980px)', 
			'db_image_max_height_phone'=>'(max-width: 767px)'
		);
		foreach($media_queries as $k=>$mq) {
			if (!empty($args[$k]) && ($num = abs(intval($args[$k])))) {

				$css.="
					@media only screen and {$mq} {
						
						/* Max height */
						.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_image { 
							position: relative;
							padding-bottom: {$num}%;
							height: 0;
							overflow: hidden;
						}
						.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_image img { 
							position: absolute;
							top: 0;
							left: 0;
							width: 100%;
							height: 100%;
						}
					}
				";	
				
			}
		}
	}
	
	// Row spacing
	if (!empty($args['db_image_row_spacing'])) {
	
		$media_queries = array(
			'db_image_row_spacing'=>'(min-width: 981px)', 
			'db_image_row_spacing_tablet'=>'(min-width: 768px) and (max-width: 980px)', 
			'db_image_row_spacing_phone'=>'(max-width: 767px)'
		);
		foreach($media_queries as $k=>$mq) {
			if (!empty($args[$k]) && ($num = abs(intval($args[$k])))) {

				$css.="
					@media only screen and {$mq} {
						
						/* Row spacing */
						.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item { 
							padding-bottom: {$num}% !important; 
						}
					}
				";	
				
			}
		}
	}
	
	// Center image titles
	if (!empty($args['db_image_center_titles'])) {
		
		$align = $args['db_image_center_titles'];
		
		$css.="
			/* Center titles */
			.et_pb_column .{$class} .et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_title {
				text-align: {$align};
			}
		";
	}
	
	// Object fit
	if (!empty($args['db_image_object_fit'])) {
		
		$object_fit = $args['db_image_object_fit'];
		
		$css.="
			/* Image fit */
			.et_pb_gallery_item.et_pb_grid_item .et_pb_gallery_image img { 
				object-fit: $object_fit !important; 
			}
		";
	}
	
	if (!empty($css)) { $content.="<style>$css</style>"; }
	
	return $content;
}
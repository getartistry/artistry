<?php 

add_filter('dbmo_et_pb_countdown_timer_whitelisted_fields', 'dbmo_et_pb_countdown_timer_register_fields');
add_filter('dbmo_et_pb_countdown_timer_fields', 'dbmo_et_pb_countdown_timer_add_fields');
add_filter('db_pb_countdown_timer_content', 'db_pb_countdown_timer_filter_content', 10, 2);

// Define the label fields
function dbmo_et_pb_countdown_timer_get_label_fields() {
	$label_fields = array(
		'label_days_full'=>array('title'=>'Days Label', 'default'=>'Day(s)'),
		'label_days_short'=>array('title'=>'Days Label (Mobile)', 'default'=>'Day'),
		'label_hours_full'=>array('title'=>'Hours Label', 'default'=>'Hour(s)'),
		'label_hours_short'=>array('title'=>'Hours Label (Mobile)', 'default'=>'Hrs'),
		'label_mins_full'=>array('title'=>'Minutes Label', 'default'=>'Minute(s)'),
		'label_mins_short'=>array('title'=>'Minutes Label (Mobile)', 'default'=>'Min'),
		'label_secs_full'=>array('title'=>'Seconds Label', 'default'=>'Second(s)'),
		'label_secs_short'=>array('title'=>'Seconds Label (Mobile)', 'default'=>'Sec')
	);
	
	// Translate the defaults
	foreach($label_fields as $k=>$v) {
		$label_fields[$k]['default'] = __($v['default'], 'et_builder');
	}
	
	return $label_fields;
}

function dbmo_et_pb_countdown_timer_register_fields($fields) {
	$label_fields = dbmo_et_pb_countdown_timer_get_label_fields();
	$fields += array_keys($label_fields);
	return $fields;
}

function dbmo_et_pb_countdown_timer_add_fields($fields) {

	$label_fields = dbmo_et_pb_countdown_timer_get_label_fields();
	
	// Add the custom label toggle
	$fields['use_custom_labels'] = array(
		'label' => 'Use Custom Labels',
		'type' => 'yes_no_button',
		'options' => array(
			'off' => esc_html__( 'No', 'et_builder' ),
			'on'  => esc_html__( 'yes', 'et_builder' ),
		),
		'option_category' => 'basic_option',
		'description' => 'Change the text of the labels. '.divibooster_module_options_credit(),
		'default' => 'off',
		'toggle_slug' => 'main_content',
		'affects' => array_keys($label_fields)
	);
	
	// Add the label fields
	foreach($label_fields as $k=>$label) {
		$fields[$k] = array(
			'label' => $label['title'],
			'type'  => 'text',
			'option_category' => 'basic_option',
			'default' => $label['default'],
			'toggle_slug' => 'main_content',
			'depends_default' => true,
		);
	}
	
	return $fields;
}

function db_pb_countdown_timer_filter_content($content, $args) {

	// Apply custom labels
	if (!empty($args['use_custom_labels']) && $args['use_custom_labels'] === 'on') {
		
		$label_fields = dbmo_et_pb_countdown_timer_get_label_fields();
		
		foreach($label_fields as $k=>$label) {
			if (isset($args[$k])) {
				$size = preg_replace('/.*_(full|short)/', '\\1', $k);
				$content = str_replace(
					'data-'.$size.'="'.esc_attr($label['default']).'"', 
					'data-'.$size.'="'.esc_attr($args[$k]).'"', 
					$content
				);
			}
		}		
	}

	return $content;
}
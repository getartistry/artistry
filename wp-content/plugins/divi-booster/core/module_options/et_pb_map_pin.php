<?php
add_filter('dbmo_et_pb_map_pin_whitelisted_fields', 'dbmo_et_pb_map_pin_register_fields');
add_filter('dbmo_et_pb_map_pin_fields', 'dbmo_et_pb_map_pin_add_fields');
add_filter('db_pb_map_pin_content', 'db_pb_map_pin_filter_content', 10, 2);
add_action('wp_enqueue_scripts', 'db_pb_map_pin_register_scripts');

function dbmo_et_pb_map_pin_register_fields($fields) {
	$fields[] = 'db_start_open';
	return $fields;
}

function dbmo_et_pb_map_pin_add_fields($fields) {

	// Option to show pin details by default
	$fields['db_start_open'] = array(
		'label' => 'Show Details by Default',
		'type' => 'yes_no_button',
		'options' => array(
			'off' => esc_html__( 'No', 'et_builder' ),
			'on'  => esc_html__( 'yes', 'et_builder' ),
		),
		'option_category' => 'basic_option',
		'description' => 'Choose whether marker pin details should be displayed right away or not. '.divibooster_module_options_credit(),
		'default' => 'off',
		'toggle_slug'=>'main_content'
	);
	
	return $fields;
}


function db_pb_map_pin_filter_content($content, $args) {
	
	// Show pin details by default
	if (!empty($args['db_start_open']) && $args['db_start_open'] === 'on') {
		$content = preg_replace('#(<div class="et_pb_map_pin[^"]*")#', '\\1 data-initial="open"', $content);
		
		// Load the js to actually apply open the pins
		wp_enqueue_script('db_pb_map_pin');
	}
	
	return $content;
}

function db_pb_map_pin_register_scripts() {
	wp_register_script('db_pb_map_pin', plugins_url('et_pb_map_pin.js', __FILE__), array(), BOOSTER_VERSION, true);
}
  
<?php

/**
 * Customizer controls: Live Custom CSS section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );

Kirki::add_field( 'dce', array(
	'type'		=> 'code',
	'settings'	=> 'dce_live_custom_css',
	'label'		=> __( 'Custom CSS:', 'divi-children-engine' ),
	'section'	=> 'dce_live_custom_css',
	'default'	=> '',
	'priority'	=> 1,
	'choices'	=> array(
		'language'	=> 'css',
		'theme'		=> 'monokai',
		'height'	=> 250,
	),
) );


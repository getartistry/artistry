<?php

/**
 * Customizer controls: Main Header section (horizontal navigation only)
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



check_production_mode( __FILE__ );


/* GROUP TITLE: General Main Header appearance */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mhga',
	'label'		=> __( 'Main Header general appearance:', 'divi-children-engine' ),
	'section'	=> 'dce_main_header',
	'priority'	=> 100,
) );


/* Show/Hide Main Header bottom border */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_main_header_border',
	'label'		=> __( 'Header bottom border', 'divi-children-engine' ),
	'section'	=> 'dce_main_header',
	'default'	=> '0 1px 0 rgba(0, 0, 0, 0.1)',
	'priority'	=> 110,
	'choices'	=> array(
		'0 1px 0 rgba(0, 0, 0, 0.1)'	=>	__( 'Show border', 'divi-children-engine' ),
		'none'							=>	__( 'Hide border', 'divi-children-engine' ),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#main-header',
			'function'	=> 'css',
			'property'	=> 'box-shadow',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#main-header',
			'function'	=> 'css',
			'property'	=> '-webkit-box-shadow',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#main-header',
			'function'	=> 'css',
			'property'	=> '-moz-box-shadow',
			'suffix'	=> '!important;',
		),
	),
) );


/* Show/Hide fixed header bottom shadow */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_main_header_fixed_shadow',
	'label'		=> __( 'Fixed header bottom shadow', 'divi-children-engine' ),
	'section'	=> 'dce_main_header',
	'default'	=> '0 0 7px rgba(0, 0, 0, 0.1)',
	'priority'	=> 120,
	'choices'	=> array(
		'0 0 7px rgba(0, 0, 0, 0.1)'	=>	__( 'Show shadow', 'divi-children-engine' ),
		'none'							=>	__( 'Hide shadow', 'divi-children-engine' ),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#main-header.et-fixed-header',
			'function'	=> 'css',
			'property'	=> 'box-shadow',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#main-header.et-fixed-header',
			'function'	=> 'css',
			'property'	=> '-webkit-box-shadow',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#main-header.et-fixed-header',
			'function'	=> 'css',
			'property'	=> '-moz-box-shadow',
			'suffix'	=> '!important;',
		),
	),
) );


/* GROUP TITLE: Main Header Menu */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mhga',
	'label'		=> __( 'Main Header Menu:', 'divi-children-engine' ),
	'description'	=> __( '(These settings do not affect the mobile menu)', 'divi-children-engine' ),
	'section'	=> 'dce_main_header',
	'priority'	=> 200,
) );


/* Menu items spacing */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_main_header_menu_spacing',
	'label'			=> __( 'Spacing between menu items (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_header',
	'default'		=> 22,
	'priority'		=> 210,
	'choices'	=> array(
		'min'	=> 2,
		'max'	=> 50,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#top-menu li',
			'function'	=> 'css',
			'property'	=> 'padding-right',
			'units'		=> 'px !important;',
		),
	),
) );


/* Add horizontal line below active link */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_main_header_active_underline',
	'label'		=> __( 'Add horizontal line below active link', 'divi-children-engine' ),
	'section'	=> 'dce_main_header',
	'default'	=> 'none',
	'priority'	=> 220,
	'choices'	=> array(
		'none'		=>	__( 'Default', 'divi-children-engine' ),
		'underline'	=>	__( 'Add line', 'divi-children-engine' ),
	),
) );


/* Active menu link underline thickness */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_main_header_active_underline_line',
	'label'			=> __( 'Line thickness (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_header',
	'default'		=> 3,
	'priority'		=> 230,
	'choices'	=> array(
		'min'	=> 1,
		'max'	=> 15,
		'step'	=> 1,
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_main_header_active_underline',
			'operator'	=> '==',
			'value'		=> 'underline',
		),
	),
) );


/* Active menu link underline color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_main_header_active_underline_color',
	'label'		=> __( 'Line color', 'divi-children-engine' ),
	'section'	=> 'dce_main_header',
	'default'	=> dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 240,
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_main_header_active_underline',
			'operator'	=> '==',
			'value'		=> 'underline',
		),
	),
) );

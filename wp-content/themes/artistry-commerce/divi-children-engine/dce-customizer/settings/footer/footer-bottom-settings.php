<?php

/**
 * Customizer controls: Footer Bottom section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Footer Bottom Layout */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_footer_bottom_layout',
	'label'			=> __( 'Footer Bottom Layout', 'divi-children-engine' ),
	'description' 	=> __( 'Choose Divi default (credits on the left side, social icons on the right), reversed (social icons left, credits right), or everything centered.', 'divi-children-engine' ),
	'section'		=> 'dce_footer_bottom',
	'default'		=> 'default',
	'priority'		=> 10,
	'choices'	=> array(
		'default'	=>	__( 'Default', 'divi-children-engine' ),
		'centered'	=>	__( 'Centered', 'divi-children-engine' ),
		'reversed'	=>	__( 'Reversed', 'divi-children-engine' ),
	),
) );


/* Footer Bottom top padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_bottom_toppadding',
	'label'			=> __( 'Footer Bottom top padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_footer_bottom',
	'default'		=> 15,
	'priority'		=> 100,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-bottom',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px',
		),
	),
) );


/* Footer Bottom Social Icons bottom padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_bottom_socialicons_bottompadding',
	'label'			=> __( 'Social Icons bottom padding (px)', 'divi-children-engine' ),
	'description' 	=> __( 'Vertical padding under the Social Icons when the centered layout is used.', 'divi-children-engine' ),
	'section'		=> 'dce_footer_bottom',
	'default'		=> 20,
	'priority'		=> 110,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback'  => array(
		array(
			'setting'	=> 'dce_footer_bottom_layout',
			'operator'	=> '==',
			'value'		=> 'centered',
		),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-bottom .et-social-icons',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),
) );


/* Footer Bottom bottom padding */	
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_bottom_bottompadding',
	'label'			=> __( 'Footer Bottom bottom padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_footer_bottom',
	'default'		=> 5,
	'priority'		=> 120,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-bottom',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px',
		),
	),
) );


/* Span Footer Bottom to full width */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_footer_bottom_fullwidth',
	'label'			=> __( 'Span Footer Bottom to full width', 'divi-children-engine' ),
	// 'description' 	=> __( 'Choose Divi default (credits on the left side, social icons on the right), reversed (social icons left, credits right), or everything centered.', 'divi-children-engine' ),
	'section'		=> 'dce_footer_bottom',
	'default'		=> 'default',
	'priority'		=> 200,
	'choices'	=> array(
		'default'	=>	__( 'Default', 'divi-children-engine' ),
		'fullwidth'	=>	__( 'Full Width', 'divi-children-engine' ),
	),
	'active_callback'  => array(
		array(
			'setting'	=> 'dce_footer_bottom_layout',
			'operator'	=> '!=',
			'value'		=> 'centered',
		),
	),
) );


/* Footer Bottom lateral padding */	
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_bottom_lateral_padding',
	'label'			=> __( 'Footer Bottom lateral padding (%)', 'divi-children-engine' ),
	'section'		=> 'dce_footer_bottom',
	'default'		=> 2,
	'priority'		=> 210,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 10,
		'step'	=> 0.1,
	),
	'active_callback'  => array(
		array(
			'setting'	=> 'dce_footer_bottom_fullwidth',
			'operator'	=> '==',
			'value'		=> 'fullwidth',
		),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-bottom .container',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> '%',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '#footer-bottom .container',
			'function'	=> 'css',
			'property'	=> 'padding-right',
			'units'		=> '%',
			'suffix'	=> ' !important;',
		),
	),
) );



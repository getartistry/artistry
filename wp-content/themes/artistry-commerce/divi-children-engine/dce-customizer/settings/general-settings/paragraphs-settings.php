<?php

/**
 * Customizer controls: Paragraphs section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Customize general paragraphs */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_paragraph_customize',
	'label'		=> __( 'Customize general paragraphs', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'default'	=> '0',
	'priority'	=> 10,
) );

$customize_paragraphs_callback = array(
	array(
		'setting'	=> 'dce_paragraph_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* GROUP TITLE: General Paragraph styles*/
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pps',
	'label'		=> __( 'General paragraph styles:', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'priority'	=> 100,
	'active_callback' => $customize_paragraphs_callback,
) );


/* Paragraph line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_paragraph_line_height',
	'label'		=> __( 'Paragraph line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'default'	=> 1.7,
	'priority'	=> 110,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> 'p',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_paragraphs_callback,
) );


/* Paragraph bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_paragraph_bottom_padding',
	'label'		=> __( 'Paragraph bottom padding (em)', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'default'	=> 1,
	'priority'	=> 120,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 10,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> 'p',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_paragraphs_callback,
) );


/* Set specific font size for paragraphs */
Kirki::add_field( 'dce', array(
	'type'		=> 'checkbox',
	'settings'	=> 'dce_paragraph_change_font_size',
	'label'		=> __( 'Set specific font size for paragraphs', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'default'	=> '0',
	'priority'	=> 200,
	'active_callback' => $customize_paragraphs_callback,
) );


/* Paragraph font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_paragraph_font_size',
	'label'		=> __( 'Paragraph font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'default'	=> dce_get_divi_option( 'body_font_size', 14 ),
	'priority'	=> 210,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 30,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> 'p',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => array(
		array(
			'setting'	=> 'dce_paragraph_customize',
			'operator'	=> '==',
			'value'		=> '1',
		),
		array(
			'setting'  => 'dce_paragraph_change_font_size',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );


/* Paragraph text align */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_paragraph_text_align',
	'label'		=> __( 'Paragraph text align', 'divi-children-engine' ),
	'section'	=> 'dce_paragraphs',
	'default'	=> 'left',
	'priority'	=> 220,
	'choices'	=> array(
		'left'		=>	__( 'Left', 'divi-children-engine' ),
		'center'	=>	__( 'Center', 'divi-children-engine' ),
		'justify'	=>	__( 'Justify', 'divi-children-engine' ),
		'right'		=>	__( 'Right', 'divi-children-engine' ),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> 'p',
			'function'	=> 'css',
			'property'	=> 'text-align',
			'suffix'	=> '!important;',
		),
	),
	'active_callback' => $customize_paragraphs_callback,
) );


<?php

/**
 * Customizer controls: Lists section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Customize Unordered Lists */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_lists_ul_customize',
	'label'		=> __( 'Customize Unordered Lists (UL)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> '0',
	'priority'	=> 100,
) );

$customize_ul_callback = array(
	array(
		'setting'	=> 'dce_lists_ul_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* Customize Ordered Lists */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_lists_ol_customize',
	'label'		=> __( 'Customize Ordered Lists (OL)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 'default',
	'priority'	=> 200,
) );

$customize_ol_callback = array(
	array(
		'setting'	=> 'dce_lists_ol_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* GROUP TITLE: Unordered Lists styles*/
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_lul',
	'label'		=> __( 'Unordered Lists (UL):', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'priority'	=> 1000,
	'active_callback' => $customize_ul_callback,
) );


/* UL left padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ul_left_padding',
	'label'		=> __( 'Indentation (list left padding, em)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 1,
	'priority'	=> 1100,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 10,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ul',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.entry-content ul',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.comment-content ul',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ul',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_ul_callback,
) );


/* Unordered Lists line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ul_line_height',
	'label'		=> __( 'Line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 1.9,
	'priority'	=> 1110,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 5,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ul',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.entry-content ul',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.comment-content ul',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ul',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_ul_callback,
) );


/* Unordered Lists top padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ul_top_padding',
	'label'		=> __( 'List top padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 0,
	'priority'	=> 1120,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ul',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.entry-content ul',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.comment-content ul',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ul',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $customize_ul_callback,
) );


/* Unordered Lists bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ul_bottom_padding',
	'label'		=> __( 'List bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 23,
	'priority'	=> 1130,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ul',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.entry-content ul',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.comment-content ul',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ul',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $customize_ul_callback,
) );


/* Set specific font size for UL */
Kirki::add_field( 'dce', array(
	'type'		=> 'checkbox',
	'settings'	=> 'dce_lists_ul_change_font_size',
	'label'		=> __( 'Set specific font size for UL', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> '0',
	'priority'	=> 1300,
	'active_callback' => $customize_ul_callback,
) );


/* Unordered Lists font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ul_font_size',
	'label'		=> __( 'Unordered Lists font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> dce_get_divi_option( 'body_font_size', 14 ),
	'priority'	=> 1310,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 30,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ul',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.entry-content ul',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.comment-content ul',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ul',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => array(
		array(
			'setting'	=> 'dce_lists_ul_customize',
			'operator'	=> '==',
			'value'		=> 'custom',
		),	
		array(
			'setting'	=> 'dce_lists_ul_change_font_size',
			'operator'	=> '==',
			'value'		=> '1',
		),
	),
) );


/* Unordered Lists bullets style */
Kirki::add_field( 'dce', array(
	'type'		=> 'select',
	'settings'	=> 'dce_lists_ul_style_type',
	'label'		=> __( 'UL bullets style', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 'disc',
	'priority'	=> 1400,
	'choices'	=> array(
		'none'		=> 'None',
		'disc'		=> 'Default Disc Bullets',
		'square'	=> 'Squares',
		'circle'	=> 'Circles',
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ul',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '.entry-content ul',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '.comment-content ul',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ul',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
	),
	'active_callback' => $customize_ul_callback,
) );


/* GROUP TITLE: Ordered Lists styles*/
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_lol',
	'label'		=> __( 'Ordered Lists (OL):', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'priority'	=> 2000,
	'active_callback' => $customize_ol_callback,
) );


/* OL left padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ol_left_padding',
	'label'		=> __( 'Indentation (list left padding, em)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 0,
	'priority'	=> 2100,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 10,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ol',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.entry-content ol',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.comment-content ol',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ol',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_ol_callback,
) );


/* Ordered Lists line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ol_line_height',
	'label'		=> __( 'Line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 1.9,
	'priority'	=> 2110,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 5,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ol',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.entry-content ol',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> '.comment-content ol',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ol',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_ol_callback,
) );


/* Ordered Lists top padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ol_top_padding',
	'label'		=> __( 'List top padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 0,
	'priority'	=> 2120,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ol',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.entry-content ol',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.comment-content ol',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ol',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $customize_ol_callback,
) );


/* Ordered Lists bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ol_bottom_padding',
	'label'		=> __( 'List bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 23,
	'priority'	=> 2130,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ol',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.entry-content ol',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.comment-content ol',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ol',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $customize_ol_callback,
) );


/* Set specific font size for OL */
Kirki::add_field( 'dce', array(
	'type'		=> 'checkbox',
	'settings'	=> 'dce_lists_ol_change_font_size',
	'label'		=> __( 'Set specific font size for OL', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> '0',
	'priority'	=> 2300,
	'active_callback' => $customize_ol_callback,
) );


/* Ordered Lists font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_lists_ol_font_size',
	'label'		=> __( 'Unordered Lists font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> dce_get_divi_option( 'body_font_size', 14 ),
	'priority'	=> 2310,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 30,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ol',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.entry-content ol',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '.comment-content ol',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ol',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => array(
		array(
			'setting'	=> 'dce_lists_ol_customize',
			'operator'	=> '==',
			'value'		=> 'custom',
		),	
		array(
			'setting'	=> 'dce_lists_ol_change_font_size',
			'operator'	=> '==',
			'value'		=> '1',
		),
	),
) );


/* Ordered Lists bullets style */
Kirki::add_field( 'dce', array(
	'type'		=> 'select',
	'settings'	=> 'dce_lists_ol_style_type',
	'label'		=> __( 'OL bullets style', 'divi-children-engine' ),
	'section'	=> 'dce_lists',
	'default'	=> 'decimal',
	'priority'	=> 2400,
	'choices'	=> array(
		'decimal'				=> 'Default Numbers',
		'decimal-leading-zero'	=> 'Numbers with leading zero',
		'lower-alpha'			=> 'Alphabetical - Lower case',
		'upper-alpha'			=> 'Alphabetical - Upper case',
		'lower-roman'			=> 'Roman numbers - Lower case',
		'upper-roman'			=> 'Roman numbers - Upper case',
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#left-area ol',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '.entry-content ol',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '.comment-content ol',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> 'body.et-pb-preview #main-content .container ol',
			'function'	=> 'css',
			'property'	=> 'list-style-type',
			'suffix'	=> '!important;',
		),
	),
	'active_callback' => $customize_ol_callback,
) );

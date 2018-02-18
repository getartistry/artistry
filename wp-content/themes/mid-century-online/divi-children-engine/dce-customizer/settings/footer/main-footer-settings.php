<?php

/**
 * Customizer controls: Main Footer section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



check_production_mode( __FILE__ );


/* Footer layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_footer_columns_default',
	'label'		=> __( 'Customize Footer Layout', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> 'custom',
	'priority'	=> 100,
	'description'	=> __( 'Use Divi footer layout options or override them to further customize your footer columns with your child theme.', 'divi-children-engine' ),
	'help'	=> __( 'Your child theme lets you use up to 5 footer widget columns and allows you to customize the spacing between columns and the columns width ratio (for layouts with different column widths).', 'divi-children-engine' ),
	'choices'	=> array(
		'default'	=>	__( 'Use Divi layouts', 'divi-children-engine' ),
		'custom'	=>	__( 'Customize layout', 'divi-children-engine' ),	
	),
) );


/* INFO BOX: Custom footer layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'ib_mffl',
	'label'		=> __( 'You will find that a new "Footer Area #5" has been created by Divi Children in case you want to use a 5 column footer layout. If you do not, just leave "Footer Area #5" empty.', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 110,
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_footer_columns_default',
			'operator'	=> '==',
			'value'		=> 'custom',
			),
	),
) );


/* GROUP TITLE: Custom footer layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mffl',
	'label'		=> __( 'Custom footer layout:', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 200,
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_footer_columns_default',
			'operator'	=> '==',
			'value'		=> 'custom',
			),
	),
) );


/* Custom Footer Columns Layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-image',
	'settings'	=> 'dce_footer_columns_layout',
	'label'		=> __( 'Select your custom footer layout:', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 210,
	'default'	=> '0',
	'choices'	=> array(
		'0'	=> DCE_IMAGES_URL . '4-widget-layout.png',
		'1'	=> DCE_IMAGES_URL . '3-widget-layout.png',
		'2'	=> DCE_IMAGES_URL . '2-widget-layout.png',
		'3'	=> DCE_IMAGES_URL . '1-2-1-widget-layout.png',
		'4'	=> DCE_IMAGES_URL . '2-1-1-widget-layout.png',
		'5'	=> DCE_IMAGES_URL . '1-1-2-widget-layout.png',
		'6'	=> DCE_IMAGES_URL . '2-1-widget-layout.png',
		'7'	=> DCE_IMAGES_URL . '1-2-widget-layout.png',
		'8'	=> DCE_IMAGES_URL . '1-widget-layout.png',
		'9'	=> DCE_IMAGES_URL . '5-widget-layout.png',
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_footer_columns_default',
			'operator'	=> '==',
			'value'		=> 'custom',
			),
	),
) );


/* Custom Footer Columns Width Ratio */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_columns_width_ratio',
	'label'			=> __( 'Columns width ratio', 'divi-children-engine' ),
	'description'	=> __( 'Applies only to layouts with different column widths. Sets the ratio between the two widths. Default: 2 (wider column has double width).', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> 2,
	'priority'	=> 220,
	'choices'	=> array(
		'min'	=> 1.5,
		'max'	=> 2.5,
		'step'	=> .1,
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_footer_columns_default',
			'operator'	=> '==',
			'value'		=> 'custom',
			),
		array(
			'setting'	=> 'dce_footer_columns_layout',
			'operator'	=> '!=',
			'value'		=> '0',
			),
		array(
			'setting'	=> 'dce_footer_columns_layout',
			'operator'	=> '!=',
			'value'		=> '1',
			),
		array(
			'setting'	=> 'dce_footer_columns_layout',
			'operator'	=> '!=',
			'value'		=> '2',
			),
		array(
			'setting'	=> 'dce_footer_columns_layout',
			'operator'	=> '!=',
			'value'		=> '8',
			),
		array(
			'setting'	=> 'dce_footer_columns_layout',
			'operator'	=> '!=',
			'value'		=> '9',
			),			
	),
) );


/* Custom Footer Columns Separation */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_columns_separation',
	'label'			=> __( 'Columns separation (%)', 'divi-children-engine' ),
	'section'		=> 'dce_main_footer',
	'description'	=> __( 'Spacing between columns (percentage of total width). Default: 5%.', 'divi-children-engine' ),		
	'default'		=> 5,
	'priority'		=> 230,
	'choices'		=> array(
		'min' 		=> 1,
		'max' 		=> 15,
		'step'		=> 1,
	),
	'active_callback'		=> array(
		array(
			'setting'	=> 'dce_footer_columns_default',
			'operator'	=> '==',
			'value'		=> 'custom',
			),
		array(
			'setting'	=> 'dce_footer_columns_layout',
			'operator'	=> '!=',
			'value'		=> '8',
			),
	),
) );


/* GROUP TITLE: Paddings and margins */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mfpm',
	'label'		=> __( 'Paddings and margins:', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 300,
) );


/* Main Footer top padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_main_footer_toppadding',
	'label'		=> __( 'Main Footer top padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> 80,
	'priority'	=> 310,
	'choices'	=> array(
		'min' 	=> 0,
		'max' 	=> 100,
		'step'	=> 1,
	),	
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-widgets',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px',
		),
	)
) );


/* Footer widgets bottom margin */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_footer_widget_bottommargin',
	'label'		=> __( 'Footer widgets bottom margin (px)', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> 50,
	'priority'	=> 320,
	'choices'	=> array(
		'min' 	=> 0,
		'max' 	=> 100,
		'step'	=> 1,
	),	
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.footer-widget',
			'function'	=> 'css',
			'property'	=> 'margin-bottom',
			'units'		=> 'px !important;',
		),
	)
) );


/* Widget header bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_main_footer_title_padding',
	'label'		=> __( 'Widget header bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',	
	'default'	=> 10,
	'priority'	=> 330,
	'choices'	=> array(
		'min' 	=> 0,
		'max' 	=> 50,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.footer-widget .title',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
	)
) );


/* GROUP TITLE: Links */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mfl',
	'label'		=> __( 'Links:', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 400,
) );


/* Main Footer links hover color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_main_footer_hovercolor',
	'label'		=> __( 'Main Footer links hover color', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 410,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#main-footer .footer-widget li a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '#main-footer #footer-widgets .et_pb_widget li a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
) );


/* GROUP TITLE: Bullets */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mfb',
	'label'		=> __( 'Bullets:', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 500,
) );


/* Display bullets on Main Footer */
Kirki::add_field( 'dce', array(
	'type'		=> 'checkbox',
	'settings'	=> 'dce_main_footer_bullets',
	'label'		=> __( 'Display bullets on Main Footer', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> 1,
	'priority'	=> 510,
) );


/* Main Footer bullets style */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-image',
	'settings'	=> 'dce_main_footer_bulletstype',
	'label'		=> __( 'Main Footer bullets style:', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'priority'	=> 520,
	'default'	=> 'bullets',
	'choices'	=> array(
		'bullets'	=>	DCE_IMAGES_URL . 'default_bullet.png',
		'squares'	=>	DCE_IMAGES_URL . 'square_bullet.png',
		'arrows'	=>	DCE_IMAGES_URL . 'arrow_bullet.png',
		'line'		=>	DCE_IMAGES_URL . 'line_bullet.png',
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_main_footer_bullets',
			'operator'	=> '==',
			'value'		=> 1,
			),
	),
) );


/* Main Footer bullets color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_main_footer_bulletscolor',
	'label'		=> __( 'Main Footer bullets color', 'divi-children-engine' ),
	'section'	=> 'dce_main_footer',
	'default'	=> dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 530,
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_main_footer_bullets',
			'operator'	=> '==',
			'value'		=> 1,
			),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.footer-widget li:before',
			'function'	=> 'css',
			'property'	=> 'border-color',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '.footer-widget li:before',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '.footer-widget li',
			'function'	=> 'css',
			'property'	=> 'border-color',
			'suffix'	=> ' !important;',
		),
	),
) );


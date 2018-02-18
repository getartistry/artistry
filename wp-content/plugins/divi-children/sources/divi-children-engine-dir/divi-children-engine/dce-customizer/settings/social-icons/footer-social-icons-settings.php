<?php

/**
 * Customizer controls: Footer Social Icons section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Footer Social Icons color */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_footer_social_icons_servicecolor',
	'label'			=> __( 'Footer Social Icons color:', 'divi-children-engine' ),
	'description'	=> __( 'Choose Divi default (all social icons displayed with the same color) or use the original icon colors from each service.', 'divi-children-engine' ),
	'section'		=> 'dce_footer_social_icons',
	'default'		=> '0',
	'priority'		=> 100,
	'choices'		=> array(
		'0'	=> __( 'Use Divi default', 'divi-children-engine' ),
		'1'	=> __( 'Use original icon colors', 'divi-children-engine' ),
	),
) );


/* Use original social icon color on hover */
Kirki::add_field( 'dce', array(
	'type'			=> 'checkbox',
	'settings'		=> 'dce_footer_social_icons_hoverservicecolor',
	'label'			=> __( 'Use original icon colors on hover', 'divi-children-engine' ),
	'section'		=> 'dce_footer_social_icons',
	'default'		=> '0',
	'priority'		=> 200,
	'active_callback'  => array(
		array(
			'setting'  => 'dce_footer_social_icons_servicecolor',
			'operator' => '==',
			'value'    => '0',
		),
	),	
) );


/* Social icons hover color */
Kirki::add_field( 'dce', array(
	'type'			=> 'color',
	'settings'		=> 'dce_footer_social_icons_hovercolor',
	'label'			=> __( 'Social Icons hover color', 'divi-children-engine' ),
	'section'		=> 'dce_footer_social_icons',
	'default'		=> dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'		=> 300,
	'active_callback'  => array(
		array(
			'setting'  => 'dce_footer_social_icons_servicecolor',
			'operator' => '==',
			'value'    => '0',
		),
		array(
			'setting'  => 'dce_footer_social_icons_hoverservicecolor',
			'operator' => '==',
			'value'    => '0',
		),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> ' #main-footer #footer-bottom ul.et-social-icons li a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),	
) );


/* Social icons separation */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_social_icons_margin',
	'label'			=> __( 'Social Icons separation (px)', 'divi-children-engine' ),
	'section'		=> 'dce_footer_social_icons',
	'default'		=> 18,
	'priority'		=> 400,
	'choices'	=> array(
		'min'	=> 5,
		'max'	=> 200,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> ' #footer-bottom ul.et-social-icons li',
			'function'	=> 'css',
			'property'	=> 'margin-left',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),	
) );

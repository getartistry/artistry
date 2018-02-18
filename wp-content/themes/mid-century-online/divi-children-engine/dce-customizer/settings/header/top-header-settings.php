<?php

/**
 * Customizer controls: Top Header section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* GROUP TITLE: Top Header Layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_thl',
	'label'		=> __( 'Top Header Layout:', 'divi-children-engine' ),
	'section'	=> 'dce_top_header',
	'priority'	=> 10,
) );


/* Center or Reverse Layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_top_header_layout',
	'label'		=> __( 'Center or Reverse Layout', 'divi-children-engine' ),
	'tooltip' 	=> __( 'Center the layout or modify the default display order for the contact info elements (phone and email info at the left and social icons at the right) to just the opposite order.', 'divi-children-engine' ),
	'section'	=> 'dce_top_header',
	'default'	=> 'default',
	'priority'	=> 100,
	'choices'	=> array(
		'default'	=>	__( 'Default', 'divi-children-engine' ),
		'centered'	=>	__( 'Centered', 'divi-children-engine' ),
		'reversed'	=>	__( 'Reversed Info', 'divi-children-engine' ),
	),
) );


/* Right align social icons */
Kirki::add_field( 'dce', array(
	'type'			=> 'checkbox',
	'settings'		=> 'dce_top_header_social_right',
	'label'			=> __( 'Align social icons to the top right', 'divi-children-engine' ),
	'description'	=> __( '(If menu is used, forces it to jump a line down)', 'divi-children-engine' ),
	'section'		=> 'dce_top_header',
	'default'		=> '',
	'priority'		=> 110,
	'active_callback'  => array(
		array(
			'setting'	=> 'dce_top_header_layout',
			'operator'	=> '==',
			'value'		=> 'default',
		),
	),
) );


/* Right align phone and email */
Kirki::add_field( 'dce', array(
	'type'			=> 'checkbox',
	'settings'		=> 'dce_top_header_contact_right',
	'label'			=> __( 'Align contact info to the top right', 'divi-children-engine' ),
	'description'	=> __( '(If menu is used, forces it to jump a line down)', 'divi-children-engine' ),
	'section'		=> 'dce_top_header',
	'default'		=> '',
	'priority'		=> 120,
	'active_callback'  => array(
		array(
			'setting'	=> 'dce_top_header_layout',
			'operator'	=> '==',
			'value'		=> 'reversed',
		),
	),
) );


/* GROUP TITLE: Top Header Menu */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_thm',
	'label'		=> __( 'Top Header Menu:', 'divi-children-engine' ),
	'section'	=> 'dce_top_header',
	'priority'	=> 200,
) );


/* Top Header Menu Align */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_top_header_menu_align',
	'label'			=> __( 'Top Header Menu alignment', 'divi-children-engine' ),
	'description'	=> __( 'This setting is most useful when the Top Header is organized in two lines, i.e. the menu is in a line below the contact info and the social icons.', 'divi-children-engine' ),
	'section'		=> 'dce_top_header',
	'default'		=> 'right',
	'priority'		=> 210,
	'choices'	=> array(
		'left'		=>	__( 'Left', 'divi-children-engine' ),
		'center'	=>	__( 'Center', 'divi-children-engine' ),
		'right'		=>	__( 'Right', 'divi-children-engine' ),
	),
) );


/* Menu items spacing */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_top_header_menu_spacing',
	'label'			=> __( 'Spacing between menu items (px)', 'divi-children-engine' ),
	'section'		=> 'dce_top_header',
	'default'		=> 15,
	'priority'		=> 220,
	'choices'	=> array(
		'min'	=> 2,
		'max'	=> 200,
		'step'	=> 1,
	),
) );


/* Menu left margin */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_top_header_menu_leftmargin',
	'label'			=> __( 'Menu left margin (px)', 'divi-children-engine' ),
	'section'		=> 'dce_top_header',
	'default'		=> 25,
	'priority'		=> 230,
	'choices'	=> array(
		'min'	=> 2,
		'max'	=> 200,
		'step'	=> 1,
	),
	'active_callback'  => array(
		array(
			'setting'	=> 'dce_top_header_layout',
			'operator'	=> '!=',
			'value'		=> 'centered',
		),
		array(
			'setting'	=> 'dce_top_header_social_right',
			'operator'	=> '==',
			'value'		=> '',
		),
		array(
			'setting'	=> 'dce_top_header_contact_right',
			'operator'	=> '==',
			'value'		=> '',
		),
		array(
			'setting'	=> 'dce_top_header_menu_align',
			'operator'	=> '==',
			'value'		=> 'left',
		),
	),
) );

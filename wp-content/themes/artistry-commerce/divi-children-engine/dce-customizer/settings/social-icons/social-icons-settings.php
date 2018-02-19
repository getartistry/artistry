<?php

/**
 * Customizer controls: Social Icons section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


$defaults = dce_get_social_icons_defaults();


/* Customize Social Icons */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_customize_social_icons',
	'label'			=> __( 'Customize Social Icons', 'divi-children-engine' ),
	'section'		=> 'dce_social_icons',
	'description'	=> __( 'Choose whether to customize the social icons of your child theme by means of the "Social Icons EZ-Builder" or to use the settings from Divi Theme Options.', 'divi-children-engine' ),
	'tooltip'		=> __( 'Default values will be automatically imported from your Divi Theme Options where applicable, until you save your settings for the first time.', 'divi-children-engine' ),
	'default'		=> '0',
	'priority'		=> 10,
	'choices'		=> array(
		'0'	=>	__( 'Use Divi options', 'divi-children-engine' ),
		'1'	=>	__( 'Customize social icons', 'divi-children-engine' ),
	),
) );


/* Social Icons EZ-Builder */
Kirki::add_field( 'dce', array(
	'type'			=> 'repeater',
	'settings'		=> 'dce_social_icons',
	'label'			=> __( 'Social Icons EZ-Builder', 'divi-children-engine' ),
	'description'	=> __( 'Add or remove social icons and drag them to reorder them as you like.', 'divi-children-engine' ),
	'section'		=> 'dce_social_icons',
	'priority'		=> 100,
	'row_label'		=> array(               
		'type'	=> 'field',
		'value'	=> __('Social Icon', 'divi-children-engine' ),
		'field'	=> 'network',
	),
	'default'		=> $defaults,
	'fields'		=> array(
		'network'	=> array(
			'type'			=> 'select',
			'label'			=> __( 'Social Network', 'divi-children-engine' ),
			'description'	=> __( 'Choose the social network', 'divi-children-engine' ),
			'default'		=> '',
			'choices'		=> array(
				'facebook'	=> __( 'Facebook', 'divi-children-engine' ),
				'twitter'	=> __( 'Twitter', 'divi-children-engine' ),
				'google+'	=> __( 'Google+', 'divi-children-engine' ),
				'pinterest'	=> __( 'Pinterest', 'divi-children-engine' ),
				'linkedin'	=> __( 'Linkedin', 'divi-children-engine' ),
				'tumblr'	=> __( 'Tumblr', 'divi-children-engine' ),
				'instagram'	=> __( 'Instagram', 'divi-children-engine' ),
				'skype'		=> __( 'Skype', 'divi-children-engine' ),
				'flikr'		=> __( 'Flikr', 'divi-children-engine' ),
				'youtube'	=> __( 'Youtube', 'divi-children-engine' ),
				'vimeo'		=> __( 'Vimeo', 'divi-children-engine' ),
				'myspace'	=> __( 'Myspace', 'divi-children-engine' ),
				'dribbble'	=> __( 'Dribbble', 'divi-children-engine' ),
				'rss'		=> __( 'RSS', 'divi-children-engine' ),
			),
		),
		'url'		=> array(
			'type'			=> 'text',
			'label'			=> __( 'Account URL', 'divi-children-engine' ),
			'description'	=> __( 'The URL for this social network link', 'divi-children-engine' ),
			'default'		=> '#',
		),
		'blank'		=> array(
			'type'			=> 'checkbox',
			'label'			=> __( 'Open link on a new browser tab', 'divi-children-engine' ),
			'default'		=> '1',
		),
	),
	'active_callback'  => array(
		array(
			'setting'  => 'dce_customize_social_icons',
			'operator' => '==',
			'value'    => '1',
		),
	),
) );









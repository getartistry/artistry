<?php

/**
 * Customizer controls: Single Post Layout section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Customize Post Layout */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_post_layout',
	'label'			=> __( 'Customize Post Layout:', 'divi-children-engine' ),
	'tooltip' 		=> __( 'Choose whether to customize the composition of your child theme´s posts by means of the "Post Layout EZ-Builder" or to just use the Divi default post layout.', 'divi-children-engine' ),
	'section'		=> 'dce_post_layout',
	'default'		=> 'default',
	'priority'		=> 10,
	'choices'		=> array(
		'default'	=> __( 'Use Divi default', 'divi-children-engine' ),
		'custom'	=> __( 'Custom Post Layout', 'divi-children-engine' ),
	),
) );


$customize_post_layout_callback = array(
	array(
		'setting'  => 'dce_post_layout',
		'operator' => '==',
		'value'    => 'custom',
	),
);


/* GROUP TITLE: Custom Post Layout */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pl',
	'label'		=> __( 'Custom Post Layout:', 'divi-children-engine' ),
	'section'	=> 'dce_post_layout',
	'priority'	=> 100,
	'active_callback' => $customize_post_layout_callback,
) );


/* Post Layout EZ-Builder */
Kirki::add_field( 'dce', array(
	'type'			=> 'repeater',
	'settings'		=> 'dce_post_layout_elements',
	'label'			=> __( 'Post Layout EZ-Builder', 'divi-children-engine' ),
	'description'	=> __( 'Add or remove post elements and drag them to reorder them as you like.', 'divi-children-engine' ),
	'section'		=> 'dce_post_layout',
	'priority'		=> 110,
	'row_label'		=> array(               
		'type'	=> 'field',
		'value'	=> __('Post Element', 'divi-children-engine' ),
		'field'	=> 'element',
	),
	'default'	=> array(
		array(
			'element'		=> 'title',
			'field'			=> '',
			'field_value'	=> '',
		),
		array(
			'element'		=> 'meta',
			'field'			=> '',
			'field_value'	=> '',
		),
		array(
			'element'		=> 'featured',
			'field'			=> '',
			'field_value'	=> '',
		),
		array(
			'element'		=> 'content',
			'field'			=> '',
			'field_value'	=> '',
		),
		array(
			'element'		=> 'comments',
			'field'			=> '',
			'field_value'	=> '',
		),
	),
	'fields'		=> array(
		'element'	=> array(
			'type'			=> 'select',
			'label'			=> __( 'Post Element', 'divi-children-engine' ),
			'description'	=> __( '(Checked options do not need any additional field)', 'divi-children-engine' ),
			'default'		=> 'divi_layout',
			'choices'		=> array(
				'divi_layout'	=> __( 'Divi Layout', 'divi-children-engine' ),
				'hero_begins'	=> __( '┏ Hero Image section top ┓', 'divi-children-engine' ),
				'hero_ends'		=> __( '┗ Hero Image section end ┛ ☑', 'divi-children-engine' ),
				'image_begins'	=> __( '┏ Back Image section top ┓', 'divi-children-engine' ),
				'image_ends'	=> __( '┗ Back Image section end ┛ ☑', 'divi-children-engine' ),
				'title'			=> __( 'Post Title ☑', 'divi-children-engine' ),
				'meta'			=> __( 'Post Meta ☑', 'divi-children-engine' ),
				'featured'		=> __( 'Featured Image ☑', 'divi-children-engine' ),
				'content'		=> __( 'Post Content ☑', 'divi-children-engine' ),
				'comments'		=> __( 'Post Comments ☑', 'divi-children-engine' ),
				'spacer'		=> __( 'Spacer ☑', 'divi-children-engine' ),
				'shortcode'		=> __( 'Shortcode', 'divi-children-engine' ),
				'code'			=> __( 'HTML code', 'divi-children-engine' ),
				'code-fw'		=> __( 'HTML code (fullwidth)', 'divi-children-engine' ),
				'468x60'		=> __( '468x60 Banner Ad ☑', 'divi-children-engine' ),
			),
		),
		'field'	=> array(
			'type'			=> 'select',
			'label'			=> __( 'Additional field type', 'divi-children-engine' ),
			'description'	=> __( '(Checked options do not need any field content or value)', 'divi-children-engine' ),
			'default'		=> '',
			'choices'		=> array(
				''					=> __( 'None ☑', 'divi-children-engine' ),
				'featured'			=> __( 'Featured Image ☑', 'divi-children-engine' ),
				'featured-parallax'	=> __( 'Featured Image - Parallax ☑', 'divi-children-engine' ),
				'url'				=> __( 'Image URL', 'divi-children-engine' ),
				'url-parallax'		=> __( 'Image URL - Parallax', 'divi-children-engine' ),
				'layout_name'		=> __( 'Divi Layout Name', 'divi-children-engine' ),
				'shortcode'			=> __( 'Shortcode', 'divi-children-engine' ),
				'code'				=> __( 'HTML code', 'divi-children-engine' ),
				'transparent'		=> __( 'Transparent background ☑', 'divi-children-engine' ),
				// 'content'		=> __( 'Content', 'divi-children-engine' ),
			),
		),
		'field_value'	=> array(
			'type'			=> 'textarea',
			'label'			=> esc_attr__( 'Additional field content', 'divi-children-engine' ),
			'default'		=> '',
		),
	),
	'active_callback'	=> $customize_post_layout_callback,
) );


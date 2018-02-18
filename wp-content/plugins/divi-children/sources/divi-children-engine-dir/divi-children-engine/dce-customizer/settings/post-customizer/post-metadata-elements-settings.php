<?php

/**
 * Customizer controls: Single Post Meta Data Elements section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Customize Post Meta elements */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_customize_postmeta',
	'label'			=> __( 'Customize Post Meta Elements:', 'divi-children-engine' ),
	'tooltip' 		=> __( 'Choose whether to customize the composition of your child theme´s post meta by means of the "Post Meta EZ-Builder" or to just use the Divi default post meta.', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata_elements',
	'default'		=> '1',
	'priority'		=> 10,
	'choices'		=> array(
		'0'	=> __( 'Use Divi default', 'divi-children-engine' ),
		'1'	=> __( 'Custom Post Meta', 'divi-children-engine' ),
	),
) );


$customize_postmeta_callback = array(
	array(
		'setting'  => 'dce_customize_postmeta',
		'operator' => '==',
		'value'    => '1',
	),
);


/* GROUP TITLE: Post Meta Elements */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pme',
	'label'		=> __( 'Post Meta Elements:', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata_elements',
	'priority'	=> 100,
	'active_callback' => $customize_postmeta_callback,
) );


/* Meta Elements EZ-Builder */
Kirki::add_field( 'dce', array(
	'type'			=> 'repeater',
	'settings'		=> 'dce_post_postmeta_elements',
	'label'			=> __( 'Post Meta EZ-Builder', 'divi-children-engine' ),
	'description'	=> __( 'Add or remove meta elements and drag them to reorder them as you like.', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata_elements',
	'priority'		=> 110,
	'row_label'		=> array(               
		'type'	=> 'field',
		'value'	=> __('Meta Element', 'divi-children-engine' ),
		'field'	=> 'element',
	),
	'default'	=> array(
		array(
			'element' => 'author',
			'text' => __( 'By:', 'divi-children-engine' ),
		),
		array(
			'element' => 'date',
			'text' => __( 'Published on:', 'divi-children-engine' ),
		),
		array(
			'element' => 'categories',
			'text' => __( 'Categories:', 'divi-children-engine' ),
		),
		array(
			'element' => 'comments',
			'text' => '',
		),
	),
	'fields'		=> array(
		'element'	=> array(
			'type'			=> 'select',
			'label'			=> __( 'Meta Element', 'divi-children-engine' ),
			'default'		=> '',
			'choices'		=> array(
				'author'		=> __( 'Author', 'divi-children-engine' ),
				'date'			=> __( 'Date', 'divi-children-engine' ),
				'mod_date'		=> __( 'Date - Last Modified', 'divi-children-engine' ),
				'categories'	=> __( 'Categories', 'divi-children-engine' ),
				'comments'		=> __( 'Comments', 'divi-children-engine' ),
				'tags'			=> __( 'Tags', 'divi-children-engine' ),
			),
		),
		'text' => array(
			'type'        => 'text',
			'label'       => esc_attr__( 'Text before element', 'divi-children-engine' ),
			'description' => esc_attr__( '(displayed if no icons are used)', 'divi-children-engine' ),
			'default'     => '',
		),
	),
	'active_callback'	=> $customize_postmeta_callback,
) );


/* GROUP TITLE: Tags */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pmet',
	'label'		=> __( 'Tags:', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata_elements',
	'priority'	=> 200,
) );


/* Display Tags below content */
Kirki::add_field( 'dce', array(
	'type'		=> 'checkbox',
	'settings'	=> 'dce_post_tags_after_content',
	'label'		=> __( 'Display Tags below content', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata_elements',
	'default'	=> '',
	'priority'	=> 210,
) );


/* Text before tags */
Kirki::add_field( 'dce', array(
	'type'		=> 'text',
	'settings'	=> 'dce_post_tags_after_content_text',
	'label'		=> __( 'Text before tags:', 'divi-children-engine' ),
	'description'	=> __( '(Displayed only if no icons are used)', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata_elements',
	'default'	=> __( 'Tagged:', 'divi-children-engine' ),
	'priority'	=> 220,
	'transport'	=> 'postMessage',
	'js_vars'   => array(
		array(
			'element'  => '.dce-post-tags em',
			'function' => 'html',
		),
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_post_tags_after_content',
			'operator'	=> '==',
			'value'		=> 1,
			),
	),
) );


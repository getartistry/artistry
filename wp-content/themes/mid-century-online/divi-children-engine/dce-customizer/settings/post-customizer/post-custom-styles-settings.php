<?php

/**
 * Customizer controls: Custom Post Styles section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );

$customize_post_layout_callback = array(
	array(
		'setting'  => 'dce_post_layout',
		'operator' => '==',
		'value'    => 'custom',
	),
);

if ( 'default' == get_theme_mod( 'dce_post_layout', 'default' ) ) {

	/* INFO BOX: Reload */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'ib_pcsr',
		'label'		=> __( 'If you have chosen to use a custom layout you may need to reload the Customizer.', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'priority'	=> 1,
		'active_callback' => $customize_post_layout_callback,
	) );

	return;

}

$post_layout_elements_defaults = array(
	array(
		'element' 		=> 'title',
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
		'element' 		=> 'comments',
		'field'			=> '',
		'field_value'	=> '',
	),
);
$post_layout_elements = get_theme_mod( 'dce_post_layout_elements', $post_layout_elements_defaults );

$active_elements = array();
foreach ( $post_layout_elements as $element_array ) {
	$active_elements[] = $element_array['element'];
}

if ( in_array( 'title', $active_elements ) ) {
	$has_title = true;
}

if ( in_array( 'meta', $active_elements ) ) {
	$has_meta = true;
}

if ( in_array( 'hero_begins', $active_elements ) AND in_array( 'hero_ends', $active_elements ) ) {
	$has_hero = true;
}

if ( in_array( 'image_begins', $active_elements ) AND in_array( 'image_ends', $active_elements ) ) {
	$has_backimg = true;
}

if ( in_array( 'spacer', $active_elements ) ) {
	$has_spacer = true;
}


/* INFO BOX: Hit Save & Publish */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'ib_pcshs',
	'label'		=> __( 'Hit the "Save & Publish" button after you´ve built your custom layout and reload the Customizer to make sure that all the applicable settings appear in this section.', 'divi-children-engine' ),
	'section'	=> 'dce_post_custom_styles',
	'priority'	=> 10,
	'active_callback' => $customize_post_layout_callback,
) );


if ( $has_hero ) {

	/* GROUP TITLE: Hero Image Section  */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'gt_pcshis',
		'label'		=> __( 'Hero Image Section:', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'priority'	=> 100,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Hero section Top padding */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_hero_top_padding',
		'label'		=> __( 'Section top padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 100,
		'priority'	=> 110,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 250,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_hero_image',
				'function'	=> 'css',
				'property'	=> 'padding-top',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Hero section Bottom padding */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_hero_bottom_padding',
		'label'		=> __( 'Section bottom padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 100,
		'priority'	=> 120,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 250,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_hero_image',
				'function'	=> 'css',
				'property'	=> 'padding-bottom',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Add Overlay */
	Kirki::add_field( 'dce', array(
		'type'		=> 'checkbox',
		'settings'	=> 'dce_post_custom_hero_add_overlay',
		'label'		=> __( 'Add overlay', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> '0',
		'priority'	=> 130,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Overlay rgba color */
	Kirki::add_field( 'dce', array(
		'type'		=> 'color',
		'settings'	=> 'dce_post_custom_hero_overlay_color',
		'label'		=> __( 'Overlay rgba color', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> 'rgba(0,0,0,0.4)',
		'priority'	=> 140,
		'alpha'		=> true,
		'active_callback'  => array(
			array(
				'setting'  => 'dce_post_custom_hero_add_overlay',
				'operator' => '==',
				'value'    => '1',
			),
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_hero_overlay:before',
				'function'	=> 'css',
				'property'	=> 'background-color',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'dce_post_layout',
				'operator' => '==',
				'value'    => 'custom',
			),
			array(
				'setting'  => 'dce_post_custom_hero_add_overlay',
				'operator' => '==',
				'value'    => '1',
			),
		),
	) );

}


if ( $has_backimg ) {

	/* GROUP TITLE: Background Image Section  */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'gt_pcsbis',
		'label'		=> __( 'Background Image Section:', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'priority'	=> 200,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Background Image section Top padding */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_backimg_top_padding',
		'label'		=> __( 'Section top padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 50,
		'priority'	=> 210,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 200,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_backimg',
				'function'	=> 'css',
				'property'	=> 'padding-top',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Background Image section Bottom padding */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_backimg_bottom_padding',
		'label'		=> __( 'Section bottom padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 50,
		'priority'	=> 220,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 200,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_backimg',
				'function'	=> 'css',
				'property'	=> 'padding-bottom',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Background Image Add Overlay */
	Kirki::add_field( 'dce', array(
		'type'		=> 'checkbox',
		'settings'	=> 'dce_post_custom_backimg_add_overlay',
		'label'		=> __( 'Add overlay', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> '0',
		'priority'	=> 230,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Background Image Overlay rgba color */
	Kirki::add_field( 'dce', array(
		'type'		=> 'color',
		'settings'	=> 'dce_post_custom_backimg_overlay_color',
		'label'		=> __( 'Overlay rgba color', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> 'rgba(0,0,0,0.4)',
		'priority'	=> 240,
		'alpha'		=> true,
		'active_callback'  => array(
			array(
				'setting'  => 'dce_post_custom_backimg_add_overlay',
				'operator' => '==',
				'value'    => '1',
			),
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_backimg_overlay:before',
				'function'	=> 'css',
				'property'	=> 'background-color',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => array(
			array(
				'setting'  => 'dce_post_layout',
				'operator' => '==',
				'value'    => 'custom',
			),
			array(
				'setting'  => 'dce_post_custom_backimg_add_overlay',
				'operator' => '==',
				'value'    => '1',
			),
		),
	) );

}


if ( $has_title ) {

	/* GROUP TITLE: Post Title */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'gt_pcspt',
		'label'		=> __( 'Post Title:', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'priority'	=> 300,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Title color */
	Kirki::add_field( 'dce', array(
		'type'		=> 'color',
		'settings'	=> 'dce_post_custom_title_color',
		'label'		=> __( 'Title color', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> '#333',
		'priority'	=> 310,
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'color',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Title Top margin */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_title_top_margin',
		'label'		=> __( 'Title top margin (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 0,
		'priority'	=> 320,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 200,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'margin-top',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Title Bottom margin */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_title_bottom_margin',
		'label'		=> __( 'Title bottom margin (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 0,
		'priority'	=> 330,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 200,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'margin-bottom',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Title horizontal margin (%) */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_title_horizontal_margin',
		'label'		=> __( 'Title horizontal margin (%)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> 0,
		'priority'	=> 340,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 30,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'margin-left',
				'units'		=> '%',
				'suffix'	=> ' !important;',
			),
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'margin-right',
				'units'		=> '%',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Title alignment */
	Kirki::add_field( 'dce', array(
		'type'			=> 'radio-buttonset',
		'settings'		=> 'dce_post_custom_title_align',
		'label'			=> __( 'Title alignment:', 'divi-children-engine' ),
		'section'		=> 'dce_post_custom_styles',
		'default'		=> 'left',
		'priority'		=> 350,
		'choices'		=> array(
			'left'		=> __( 'Left', 'divi-children-engine' ),
			'center'	=> __( 'Center', 'divi-children-engine' ),
			'right'		=> __( 'Right', 'divi-children-engine' ),
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'text-align',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Add Text background */
	Kirki::add_field( 'dce', array(
		'type'		=> 'radio-buttonset',
		'settings'	=> 'dce_post_custom_title_add_background',
		'label'		=> __( 'Add a text background to the title', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> '0',
		'priority'	=> 360,
		'choices'	=> array(
			'0'	=>	__( 'Default Title', 'divi-children-engine' ),
			'1'	=>	__( 'Add Background', 'divi-children-engine' ),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	$title_background_callback = array (
		array(
			'setting'	=> 'dce_post_layout',
			'operator'	=> '==',
			'value'		=> 'custom',
		),
		array(
			'setting'	=> 'dce_post_custom_title_add_background',
			'operator'	=> '==',
			'value'		=> '1',
		),
	);

	/* Title background color */
	Kirki::add_field( 'dce', array(
		'type'		=> 'color',
		'settings'	=> 'dce_post_custom_title_background_color',
		'label'		=> __( 'Title background color', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> 'rgba(0,0,0,0.5)',
		'priority'	=> 370,
		'alpha'		=> true,
		'active_callback'  => array(
			array(
				'setting'  => 'dce_post_custom_title_add_background',
				'operator' => '==',
				'value'    => '1',
			),
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'background-color',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $title_background_callback,
	) );

	/* Post Title vertical padding */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_title_vertical_padding',
		'label'		=> __( 'Title vertical padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 10,
		'priority'	=> 380,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 100,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'padding-top',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'padding-bottom',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $title_background_callback,
	) );

	/* Post Title horizontal padding */ 
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_title_horizontal_padding',
		'label'		=> __( 'Title horizontal padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 10,
		'priority'	=> 390,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 100,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'padding-left',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
			array(
				'element'	=> 'h1.dce-post-title',
				'function'	=> 'css',
				'property'	=> 'padding-right',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $title_background_callback,
	) );

}


if ( $has_meta ) {

	/* GROUP TITLE: Post Meta */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'gt_pcspm',
		'label'		=> __( 'Post Meta:', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'priority'	=> 400,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Meta Top margin */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_meta_top_margin',
		'label'		=> __( 'Meta top margin (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 0,
		'priority'	=> 410,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 100,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'margin-top',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Meta Bottom margin */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_meta_bottom_margin',
		'label'		=> __( 'Meta bottom margin (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 0,
		'priority'	=> 420,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 100,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'margin-bottom',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Meta horizontal margin (%) */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_meta_horizontal_margin',
		'label'		=> __( 'Meta horizontal margin (%)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 0,
		'priority'	=> 430,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 30,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'margin-left',
				'units'		=> '%',
				'suffix'	=> ' !important;',
			),
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'margin-right',
				'units'		=> '%',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Post Meta alignment */
	Kirki::add_field( 'dce', array(
		'type'			=> 'radio-buttonset',
		'settings'		=> 'dce_post_custom_meta_align',
		'label'			=> __( 'Meta alignment:', 'divi-children-engine' ),
		'section'		=> 'dce_post_custom_styles',
		'default'		=> 'left',
		'priority'		=> 440,
		'choices'		=> array(
			'left'		=> __( 'Left', 'divi-children-engine' ),
			'center'	=> __( 'Center', 'divi-children-engine' ),
			'right'		=> __( 'Right', 'divi-children-engine' ),
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'text-align',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Add Meta background */
	Kirki::add_field( 'dce', array(
		'type'		=> 'radio-buttonset',
		'settings'	=> 'dce_post_custom_meta_add_background',
		'label'		=> __( 'Add background to the meta line', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> '0',
		'priority'	=> 450,
		'choices'	=> array(
			'0'	=>	__( 'Default Meta', 'divi-children-engine' ),
			'1'	=>	__( 'Add Background', 'divi-children-engine' ),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

	$meta_background_callback = array (
		array(
			'setting'	=> 'dce_post_layout',
			'operator'	=> '==',
			'value'		=> 'custom',
		),
		array(
			'setting'	=> 'dce_post_custom_meta_add_background',
			'operator'	=> '==',
			'value'		=> '1',
		),
	);

	/* Meta background color */
	Kirki::add_field( 'dce', array(
		'type'		=> 'color',
		'settings'	=> 'dce_post_custom_meta_background_color',
		'label'		=> __( 'Meta background color', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'default'	=> 'rgba(0,0,0,0.5)',
		'priority'	=> 460,
		'alpha'		=> true,
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'background-color',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $meta_background_callback,
	) );

	/* Post Meta vertical padding */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_meta_vertical_padding',
		'label'		=> __( 'Meta vertical padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 10,
		'priority'	=> 470,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 100,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'padding-top',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'padding-bottom',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $meta_background_callback,
	) );

	/* Post Meta horizontal padding */ 
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_meta_horizontal_padding',
		'label'		=> __( 'Meta horizontal padding (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 10,
		'priority'	=> 480,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 100,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'padding-left',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
			array(
				'element'	=> '#dce-custom-post .post-meta',
				'function'	=> 'css',
				'property'	=> 'padding-right',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $meta_background_callback,
	) );

}


/* GROUP TITLE: Content  */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pcsc',
	'label'		=> __( 'Content:', 'divi-children-engine' ),
	'section'	=> 'dce_post_custom_styles',
	'priority'	=> 500,
	'active_callback' => $customize_post_layout_callback,
) );

/* Content text size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_post_custom_content_text_size',
	'label'		=> __( 'Content text size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_post_custom_styles',		
	'default'	=> dce_get_divi_option( 'body_font_size', 14 ),
	'priority'	=> 510,
	'choices'	=> array(
		'min' 	=> 0,
		'max' 	=> 30,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#dce-custom-post',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback' => $customize_post_layout_callback,
) );

/* Content line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_post_custom_content_line_height',
	'label'		=> __( 'Content Content line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_post_custom_styles',		
	'default'	=> dce_get_divi_option( 'body_font_height', 1.7 ),
	'priority'	=> 520,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#dce-custom-post',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
	),
	'active_callback' => $customize_post_layout_callback,
) );


if ( $has_spacer ) {

	/* GROUP TITLE: Spacer  */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'gt_pcss',
		'label'		=> __( 'Spacer:', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',
		'priority'	=> 600,
		'active_callback' => $customize_post_layout_callback,
	) );

	/* Spacer Top margin */
	Kirki::add_field( 'dce', array(
		'type'		=> 'slider',
		'settings'	=> 'dce_post_custom_spacer_height',
		'label'		=> __( 'Spacer height (px)', 'divi-children-engine' ),
		'section'	=> 'dce_post_custom_styles',		
		'default'	=> 50,
		'priority'	=> 610,
		'choices'	=> array(
			'min' 	=> 0,
			'max' 	=> 200,
			'step'	=> 1,
		),
		'transport'	=> 'postMessage',
		'js_vars'	=> array(
			array(
				'element'	=> '.dce_spacer',
				'function'	=> 'css',
				'property'	=> 'height',
				'units'		=> 'px',
				'suffix'	=> ' !important;',
			),
		),
		'active_callback' => $customize_post_layout_callback,
	) );

}


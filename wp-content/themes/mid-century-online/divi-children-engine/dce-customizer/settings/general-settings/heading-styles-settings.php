<?php

/**
 * Customizer controls: Heading Styles section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


$default_h1_size = dce_get_divi_option( 'body_header_size', 30 );
if ( 30 != $default_h1_size ) {
		$default_h2_size = round( $default_h1_size * 0.86 );
		$default_h3_size = round( $default_h1_size * 0.73 );
		$default_h4_size = round( $default_h1_size * 0.60 );
		$default_h5_size = round( $default_h1_size * 0.53 );
		$default_h6_size = round( $default_h1_size * 0.47 );
	} else {
		$default_h2_size = 26;
		$default_h3_size = 22;
		$default_h4_size = 18;
		$default_h5_size = 16;
		$default_h6_size = 14;	
}

$default_line_height = dce_get_divi_option( 'body_header_height', 1 );


/* Customize h1 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_heading_h1_customize',
	'label'		=> __( 'Customize h1 headings', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '0',
	'priority'	=> 10,
) );

$customize_h1_callback = array(
	array(
		'setting'	=> 'dce_heading_h1_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* Customize h2 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_heading_h2_customize',
	'label'		=> __( 'Customize h2 headings', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '0',
	'priority'	=> 20,
) );

$customize_h2_callback = array(
	array(
		'setting'	=> 'dce_heading_h2_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* Customize h3 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_heading_h3_customize',
	'label'		=> __( 'Customize h3 headings', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '0',
	'priority'	=> 30,
) );

$customize_h3_callback = array(
	array(
		'setting'	=> 'dce_heading_h3_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* Customize h4 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_heading_h4_customize',
	'label'		=> __( 'Customize h4 headings', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '0',
	'priority'	=> 40,
) );

$customize_h4_callback = array(
	array(
		'setting'	=> 'dce_heading_h4_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* Customize h5 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_heading_h5_customize',
	'label'		=> __( 'Customize h5 headings', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '0',
	'priority'	=> 50,
) );

$customize_h5_callback = array(
	array(
		'setting'	=> 'dce_heading_h5_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* Customize h6 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'toggle',
	'settings'	=> 'dce_heading_h6_customize',
	'label'		=> __( 'Customize h6 headings', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '0',
	'priority'	=> 60,
) );

$customize_h6_callback = array(
	array(
		'setting'	=> 'dce_heading_h6_customize',
		'operator'	=> '==',
		'value'		=> '1',
	),
);


/* GROUP TITLE: h1 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_hsh1',
	'label'		=> __( 'h1 headings:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'priority'	=> 100,
	'active_callback' => $customize_h1_callback,
) );


/* h1 font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h1_font_size',
	'label'		=> __( 'h1 font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_h1_size,
	'priority'	=> 110,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback' => $customize_h1_callback,
) );


/* Change slider titles accordingly */
Kirki::add_field( 'dce', array(
	'type'		=> 'checkbox',
	'settings'	=> 'dce_heading_h1_slider_title',
	'label'		=> __( 'Change slider titles accordingly', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> '1',
	'priority'	=> 120,
	'active_callback' => $customize_h1_callback,
) );


/* h1 line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h1_line_height',
	'label'		=> __( 'h1 line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_line_height,
	'priority'	=> 130,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'active_callback' => $customize_h1_callback,
) );


/* h1 bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h1_bottom_padding',
	'label'		=> __( 'h1 bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 10,
	'priority'	=> 150,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'active_callback' => $customize_h1_callback,
) );


/* Disable above h1 settings for */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio',
	'settings'	=> 'dce_heading_h1_disable',
	'label'		=> __( 'Disable above h1 settings for:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 'none',
	'priority'	=> 195,
	'choices'	=> array(
		'none'		=> esc_attr__( 'None', 'divi-children-engine' ),
		'tablet'	=> esc_attr__( 'Tablet and Phone', 'divi-children-engine' ),
		'phone'		=> esc_attr__( 'Phone', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h1_callback,
) );


/* GROUP TITLE: h2 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_hsh2',
	'label'		=> __( 'h2 headings:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'priority'	=> 200,
	'active_callback' => $customize_h2_callback,
) );


/* h2 font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h2_font_size',
	'label'		=> __( 'h2 font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_h2_size,
	'priority'	=> 210,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback' => $customize_h2_callback,
) );


/* h2 line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h2_line_height',
	'label'		=> __( 'h2 line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_line_height,
	'priority'	=> 230,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'active_callback' => $customize_h2_callback,
) );


/* h2 bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h2_bottom_padding',
	'label'		=> __( 'h2 bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 10,
	'priority'	=> 250,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'active_callback' => $customize_h2_callback,
) );


/* Apply these h2 settings also to */
Kirki::add_field( 'dce', array(
	'type'		=> 'select',
	'settings'	=> 'dce_heading_h2_apply',
	'label'		=> __( 'Apply these h2 settings also to:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> array(),
	'priority'	=> 290,
	'multiple'	=> 100,
	'choices'	=> array(
		'related_product'	=> esc_attr__( 'Related products h2', 'divi-children-engine' ),
		'large_blockquote'	=> esc_attr__( 'Quote post format content (1/2 column)', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h2_callback,
) );


/* Disable above h2 settings for */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio',
	'settings'	=> 'dce_heading_h2_disable',
	'label'		=> __( 'Disable above h2 settings for:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 'none',
	'priority'	=> 295,
	'choices'	=> array(
		'none'		=> esc_attr__( 'None', 'divi-children-engine' ),
		'tablet'	=> esc_attr__( 'Tablet and Phone', 'divi-children-engine' ),
		'phone'		=> esc_attr__( 'Phone', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h2_callback,
) );


/* GROUP TITLE: h3 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_hsh3',
	'label'		=> __( 'h3 headings:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'priority'	=> 300,
	'active_callback' => $customize_h3_callback,
) );


/* h3 font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h3_font_size',
	'label'		=> __( 'h3 font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_h3_size,
	'priority'	=> 310,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback' => $customize_h3_callback,
) );


/* h3 line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h3_line_height',
	'label'		=> __( 'h3 line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_line_height,
	'priority'	=> 330,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'active_callback' => $customize_h3_callback,
) );


/* h3 bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h3_bottom_padding',
	'label'		=> __( 'h3 bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 10,
	'priority'	=> 350,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'active_callback' => $customize_h3_callback,
) );


/* Disable above h3 settings for */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio',
	'settings'	=> 'dce_heading_h3_disable',
	'label'		=> __( 'Disable above h3 settings for:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 'none',
	'priority'	=> 395,
	'choices'	=> array(
		'none'		=> esc_attr__( 'None', 'divi-children-engine' ),
		'tablet'	=> esc_attr__( 'Tablet and Phone', 'divi-children-engine' ),
		'phone'		=> esc_attr__( 'Phone', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h3_callback,
) );


/* GROUP TITLE: h4 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_hsh4',
	'label'		=> __( 'h4 headings:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'priority'	=> 400,
	'active_callback' => $customize_h4_callback,
) );


/* h4 font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h4_font_size',
	'label'		=> __( 'h4 font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_h4_size,
	'priority'	=> 410,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback' => $customize_h4_callback,
) );


/* h4 line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h4_line_height',
	'label'		=> __( 'h4 line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_line_height,
	'priority'	=> 430,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'active_callback' => $customize_h4_callback,
) );


/* h4 bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h4_bottom_padding',
	'label'		=> __( 'h4 bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 10,
	'priority'	=> 450,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'active_callback' => $customize_h4_callback,
) );


/* Apply these h4 settings also to */
Kirki::add_field( 'dce', array(
	'type'		=> 'select',
	'settings'	=> 'dce_heading_h4_apply',
	'label'		=> __( 'Apply these h4 settings also to:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> array(
		'footer_widget',
		'blog_grid',
		// 'narrow_blockquote',
		// 'grid_blockquote',
		// 'narrow_link',
		// 'grid_link',
		// 'narrow_audio',
		// 'grid_audio',
		// 'audio_module',
		'portfolio_grid',
		'gallery_grid',
		// 'circle_counter',
		// 'number_counter',
	),
	'priority'	=> 490,
	'multiple'	=> 100,
	'choices'	=> array(
		'footer_widget'		=> esc_attr__( 'Footer widget h4 titles', 'divi-children-engine' ),
		'blog_grid'			=> esc_attr__( 'Blog Grid h2 titles', 'divi-children-engine' ),
		'narrow_blockquote'	=> esc_attr__( 'Quote post format content (< 1/2 column)', 'divi-children-engine' ),
		'grid_blockquote'	=> esc_attr__( 'Quote post format content (Blog Grid)', 'divi-children-engine' ),
		'narrow_link'		=> esc_attr__( 'Link post format content (< 1/2 column)', 'divi-children-engine' ),
		'grid_link'			=> esc_attr__( 'Link post format content (Blog Grid)', 'divi-children-engine' ),
		'narrow_audio'		=> esc_attr__( 'Audio post format content (< 1/2 column)', 'divi-children-engine' ),
		'grid_audio'		=> esc_attr__( 'Audio post format content (Blog Grid)', 'divi-children-engine' ),
		'audio_module'		=> esc_attr__( 'Audio module h2 titles (1/3 & 3/8 columns)', 'divi-children-engine' ),		
		'portfolio_grid'	=> esc_attr__( 'Portfolio Grid h2 titles', 'divi-children-engine' ),
		'gallery_grid'		=> esc_attr__( 'Gallery Grid h3 titles', 'divi-children-engine' ),
		'circle_counter'	=> esc_attr__( 'Circle Counter h3', 'divi-children-engine' ),
		'number_counter'	=> esc_attr__( 'Number Counter h3', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h4_callback,
) );


/* Disable above h4 settings for */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio',
	'settings'	=> 'dce_heading_h4_disable',
	'label'		=> __( 'Disable above h4 settings for:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 'none',
	'priority'	=> 495,
	'choices'	=> array(
		'none'		=> esc_attr__( 'None', 'divi-children-engine' ),
		'tablet'	=> esc_attr__( 'Tablet and Phone', 'divi-children-engine' ),
		'phone'		=> esc_attr__( 'Phone', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h4_callback,
) );


/* GROUP TITLE: h5 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_hsh5',
	'label'		=> __( 'h5 headings:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'priority'	=> 500,
	'active_callback' => $customize_h5_callback,
) );


/* h5 font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h5_font_size',
	'label'		=> __( 'h5 font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_h5_size,
	'priority'	=> 510,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback' => $customize_h5_callback,
) );


/* h5 line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h5_line_height',
	'label'		=> __( 'h5 line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_line_height,
	'priority'	=> 530,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'active_callback' => $customize_h5_callback,
) );


/* h5 bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h5_bottom_padding',
	'label'		=> __( 'h5 bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 10,
	'priority'	=> 550,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'active_callback' => $customize_h5_callback,
) );


/* Apply these h5 settings also to */
Kirki::add_field( 'dce', array(
	'type'		=> 'select',
	'settings'	=> 'dce_heading_h5_apply',
	'label'		=> __( 'Apply these h5 settings also to:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> array(),
	'priority'	=> 590,
	'multiple'	=> 100,
	'choices'	=> array(
		'woocommerce'		=> esc_attr__( 'WooCommerce h3 product lists', 'divi-children-engine' ),
		'audio_module'		=> esc_attr__( 'Audio module h2 titles (1/4 column)', 'divi-children-engine' ),		
		'portfolio_grid'	=> esc_attr__( 'Portfolio Grid h2 titles', 'divi-children-engine' ),
		'gallery_grid'		=> esc_attr__( 'Gallery Grid h3 titles', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h5_callback,
) );


/* Disable above h5 settings for */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio',
	'settings'	=> 'dce_heading_h5_disable',
	'label'		=> __( 'Disable above h5 settings for:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 'none',
	'priority'	=> 595,
	'choices'	=> array(
		'none'		=> esc_attr__( 'None', 'divi-children-engine' ),
		'tablet'	=> esc_attr__( 'Tablet and Phone', 'divi-children-engine' ),
		'phone'		=> esc_attr__( 'Phone', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h5_callback,
) );


/* GROUP TITLE: h6 Headings */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_hsh6',
	'label'		=> __( 'h6 headings:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'priority'	=> 600,
	'active_callback' => $customize_h6_callback,
) );


/* h6 font size */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h6_font_size',
	'label'		=> __( 'h6 font size (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_h6_size,
	'priority'	=> 610,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 100,
		'step'	=> 1,
	),
	'active_callback' => $customize_h6_callback,
) );


/* h6 line height */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h6_line_height',
	'label'		=> __( 'h6 line height (em)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> $default_line_height,
	'priority'	=> 630,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'active_callback' => $customize_h6_callback,
) );


/* h6 bottom padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_heading_h6_bottom_padding',
	'label'		=> __( 'h6 bottom padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 10,
	'priority'	=> 650,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'active_callback' => $customize_h6_callback,
) );


/* Disable above h6 settings for */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio',
	'settings'	=> 'dce_heading_h6_disable',
	'label'		=> __( 'Disable above h6 settings for:', 'divi-children-engine' ),
	'section'	=> 'dce_heading_styles',
	'default'	=> 'none',
	'priority'	=> 695,
	'choices'	=> array(
		'none'		=> esc_attr__( 'None', 'divi-children-engine' ),
		'tablet'	=> esc_attr__( 'Tablet and Phone', 'divi-children-engine' ),
		'phone'		=> esc_attr__( 'Phone', 'divi-children-engine' ),
	),
	'active_callback' => $customize_h6_callback,
) );


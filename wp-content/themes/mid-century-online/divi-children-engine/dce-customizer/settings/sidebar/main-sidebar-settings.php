<?php

/**
 * Customizer controls: Main Sidebar section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* GROUP TITLE: Sidebar general appearance */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_msga',
	'label'		=> __( 'Sidebar general appearance:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 10,
) );


/* Vertical divider line */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_sidebar_vertical_divider',
	'label'			=> __( 'Vertical divider line', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 'initial',
	'priority'		=> 10,
	'choices'	=> array(
		'initial'	=>	__( 'Show', 'divi-children-engine' ),
		'none'		=>	__( 'Hide', 'divi-children-engine' ),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#main-content .container:before',
			'function'	=> 'css',
			'property'	=> 'display',
			'suffix'	=> '!important;',
		),
	),
) );


/* Add a Sidebar background */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_sidebar_background',
	'label'			=> __( 'Add a Sidebar background', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 'none',
	'priority'		=> 20,
	'choices'	=> array(
		'none'		=>	__( 'None', 'divi-children-engine' ),
		'image'		=>	__( 'Image', 'divi-children-engine' ),
		'color'		=>	__( 'Color', 'divi-children-engine' ),
	),
) );

$no_background_callback = array (
	array(
		'setting'	=> 'dce_sidebar_background',
		'operator'	=> '==',
		'value'		=> 'none',
	),
);

$background_callback = array (
	array(
		'setting'	=> 'dce_sidebar_background',
		'operator'	=> '!=',
		'value'		=> 'none',
	),
);


/* GROUP TITLE: Sidebar background */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mssb',
	'label'		=> __( 'Sidebar background:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 100,
	'active_callback' => $background_callback,
) );


/* Background image */
Kirki::add_field( 'dce', array(
	'type'		=> 'image',
	'settings'	=> 'dce_sidebar_background_image',
	'label'		=> __( 'Background image', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'default'	=> '',
	'priority'	=> 110,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.et_right_sidebar #sidebar',
			'function'	=> 'css',
			'property'	=> 'background-image',
			'units'		=> '!important;',
		),
		array(
			'element'	=> '.et_left_sidebar #sidebar',
			'function'	=> 'css',
			'property'	=> 'background-image',
			'units'		=> '!important;',
		),
	),
	'active_callback' => array (
		array(
			'setting'	=> 'dce_sidebar_background',
			'operator'	=> '==',
			'value'		=> 'image',
		),
	),
) );


/* Background color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_sidebar_background_color',
	'label'		=> __( 'Background color', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'default'	=> '#ffffff',
	'priority'	=> 150,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.et_right_sidebar #sidebar',
			'function'	=> 'css',
			'property'	=> 'background-color',
			'units'		=> '!important;',
		),
		array(
			'element'	=> '.et_left_sidebar #sidebar',
			'function'	=> 'css',
			'property'	=> 'background-color',
			'units'		=> '!important;',
		),
	),
	'active_callback' => array (
		array(
			'setting'	=> 'dce_sidebar_background',
			'operator'	=> '==',
			'value'		=> 'color',
		),
	),
) );


/* GROUP TITLE: Paddings and margins */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mspm',
	'label'		=> __( 'Paddings and margins:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 200,
) );


/* Content-Sidebar Spacing */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_content_spacing',
	'label'			=> __( 'Content-sidebar spacing (%)', 'divi-children-engine' ),
	'description'	=> __( 'Left space between the page content and the right sidebar, or right space between the left sidebar and the page content (% of total width).', 'divi-children-engine' ),		
	'section'		=> 'dce_main_sidebar',
	'default'		=> 5.5,
	'priority'		=> 210,
	'choices'		=> array(
		'min'	=> 0,
		'max'	=> 20,
		'step'	=> 0.1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'		=> '.et_right_sidebar #left-area',
			'function'		=> 'css',
			'property'		=> 'padding-right',
			'units'			=> '% !important;',
			'media_query'	=> '@media all and (min-width: 981px)',
		),
		array(
			'element'		=> '.et_left_sidebar #left-area',
			'function'		=> 'css',
			'property'		=> 'padding-left',
			'units'			=> '% !important;',
			'media_query'	=> '@media all and (min-width: 981px)',
		),
	),
) );


/* Content side sidebar padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_sidepadding',
	'label'			=> __( 'Content side sidebar padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 30,
	'priority'		=> 220,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'		=> '.et_right_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-left',
			'units'			=> 'px !important;',
			'media_query'	=> '@media all and (min-width: 981px)',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-right',
			'units'			=> 'px !important;',
			'media_query'	=> '@media all and (min-width: 981px)',
		),
	),
	'active_callback' => $no_background_callback,
) );


/* Sidebar horizontal padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_hor_padding',
	'label'			=> __( 'Sidebar horizontal padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 30,
	'priority'		=> 220,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'		=> '.et_right_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-left',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_right_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-right',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-right',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-left',
			'units'			=> 'px !important;',
		),
	),
	'active_callback' => $background_callback,
) );


/* Sidebar vertical padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_vert_padding',
	'label'			=> __( 'Sidebar vertical padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 30,
	'priority'		=> 230,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'		=> '.et_right_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-top',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_right_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-bottom',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-top',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'padding-bottom',
			'units'			=> 'px !important;',
		),
	),
	'active_callback' => $background_callback,
) );


/* Sidebar widget bottom margin */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_widget_bottommargin',
	'label'			=> __( 'Widgets bottom margin (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 30,
	'priority'		=> 240,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'		=> '.et_right_sidebar #sidebar .et_pb_widget',
			'function'		=> 'css',
			'property'		=> 'margin-bottom',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar .et_pb_widget',
			'function'		=> 'css',
			'property'		=> 'margin-bottom',
			'units'			=> 'px !important;',
		),
	),
) );


/* Sidebar bottom margin */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_bottom_margin',
	'label'			=> __( 'Sidebar bottom margin (px)', 'divi-children-engine' ),
	'description'	=> __( 'Adds spacing between sidebar background and footer, for short pages or small screens.', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 30,
	'priority'		=> 250,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'		=> '.et_right_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'margin-bottom',
			'units'			=> 'px !important;',
		),
		array(
			'element'		=> '.et_left_sidebar #sidebar',
			'function'		=> 'css',
			'property'		=> 'margin-bottom',
			'units'			=> 'px !important;',
		),
	),
	'active_callback' => $background_callback,
) );


/* GROUP TITLE: Sidebar widget titles */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_msswt',
	'label'		=> __( 'Widget titles:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 300,
) );


/* Box titles with a background */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_sidebar_boxed_title',
	'label'			=> __( 'Box titles with a background', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 'default',
	'priority'		=> 310,
	'choices'	=> array(
		'default'	=>	__( 'Default Titles', 'divi-children-engine' ),
		'boxed'		=>	__( 'Boxed Titles', 'divi-children-engine' ),
	),
) );

$boxed_title_callback = array (
	array(
		'setting'	=> 'dce_sidebar_boxed_title',
		'operator'	=> '==',
		'value'		=> 'boxed',
	),
);


/* Boxed titles background color */
Kirki::add_field( 'dce', array(
	'type'        => 'color',
	'settings'    => 'dce_sidebar_boxed_title_backcolor',
	'label'       => __( 'Boxed titles background color', 'divi-children-engine' ),
	'section'     => 'dce_main_sidebar',
	'default'     => '#eeeeee',
	'priority'    => 320,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'background-color',
			'units'		=> '!important;',
		),
	),
	'active_callback' => $boxed_title_callback,
) );


/* Boxed titles vertical padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_boxed_title_vertpadding',
	'label'			=> __( 'Boxed titles vertical padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 10,
	'priority'		=> 320,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'padding-top',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'padding-bottom',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $boxed_title_callback,
) );


/* Boxed titles horizontal padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_boxed_title_horpadding',
	'label'			=> __( 'Boxed titles horizontal padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 10,
	'priority'		=> 330,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'px !important;',
		),
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'padding-right',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $boxed_title_callback,
) );


/* Boxed titles bottom margin */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_boxed_title_bottommargin',
	'label'			=> __( 'Boxed titles bottom margin (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 10,
	'priority'		=> 340,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'margin-bottom',
			'units'		=> 'px !important;',
		),
	),
	'active_callback' => $boxed_title_callback,
) );


$default_h1_size = dce_get_divi_option( 'body_header_size', 30 );
if ( 30 != $default_h1_size ) {
		$default_title_size = round( $default_h1_size * 0.60 );
	} else {
		$default_title_size = 18;
}
$default_h_color = dce_get_divi_option( 'header_color', '#666666' );
$default_h_height = dce_get_divi_option( 'body_header_height', 1 );


/* Widget Titles font size */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_title_size',
	'label'			=> __( 'Titles font size (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> $default_title_size,
	'priority'		=> 400,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 50,
		'step'	=> 1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px !important;',
		),
	),
) );


/* Widget Titles color */
Kirki::add_field( 'dce', array(
	'type'        => 'color',
	'settings'    => 'dce_sidebar_title_color',
	'label'       => __( 'Titles font color', 'divi-children-engine' ),
	'section'     => 'dce_main_sidebar',
	'default'     => $default_h_color,
	'priority'    => 410,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> '!important;',
		),
	),
) );


/* Widget Titles line height (em) */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_title_height',
	'label'			=> __( 'Titles line height (em)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> $default_h_height,
	'priority'		=> 420,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'line-height',
			'units'		=> 'em !important;',
		),
	),
) );


/* Widget Titles font weight */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_title_weight',
	'label'			=> __( 'Titles font weight', 'divi-children-engine' ),
	'description'	=> __( '(Make sure the selected weight is available for the font family being used)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 500,
	'priority'		=> 430,
	'choices'	=> array(
		'min'	=> 300,
		'max'	=> 900,
		'step'	=> 100,
	),
	'transport'		=> 'postMessage',
	'js_vars'		=> array(
		array(
			'element'	=> '#sidebar h4.widgettitle',
			'function'	=> 'css',
			'property'	=> 'font-weight',
			'suffix'	=> '!important;',
		),
	),
) );


/* Uppercase titles */
Kirki::add_field( 'dce', array(
	'type'			=> 'checkbox',
	'settings'		=> 'dce_sidebar_title_uppercase',
	'label'			=> __( 'Uppercase titles', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> '',
	'priority'		=> 440,
	'transport'		=> 'postMessage',
) );


/* Titles in italics */
Kirki::add_field( 'dce', array(
	'type'			=> 'checkbox',
	'settings'		=> 'dce_sidebar_title_italics',
	'label'			=> __( 'Titles in italics', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> '',
	'priority'		=> 450,
	'transport'		=> 'postMessage',
) );


/* GROUP TITLE: Widget text and links */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mswtl',
	'label'		=> __( 'Widget text and links:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 500,
) );


/* Widget text and links font size */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_widgettext_size',
	'label'			=> __( 'Text and links font size (px)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> dce_get_divi_option( 'body_font_size', 14 ),
	'priority'		=> 510,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 30,
		'step'	=> 1,
	),
) );


/* Widget text and links color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_sidebar_widgettext_color',
	'label'		=> __( 'Text and links color', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'default'	=> dce_get_divi_option( 'font_color', '#666666' ),
	'priority'	=> 520,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#sidebar li',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#sidebar .textwidget',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#sidebar li a',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> '!important;',
		),
	),
) );


/* Widget text and links line height */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_sidebar_widgettext_height',
	'label'			=> __( 'Text and links line height (em)', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> dce_get_divi_option( 'body_font_height', 1.7 ),
	'priority'		=> 530,
	'choices'	=> array(
		'min'	=> 0.8,
		'max'	=> 3,
		'step'	=> 0.1,
	),
) );


/* Widget links hover color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_sidebar_widgethover_color',
	'label'		=> __( 'Links hover color', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'default'	=> '#82c0c7',
	'priority'	=> 540,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#main-content #sidebar li a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> '!important;',
		),
		array(
			'element'	=> '#main-content #sidebar .textwidget a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> '!important;',
		),
	),
) );


/* GROUP TITLE: Widget lists styling: */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_mswls',
	'label'		=> __( 'Widget lists styling:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 600,
) );


/* Custom list styles */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_sidebar_widget_lists',
	'label'			=> __( 'Custom list styles', 'divi-children-engine' ),
	'description'	=> __( 'Customize widget lists by adding bullets and backgrounds to widget list elements.', 'divi-children-engine' ),
	'section'		=> 'dce_main_sidebar',
	'default'		=> 'default',
	'priority'		=> 610,
	'choices'	=> array(
		'default' 	=>	__( 'Divi Default', 'divi-children-engine' ),
		'custom'	=>	__( 'Customize Lists', 'divi-children-engine' ),
	),
) );


/* Custom bullets and backgrounds */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-image',
	'settings'	=> 'dce_sidebar_widget_lists_type',
	'label'		=> __( 'Custom bullets and backgrounds:', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'priority'	=> 620,
	'default'	=> 'bullets',
	'choices'	=> array(
		'bullets'			=>	DCE_IMAGES_URL . 'sidebar_widget_bullet.png',
		'squares'			=>	DCE_IMAGES_URL . 'sidebar_widget_square.png',
		'arrows'			=>	DCE_IMAGES_URL . 'sidebar_widget_arrow.png',
		'line'				=>	DCE_IMAGES_URL . 'sidebar_widget_line.png',
		'background'		=>	DCE_IMAGES_URL . 'sidebar_widget_background.png',
		'line-background'	=>	DCE_IMAGES_URL . 'sidebar_widget_line_background.png',
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_sidebar_widget_lists',
			'operator'	=> '==',
			'value'		=> 'custom',
		),
	),
) );


/* Bullets color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_sidebar_bullets_color',
	'label'		=> __( 'Bullets color', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'default'	=> get_theme_mod( 'dce_sidebar_title_color', $default_h_color ),
	'priority'	=> 630,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_sidebar_widget_lists',
			'operator'	=> '==',
			'value'		=> 'custom',
		),
		array(
			'setting'	=> 'dce_sidebar_widget_lists_type',
			'operator'	=> '!=',
			'value'		=> 'background',
		),
	),
) );


/* List elements background color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_sidebar_widget_lists_bkgndcolor',
	'label'		=> __( 'List elements background color', 'divi-children-engine' ),
	'section'	=> 'dce_main_sidebar',
	'default'	=> '#f4f4f4',
	'priority'	=> 640,
	'choices'	=> array(
		'alpha'	=> true,
	),
	'active_callback'	=> array(
		array(
			'setting'	=> 'dce_sidebar_widget_lists',
			'operator'	=> '==',
			'value'		=> 'custom',
		),
		array(
			'setting'	=> 'dce_sidebar_widget_lists_type',
			'operator'	=> '!=',
			'value'		=> 'bullets',
		),
		array(
			'setting'	=> 'dce_sidebar_widget_lists_type',
			'operator'	=> '!=',
			'value'		=> 'squares',
		),
		array(
			'setting'	=> 'dce_sidebar_widget_lists_type',
			'operator'	=> '!=',
			'value'		=> 'arrows',
		),
		array(
			'setting'	=> 'dce_sidebar_widget_lists_type',
			'operator'	=> '!=',
			'value'		=> 'line',
		),
	),
) );


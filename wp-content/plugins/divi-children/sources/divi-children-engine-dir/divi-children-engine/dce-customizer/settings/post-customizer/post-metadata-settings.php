<?php

/**
 * Customizer controls: Single Post Meta Data Styles section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


/* Post meta with or without icons */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_post_postmeta_with_icons',
	'label'			=> __( 'Post meta with or without icons', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata',
	'tooltip'		=> __( 'Choose Divi default (no icons used for the post meta) or customize your post meta with icons. Additional settings for each case will appear below depending on your choice.', 'divi-children-engine' ),
	'default'		=> 'default',
	'priority'		=> 100,
	'choices'		=> array(
		'default'	=>	__( 'Divi Default', 'divi-children-engine' ),
		'icons'		=>	__( 'Post Meta with Icons', 'divi-children-engine' ),
	),
) );


$no_icons_callback = array(
	array(
		'setting'	=> 'dce_post_postmeta_with_icons',
		'operator'	=> '==',
		'value'		=> 'default',
	),
);


$icons_callback = array(
	array(
		'setting'	=> 'dce_post_postmeta_with_icons',
		'operator'	=> '==',
		'value'		=> 'icons',
	),
);


/* GROUP TITLE: Post Meta Data Icons */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pmi',
	'label'		=> __( 'Post Meta Data Icons:', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'priority'	=> 200,
	'active_callback'	=> $icons_callback,
) );


/* Same color for all icons */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_post_postmeta_same_icons_color',
	'label'			=> __( 'Use same color for all icons?', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata',
	'default'		=> 'same',
	'priority'		=> 210,
	'choices'		=> array(
		'same' 		=> __( 'Same Color', 'divi-children-engine' ),
		'different'	=> __( 'Different Colors', 'divi-children-engine' ),
	),
	'active_callback'	=> $icons_callback,
) );


$same_icon_color_callback = array(
	array(
		'setting'	=> 'dce_post_postmeta_with_icons',
		'operator'	=> '==',
		'value'		=> 'icons',
	),
	array(
		'setting'	=> 'dce_post_postmeta_same_icons_color',
		'operator'	=> '==',
		'value'		=> 'same',
	),
);


$different_icon_color_callback = array(
	array(
		'setting'	=> 'dce_post_postmeta_with_icons',
		'operator'	=> '==',
		'value'		=> 'icons',
	),
	array(
		'setting'	=> 'dce_post_postmeta_same_icons_color',
		'operator'	=> '==',
		'value'		=> 'different',
	),
);


/* All icons color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_icon_color',
	'label'		=> __( 'Icons color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 220,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $same_icon_color_callback,
) );


/* Author icon color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_author_color',
	'label'		=> __( 'Author icon color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 230,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon.icon_profile',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $different_icon_color_callback,
) );


/* Date icon color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_date_color',
	'label'		=> __( 'Date icon color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 231,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon.icon_calendar',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $different_icon_color_callback,
) );


/* Date - Last Modified icon color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_mod_date_color',
	'label'		=> __( 'Date - Last Modified icon color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 232,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon.icon_refresh',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $different_icon_color_callback,
) );


/* Categories icon color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_categories_color',
	'label'		=> __( 'Categories icon color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 233,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon.icon_clipboard',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $different_icon_color_callback,
) );


/* Comments icon color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_comments_color',
	'label'		=> __( 'Comments icon color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 234,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon.icon_chat',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $different_icon_color_callback,
) );


/* Tags icon color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_tags_color',
	'label'		=> __( 'Tags icon color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=>  dce_get_divi_option( 'accent_color', '#2ea3f2' ),
	'priority'	=> 235,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon.icon_tags',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $different_icon_color_callback,
) );


/* Post meta icons size */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_post_postmeta_icon_size',
	'label'			=> __( 'Icons size (px)', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata',
	'default'		=> 16,
	'priority'		=> 240,
	'choices'	=> array(
		'min'	=> 10,
		'max'	=> 50,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon:before',
			'function'	=> 'css',
			'property'	=> 'font-size',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback' => $icons_callback,
) );


/* Post meta icons padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_post_postmeta_icon_padding',
	'label'			=> __( 'Icons left padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata',
	'default'		=> 10,
	'priority'		=> 250,
	'choices'	=> array(
		'min'	=> 5,
		'max'	=> 100,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce_icon:before',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback' => $icons_callback,
) );


/* GROUP TITLE: Post Meta Data Separators */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pms',
	'label'		=> __( 'Post Meta Data Separators:', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'priority'	=> 300,
	'active_callback'	=> $no_icons_callback,
) );


/* Post meta separator character */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_post_postmeta_separator',
	'label'		=> __( 'Separator character:', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=> '&#124;',
	'priority'	=> 310,
	'choices'	=> array(
		'&#32;' 	=> ' ',
		'&#124;' 	=> '|',
		'&#8226;' 	=> '•',
		'&#166;' 	=> '¦',
		'&#126;' 	=> '~',
		'&#8211;'	=> '–',
		'&#8212;'	=> '—',
		'&#164;' 	=> '¤',
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-postmeta-separator',
			'function'	=> 'html',
		),
	),
	'active_callback'	=> $no_icons_callback,
) );


/* Separator color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_separator_color',
	'label'		=> __( 'Separator color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=> '#666',
	'priority'	=> 320,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-postmeta-separator',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback'	=> $no_icons_callback,
) );


/* Separator padding */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_post_postmeta_separator_padding',
	'label'			=> __( 'Separator horizontal padding (px)', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata',
	'default'		=> 3,
	'priority'		=> 330,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 15,
		'step'	=> 1,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-postmeta-separator',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '.dce-postmeta-separator',
			'function'	=> 'css',
			'property'	=> 'padding-right',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback' => $no_icons_callback,
) );


/* Separator font weight */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_post_postmeta_separator_weight',
	'label'			=> __( 'Separator font weight:', 'divi-children-engine' ),
	'section'		=> 'dce_post_metadata',
	'description'	=> __( '(Make sure the selected weight is available for the font family being used)', 'divi-children-engine' ),
	'default'		=> 500,
	'priority'		=> 340,
	'choices'	=> array(
		'min'	=> 300,
		'max'	=> 900,
		'step'	=> 100,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-postmeta-separator',
			'function'	=> 'css',
			'property'	=> 'font-weight',
		),
	),
	'active_callback' => $no_icons_callback,
) );


/* GROUP TITLE: Text and Link colors */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_pmtlc',
	'label'		=> __( 'Text and link colors:', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'priority'	=> 1000,
) );


/* Post meta text color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_text_color',
	'label'		=> __( 'Post meta text color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=> '#666',
	'priority'	=> 1010,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.single .post-meta',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
) );


/* Post meta links color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_links_color',
	'label'		=> __( 'Post meta links color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=> '#666',
	'priority'	=> 1020,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.single #main-content .post-meta a',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '.single #main-content .dce-post-tags a',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
) );


/* Post meta links hover color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_post_postmeta_links_color_hover',
	'label'		=> __( 'Post meta links hover color', 'divi-children-engine' ),
	'section'	=> 'dce_post_metadata',
	'default'	=> '#666',
	'priority'	=> 1030,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.single #main-content .post-meta a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '.single #main-content .dce-post-tags a:hover',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
) );


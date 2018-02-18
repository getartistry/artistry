<?php

/**
 * Customizer controls: Footer Credits section
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */


check_production_mode( __FILE__ );


$footer_credits_refresh = array(
	'dce_customize_footer_credits' => array(
		'selector'			=> '#footer-info',
		'render_callback'	=> function() {
			echo et_get_footer_credits();
		},
	),
);


/* Customize Footer Credits */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_customize_footer_credits',
	'label'			=> __( 'Customize Footer Credits:', 'divi-children-engine' ),
	'tooltip' 		=> __( 'Choose whether to customize the Footer Credits of your child theme by means of the "Footer Credits EZ-Builder" or to just use the Divi default options.', 'divi-children-engine' ),
	'section'		=> 'dce_footer_credits',
	'default'		=> '1',
	'priority'		=> 10,
	'choices'		=> array(
		'0'	=> __( 'Use Divi options', 'divi-children-engine' ),
		'1'	=> __( 'Custom Footer Credits', 'divi-children-engine' ),
	),
) );

$customize_footer_credits_callback = array(
	array(
		'setting'  => 'dce_customize_footer_credits',
		'operator' => '==',
		'value'    => '1',
	),
);


// Check Divi options to make sure Footer Credits are not disabled:
$disable_custom_credits = dce_get_divi_option( 'disable_custom_footer_credits', false );

if ( $disable_custom_credits ) {

	$message = __( 'Your Custom Footer Credits won´t work because they were disabled by a Divi option within the Customizer. Please enable them in Footer > Bottom Bar > DISABLE FOOTER CREDITS.', 'divi-children-engine' );

	/* WARNING MESSAGE: Custom Credits Disabled */
	Kirki::add_field( 'dce', array(
		'type'		=> 'custom',
		'settings'	=> 'wm_custom_credits_disabled',
		'section'	=> 'dce_footer_credits',
		'default'	=> '<div class="dce-warning">' . $message . '</div>',
		'priority'	=> 11,
		'active_callback' => $customize_footer_credits_callback,
	) );

}


/* GROUP TITLE: Footer Credits Elements */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_fce',
	'label'		=> __( 'Footer Credits Elements:', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'priority'	=> 100,
	'active_callback' => $customize_footer_credits_callback,
) );


/* Footer Credits EZ-Builder */
Kirki::add_field( 'dce', array(
	'type'			=> 'repeater',
	'settings'		=> 'dce_footer_credits',
	'label'			=> __( 'Footer Credits EZ-Builder', 'divi-children-engine' ),
	'description'	=> __( 'Add or remove text and link elements and drag them to reorder them as you like.', 'divi-children-engine' ),
	'section'		=> 'dce_footer_credits',
	'priority'		=> 110,
	'row_label'		=> array(               
		'type'	=> 'field',
		'value'	=> __('Footer Credits Element', 'divi-children-engine' ),
		'field'	=> 'link',
	),
	'default'		=> dce_get_footer_credits_defaults(),
	'fields'		=> array(
		'before'		=> array(
			'type'			=> 'text',
			'label'			=> __( 'Text before link', 'divi-children-engine' ),
			'default'		=> '',
		),
		'link'		=> array(
			'type'			=> 'text',
			'label'			=> __( 'Link text', 'divi-children-engine' ),
			'default'		=> get_bloginfo( 'name' ),
		),
		'url'		=> array(
			'type'			=> 'text',
			'label'			=> __( 'Link URL', 'divi-children-engine' ),
			'default'		=> home_url(),
		),
		'blank'		=> array(
			'type'			=> 'checkbox',
			'label'			=> __( 'Open link in a new browser tab', 'divi-children-engine' ),
			'default'		=> '1',
		),
		'after'		=> array(
			'type'			=> 'text',
			'label'			=> __( 'Text after link', 'divi-children-engine' ),
			'default'		=> '',
		),
		'years'		=> array(
			'type'			=> 'select',
			'label'			=> __( 'Add automatic year(s)?', 'divi-children-engine' ),
			'default'		=> 'none',
			'choices'		=> array(
				'none'				=> __( 'No', 'divi-children-engine' ),
				'before-before'		=> __( 'Before "Text before link"', 'divi-children-engine' ),
				'after-before'		=> __( 'After "Text before link"', 'divi-children-engine' ),
				'before-link'		=> __( 'Before Link, as part of it', 'divi-children-engine' ),
				'after-link'		=> __( 'After Link, as part of it', 'divi-children-engine' ),		
				'before-after'		=> __( 'Before "Text after link"', 'divi-children-engine' ),
				'after-after'		=> __( 'After "Text after link"', 'divi-children-engine' ),
			),
		),		
	),
	'active_callback' => $customize_footer_credits_callback,
	// 'transport'	=> 'postMessage', // Expected to work in Kirki 3.1
	// 'partial_refresh' => $footer_credits_refresh,
) );


/* Starting year */
Kirki::add_field( 'dce', array(
	'type'				=> 'text',
	'settings'			=> 'dce_footer_credits_firstyear',
	'label'				=> __( 'Site launched on:', 'divi-children-engine' ),
	'description' 		=> __( 'Enter the year your site was launched if you want to display a year range (the current year will be updated automatically).', 'divi-children-engine' ),
	'section'			=> 'dce_footer_credits',
	'default'			=> date( 'Y' ),
	'priority'			=> 120,
	'transport'			=> 'postMessage',
	'partial_refresh'	=> $footer_credits_refresh,
	'active_callback'	=> $customize_footer_credits_callback,	
) );


/* GROUP TITLE: Separators */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_fcs',
	'label'		=> __( 'Separators:', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'priority'	=> 200,
	'active_callback' => $customize_footer_credits_callback,
) );


/* Separator character */
Kirki::add_field( 'dce', array(
	'type'		=> 'radio-buttonset',
	'settings'	=> 'dce_footer_credits_separator',
	'label'		=> __( 'Separator character:', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'default'	=> '&#124;',
	'priority'	=> 210,
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
			'element'	=> '.dce-credits-separator',
			'function'	=> 'html',
		),
	),
	'active_callback' => $customize_footer_credits_callback,
) );


/* Separator horiz. padding */
Kirki::add_field( 'dce', array(
	'type'		=> 'slider',
	'settings'	=> 'dce_footer_credits_separator_padding',
	'label'		=> __( 'Separator horizontal padding (px)', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'default'	=> 3,
	'priority'	=> 220,
	'choices'	=> array(
		'min'	=> 0,
		'max'	=> 50,
		'step'	=> 1,
	),	
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-credits-separator',
			'function'	=> 'css',
			'property'	=> 'padding-left',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
		array(
			'element'	=> '.dce-credits-separator',
			'function'	=> 'css',
			'property'	=> 'padding-right',
			'units'		=> 'px',
			'suffix'	=> ' !important;',
		),
	),
	'active_callback' => $customize_footer_credits_callback,
) );


/* Separator color */
Kirki::add_field( 'dce', array(
	'type'		=> 'color',
	'settings'	=> 'dce_footer_credits_separator_color',
	'label'		=> __( 'Separator color', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'default'	=> get_theme_mod( 'dce_footer_bottom_textcolor', '#666666' ),
	'priority'	=> 230,
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-credits-separator',
			'function'	=> 'css',
			'property'	=> 'color',
		),
	),
	'active_callback' => $customize_footer_credits_callback,
) );


/* Separator font weight */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'	=> 'dce_footer_credits_separator_font_weight',
	'label'		=> __( 'Separators font weight:', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'description'	=> __( '(Make sure the selected weight is available for the font family being used)', 'divi-children-engine' ),
	'default'	=> 700,
	'priority'	=> 240,
	'choices'	=> array(
		'min'	=> 300,
		'max'	=> 900,
		'step'	=> 100,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '.dce-credits-separator',
			'function'	=> 'css',
			'property'	=> 'font-weight',
		),
	),
	'active_callback' => $customize_footer_credits_callback,
) );


/* GROUP TITLE: Links */
Kirki::add_field( 'dce', array(
	'type'		=> 'custom',
	'settings'	=> 'gt_fbl',
	'label'		=> __( 'Links:', 'divi-children-engine' ),
	'section'	=> 'dce_footer_credits',
	'priority'	=> 300,
) );


/* Customize Footer Credits Link colors */
Kirki::add_field( 'dce', array(
	'type'			=> 'radio-buttonset',
	'settings'		=> 'dce_footer_credits_customize_linkcolor',
	'label'			=> __( 'Customize Footer Credits links color:', 'divi-children-engine' ),
	'section'		=> 'dce_footer_credits',
	'default'		=> '1',
	'priority'		=> 310,
	'choices'		=> array(
		'0'	=> __( 'Same as text', 'divi-children-engine' ),
		'1'	=> __( 'Customize links', 'divi-children-engine' ),
	),	
) );


/* Links color */
Kirki::add_field( 'dce', array(
	'type'			=> 'color',
	'settings'		=> 'dce_footer_credits_linkcolor',
	'label'			=> __( 'Links color', 'divi-children-engine' ),
	'section'		=> 'dce_footer_credits',
	'default'		=> dce_get_divi_option( 'bottom_bar_text_color', '#666666' ),
	'priority'		=> 320,
	'active_callback'  => array(
		array(
			'setting'  => 'dce_footer_credits_customize_linkcolor',
			'operator' => '==',
			'value'    => '1',
		),
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-info a',
			'function'	=> 'css',
			'property'	=> 'color',
			'suffix'	=> ' !important;',
		),
	),
) );


/* Link Font Weight */
Kirki::add_field( 'dce', array(
	'type'			=> 'slider',
	'settings'		=> 'dce_footer_credits_linkweight',
	'label'			=> __( 'Links font weight', 'divi-children-engine' ),
	'description'		=> __( '(Make sure the selected weight is available for the font family being used)', 'divi-children-engine' ),
	'section'		=> 'dce_footer_credits',
	'default'		=> 700,
	'priority'		=> 330,
	'choices'	=> array(
		'min'	=> 300,
		'max'	=> 900,
		'step'	=> 100,
	),
	'transport'	=> 'postMessage',
	'js_vars'	=> array(
		array(
			'element'	=> '#footer-info a',
			'function'	=> 'css',
			'property'	=> 'font-weight',
		),
	),	
) );




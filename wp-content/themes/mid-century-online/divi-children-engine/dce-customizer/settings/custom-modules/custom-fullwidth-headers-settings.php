<?php

/**
 * Customizer controls - Divi modules with custom selectors: Custom Fullwidth Header modules
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



$custom_fw_headers_realkeys = dce_get_custom_selectors_realkeys( 'custom_fullwidth_header' );

if ( $custom_fw_headers_realkeys ) {

	foreach ( $custom_fw_headers_realkeys as $key => $value ) {

		check_production_mode( __FILE__, $key );


		$this_section = 'dce_custom_fullwidth_header_' . $key;


		/* GROUP TITLE: Logo image */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cfwhli_' . $key,
			'label'		=> __( 'Logo image:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 100,
		) );


		/* Logo image top margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_logo_topmargin_' . $key,
			'label'			=> __( 'Logo top margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 110,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content img',
					'function'		=> 'css',
					'property'		=> 'margin-top',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* Logo image bottom margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_logo_bottommargin_' . $key,
			'label'			=> __( 'Logo bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 120,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content img',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* GROUP TITLE: Header title */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cfwhht_' . $key,
			'label'		=> __( 'Title:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 200,
		) );


		/* Add a text background to the title */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_cfwh_title_background_' . $key,
			'label'			=> __( 'Add a text background to the Title', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 'default',
			'priority'		=> 210,
			'choices'	=> array(
				'default'		=>	__( 'Default Title', 'divi-children-engine' ),
				'background'	=>	__( 'Add Background', 'divi-children-engine' ),
			),
		) );

		$title_background_callback = array (
			array(
				'setting'	=> 'dce_cfwh_title_background_' . $key,
				'operator'	=> '==',
				'value'		=> 'background',
			),
		);


		/* Title background color */
		Kirki::add_field( 'dce', array(
			'type'        => 'color',
			'settings'    => 'dce_cfwh_title_backcolor_' . $key,
			'label'       => __( 'Title background color', 'divi-children-engine' ),
			'section'     => $this_section,
			'default'     => '#eeeeee',
			'priority'    => 220,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'background-color',
					'units'		=> '!important;',
				),
			),
			'active_callback' => $title_background_callback,
		) );


		/* Title vertical padding */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_title_vertpadding_' . $key,
			'label'			=> __( 'Title vertical padding (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 20,
			'priority'		=> 230,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 100,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'padding-top',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'padding-bottom',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $title_background_callback,
		) );


		/* Title horizontal padding (%) */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_title_horpadding_' . $key,
			'label'			=> __( 'Title horizontal padding (%)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 10,
			'priority'		=> 240,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 30,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'padding-left',
					'units'		=> '% !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'padding-right',
					'units'		=> '% !important;',
				),
			),
			'active_callback' => $title_background_callback,
		) );


		/* Title background border radius */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_title_radius_' . $key,
			'label'			=> __( 'Title background border radius (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 250,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 100,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> '-webkit-border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> '-moz-border-radius',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $title_background_callback,
		) );


		/* Title line height (em) */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_title_height_' . $key,
			'label'			=> __( 'Title line height (em)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 1,
			'priority'		=> 260,
			'choices'	=> array(
				'min'	=> 0.8,
				'max'	=> 3,
				'step'	=> 0.1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content h1',
					'function'	=> 'css',
					'property'	=> 'line-height',
					'units'		=> 'em !important;',
				),
			),
		) );


		/* Title bottom margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_title_bottommargin_' . $key,
			'label'			=> __( 'Title bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 270,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content h1',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* GROUP TITLE: Subheading */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cfwhsh_' . $key,
			'label'		=> __( 'Subheading:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 300,
		) );


		/* Add a text background to the Subheading */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_cfwh_subhead_background_' . $key,
			'label'			=> __( 'Add a text background to the Subheading', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 'default',
			'priority'		=> 310,
			'choices'	=> array(
				'default'		=>	__( 'Default Subheading', 'divi-children-engine' ),
				'background'	=>	__( 'Add Background', 'divi-children-engine' ),
			),
		) );

		$subhead_background_callback = array (
			array(
				'setting'	=> 'dce_cfwh_subhead_background_' . $key,
				'operator'	=> '==',
				'value'		=> 'background',
			),
		);


		/* Subheading background color */
		Kirki::add_field( 'dce', array(
			'type'        => 'color',
			'settings'    => 'dce_cfwh_subhead_backcolor_' . $key,
			'label'       => __( 'Subheading background color', 'divi-children-engine' ),
			'section'     => $this_section,
			'default'     => '#eeeeee',
			'priority'    => 320,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'background-color',
					'units'		=> '!important;',
				),
			),
			'active_callback' => $subhead_background_callback,
		) );


		/* Subheading vertical padding */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_subhead_vertpadding_' . $key,
			'label'			=> __( 'Subheading vertical padding (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 20,
			'priority'		=> 330,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 100,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'padding-top',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'padding-bottom',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $subhead_background_callback,
		) );


		/* Subheading horizontal padding (%) */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_subhead_horpadding_' . $key,
			'label'			=> __( 'Subheading horizontal padding (%)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 10,
			'priority'		=> 340,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 30,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'padding-left',
					'units'		=> '% !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'padding-right',
					'units'		=> '% !important;',
				),
			),
			'active_callback' => $subhead_background_callback,
		) );


		/* Subheading background border radius */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_subhead_radius_' . $key,
			'label'			=> __( 'Subhead background border radius (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 350,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 100,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> '-webkit-border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> '-moz-border-radius',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $subhead_background_callback,
		) );


		/* Subheading line height (em) */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_subhead_height_' . $key,
			'label'			=> __( 'Subheading line height (em)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 1.7,
			'priority'		=> 360,
			'choices'	=> array(
				'min'	=> 0.8,
				'max'	=> 3,
				'step'	=> 0.1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'	=> 'css',
					'property'	=> 'line-height',
					'units'		=> 'em !important;',
				),
			),
		) );


		/* Subheading bottom margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_subhead_bottommargin_' . $key,
			'label'			=> __( 'Subheading bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 370,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content .et_pb_fullwidth_header_subhead',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* GROUP TITLE: Buttons */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cfwhb_' . $key,
			'label'		=> __( 'Buttons:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 400,
		) );


		/* Buttons top margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_buttons_topmargin_' . $key,
			'label'			=> __( 'Buttons top margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 20,
			'priority'		=> 410,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content .et_pb_more_button',
					'function'		=> 'css',
					'property'		=> 'margin-top',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* Buttons bottom margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_buttons_bottommargin_' . $key,
			'label'			=> __( 'Buttons bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 420,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content .et_pb_more_button',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* Customize spacing between buttons */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_cfwh_buttons_separation_' . $key,
			'label'			=> __( 'Customize spacing between buttons', 'divi-children-engine' ),
			'description'	=> __( 'Align Button #1 to the left of the header container and Button #2 to the right, or separate the buttons with custom spacing. For desktops and tablets only.', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 'default',
			'priority'		=> 430,
			'choices'	=> array(
				'default'	=>	__( 'Default', 'divi-children-engine' ),
				'float'		=>	__( 'Align Buttons', 'divi-children-engine' ),
				'custom'	=>	__( 'Custom', 'divi-children-engine' ),
			),
		) );


		/* Buttons separation for desktop */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_buttons_separation_desktop_' . $key,
			'label'			=> __( 'Buttons separation for desktop (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 15,
			'priority'		=> 440,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 500,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content .et_pb_button_two',
					'function'		=> 'css',
					'property'		=> 'margin-left',
					'units'			=> 'px !important;',
					'media_query'	=> '@media all and (min-width: 981px)',
				),
			),
			'active_callback' => array(
				array(
					'setting'	=> 'dce_cfwh_buttons_separation_' . $key,
					'operator'	=> '==',
					'value'		=> 'custom',
				),
			),
		) );


		/* Buttons separation for tablet */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_cfwh_buttons_separation_tablet_' . $key,
			'label'			=> __( 'Buttons separation for tablet (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 15,
			'priority'		=> 450,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 500,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .header-content .et_pb_button_two',
					'function'		=> 'css',
					'property'		=> 'margin-left',
					'units'			=> 'px !important;',
					'media_query'	=> '@media all and (min-width: 768px) and (max-width: 980px)',
				),
			),
			'active_callback' => array(
				array(
					'setting'	=> 'dce_cfwh_buttons_separation_' . $key,
					'operator'	=> '==',
					'value'		=> 'custom',
				),
			),
		) );


		/* Correct phone Button #2 left margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'checkbox',
			'settings'		=> 'dce_cfwh_buttons_button2_nomargin' . $key,
			'label'			=> __( 'Correct phone Button #2 left margin', 'divi-children-engine' ),
			'description'	=> __( 'Properly align both buttons vertically on smaller screens by removing the second button left margin, which makes the buttons look awkward and misaligned.', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> '',
			'priority'		=> 460,
		) );


	}

}

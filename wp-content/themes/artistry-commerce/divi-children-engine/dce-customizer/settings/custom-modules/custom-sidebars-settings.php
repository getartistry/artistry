<?php

/**
 * Customizer controls - Divi modules with custom selectors: Custom Sidebar sections
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */



$custom_sidebar_modules_realkeys = dce_get_custom_selectors_realkeys( 'custom_sidebar_module' );

if ( $custom_sidebar_modules_realkeys ) {

	foreach ( $custom_sidebar_modules_realkeys as $key => $value ) {


		check_production_mode( __FILE__, $key );


		$this_section = 'dce_custom_sidebar_module_' . $key;


		/* GROUP TITLE: Sidebar general appearance */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_msga_' . $key,
			'label'		=> __( 'Sidebar general appearance:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 10,
		) );
		

		/* Add a Sidebar background */
		Kirki::add_field( 'dce', array(
			'type'		=> 'radio-buttonset',
			'settings'	=> 'dce_csb_background_' . $key,
			'label'		=> __( 'Add a Sidebar background', 'divi-children-engine' ),
			'section'	=> $this_section,
			'default'	=> 'none',
			'priority'	=> 20,
			'choices'	=> array(
				'none'		=>	__( 'None', 'divi-children-engine' ),
				'image'		=>	__( 'Image', 'divi-children-engine' ),
				'color'		=>	__( 'Color', 'divi-children-engine' ),
			),
		) );


		$no_background_callback = array (
			array(
				'setting'	=> 'dce_csb_background_' . $key,
				'operator'	=> '==',
				'value'		=> 'none',
			),
		);

		$background_callback = array (
			array(
				'setting'	=> 'dce_csb_background_' . $key,
				'operator'	=> '!=',
				'value'		=> 'none',
			),
		);


		/* GROUP TITLE: Sidebar background */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_csbsb_' . $key,
			'label'		=> __( 'Sidebar background:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 100,
			'active_callback' => $background_callback,
		) );


		/* Background image */
		Kirki::add_field( 'dce', array(
			'type'		=> 'image',
			'settings'	=> 'dce_csb_background_image_' . $key,
			'label'		=> __( 'Background image', 'divi-children-engine' ),
			'section'	=> $this_section,
			'default'	=> '',
			'priority'	=> 110,
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '#' . $value . '.et_pb_widget_area_right',
					'function'	=> 'css',
					'property'	=> 'background-image',
					'units'		=> '!important;',
				),
				array(
					'element'	=> '#' . $value . '.et_pb_widget_area_left',
					'function'	=> 'css',
					'property'	=> 'background-image',
					'units'		=> '!important;',
				),
			),
			'active_callback' => array (
				array(
					'setting'	=> 'dce_csb_background_' . $key,
					'operator'	=> '==',
					'value'		=> 'image',
				),
			),
		) );


		/* Background color */
		Kirki::add_field( 'dce', array(
			'type'		=> 'color',
			'settings'	=> 'dce_csb_background_color_' . $key,
			'label'		=> __( 'Background color', 'divi-children-engine' ),
			'section'	=> $this_section,
			'default'	=> '#ffffff',
			'priority'	=> 150,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '#' . $value . '.et_pb_widget_area_right',
					'function'	=> 'css',
					'property'	=> 'background-color',
					'units'		=> '!important;',
				),
				array(
					'element'	=> '#' . $value . '.et_pb_widget_area_left',
					'function'	=> 'css',
					'property'	=> 'background-color',
					'units'		=> '!important;',
				),
			),
			'active_callback' => array (
				array(
					'setting'	=> 'dce_csb_background_' . $key,
					'operator'	=> '==',
					'value'		=> 'color',
				),
			),
		) );


		/* GROUP TITLE: Paddings and margins */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_csbpm_' . $key,
			'label'		=> __( 'Paddings and margins:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 200,
		) );


		/* Oposite side padding */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_csb_sidepadding_' . $key,
			// 'label'			=> __( 'Content side sidebar padding (px)', 'divi-children-engine' ),
			'label'			=> __( 'Oposite side padding (px)', 'divi-children-engine' ),
			'description'	=> __( 'Right padding for the left-oriented sidebar modules or left padding for right-oriented sidebar modules.', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 30,
			'priority'		=> 210,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 100,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_right',
					'function'		=> 'css',
					'property'		=> 'padding-left',
					'units'			=> 'px !important;',
					'media_query'	=> '@media all and (min-width: 981px)',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_left',
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
			'settings'		=> 'dce_csb_hor_padding_' . $key,
			'label'			=> __( 'Sidebar horizontal padding (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
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
					'element'		=> '#' . $value . '.et_pb_widget_area_right',
					'function'		=> 'css',
					'property'		=> 'padding-left',
					'units'			=> 'px !important;',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_right',
					'function'		=> 'css',
					'property'		=> 'padding-right',
					'units'			=> 'px !important;',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_left',
					'function'		=> 'css',
					'property'		=> 'padding-right',
					'units'			=> 'px !important;',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_left',
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
			'settings'		=> 'dce_csb_vert_padding_' . $key,
			'label'			=> __( 'Sidebar vertical padding (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
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
					'element'		=> '#' . $value . '.et_pb_widget_area_right',
					'function'		=> 'css',
					'property'		=> 'padding-top',
					'units'			=> 'px !important;',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_right',
					'function'		=> 'css',
					'property'		=> 'padding-bottom',
					'units'			=> 'px !important;',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_left',
					'function'		=> 'css',
					'property'		=> 'padding-top',
					'units'			=> 'px !important;',
				),
				array(
					'element'		=> '#' . $value . '.et_pb_widget_area_left',
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
			'settings'		=> 'dce_csb_widget_bottommargin_' . $key,
			'label'			=> __( 'Widgets bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
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
					'element'		=> '#' . $value . ' .et_pb_widget',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* GROUP TITLE: Widget titles */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_csbwt_' . $key,
			'label'		=> __( 'Widget titles:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 300,
		) );


		/* Box titles with a background */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_csb_boxed_title_' . $key,
			'label'			=> __( 'Box titles with a background', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 'default',
			'priority'		=> 310,
			'choices'	=> array(
				'default'	=>	__( 'Default Titles', 'divi-children-engine' ),
				'boxed'		=>	__( 'Boxed Titles', 'divi-children-engine' ),
			),
		) );

		$boxed_title_callback = array (
			array(
				'setting'	=> 'dce_csb_boxed_title_' . $key,
				'operator'	=> '==',
				'value'		=> 'boxed',
			),
		);

		/* Boxed titles background color */
		Kirki::add_field( 'dce', array(
			'type'        => 'color',
			'settings'    => 'dce_csb_boxed_title_backcolor_' . $key,
			'label'       => __( 'Boxed titles background color', 'divi-children-engine' ),
			'section'     => $this_section,
			'default'     => '#eeeeee',
			'priority'    => 320,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '#' . $value . ' h4.widgettitle',
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
			'settings'		=> 'dce_csb_boxed_title_vertpadding_' . $key,
			'label'			=> __( 'Boxed titles vertical padding (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
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
					'element'	=> '#' . $value . ' h4.widgettitle',
					'function'	=> 'css',
					'property'	=> 'padding-top',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '#' . $value . ' h4.widgettitle',
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
			'settings'		=> 'dce_csb_boxed_title_horpadding_' . $key,
			'label'			=> __( 'Boxed titles horizontal padding (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
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
					'element'	=> '#' . $value . ' h4.widgettitle',
					'function'	=> 'css',
					'property'	=> 'padding-left',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '#' . $value . ' h4.widgettitle',
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
			'settings'		=> 'dce_csb_boxed_title_bottommargin_' . $key,
			'label'			=> __( 'Boxed titles bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
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
					'element'	=> '#' . $value . ' h4.widgettitle',
					'function'	=> 'css',
					'property'	=> 'margin-bottom',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $boxed_title_callback,
		) );


		/* GROUP TITLE: Widget links */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_csbwl_' . $key,
			'label'		=> __( 'Widget links:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 500,
		) );


		/* Widget links hover color */
		Kirki::add_field( 'dce', array(
			'type'		=> 'color',
			'settings'	=> 'dce_csb_widgethover_color_' . $key,
			'label'		=> __( 'Links hover color', 'divi-children-engine' ),
			'section'	=> $this_section,
			'default'	=> '#82c0c7',
			'priority'	=> 510,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '#main-content #' . $value . ' li a:hover',
					'function'	=> 'css',
					'property'	=> 'color',
					'suffix'	=> '!important;',
				),
				array(
					'element'	=> '#main-content #' . $value . ' .textwidget a:hover',
					'function'	=> 'css',
					'property'	=> 'color',
					'suffix'	=> '!important;',
				),
			),
		) );


		/* GROUP TITLE: Widget lists styling: */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_csbwls_' . $key,
			'label'		=> __( 'Widget lists styling:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 600,
		) );


		/* Custom list styles */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_csb_widget_lists_' . $key,
			'label'			=> __( 'Custom list styles', 'divi-children-engine' ),
			'description'	=> __( 'Customize widget lists by adding bullets and backgrounds to widget list elements.', 'divi-children-engine' ),
			'section'		=> $this_section,
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
			'settings'	=> 'dce_csb_widget_lists_type_' . $key,
			'label'		=> __( 'Custom bullets and backgrounds:', 'divi-children-engine' ),
			'section'	=> $this_section,
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
					'setting'	=> 'dce_csb_widget_lists_' . $key,
					'operator'	=> '==',
					'value'		=> 'custom',
				),
			),
		) );


		/* Bullets color */
		Kirki::add_field( 'dce', array(
			'type'		=> 'color',
			'settings'	=> 'dce_csb_bullets_color_' . $key,
			'label'		=> __( 'Bullets color', 'divi-children-engine' ),
			'section'	=> $this_section,
			'default'	=> '#666666',
			'priority'	=> 630,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'active_callback'	=> array(
				array(
					'setting'	=> 'dce_csb_widget_lists_' . $key,
					'operator'	=> '==',
					'value'		=> 'custom',
				),
				array(
					'setting'	=> 'dce_csb_widget_lists_type_' . $key,
					'operator'	=> '!=',
					'value'		=> 'background',
				),
			),
		) );


		/* List elements background color */
		Kirki::add_field( 'dce', array(
			'type'		=> 'color',
			'settings'	=> 'dce_csb_widget_lists_bkgndcolor_' . $key,
			'label'		=> __( 'List elements background color', 'divi-children-engine' ),
			'section'	=> $this_section,
			'default'	=> '#f4f4f4',
			'priority'	=> 640,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'active_callback'	=> array(
				array(
					'setting'	=> 'dce_csb_widget_lists_' . $key,
					'operator'	=> '==',
					'value'		=> 'custom',
				),
				array(
					'setting'	=> 'dce_csb_widget_lists_type_' . $key,
					'operator'	=> '!=',
					'value'		=> 'bullets',
				),
				array(
					'setting'	=> 'dce_csb_widget_lists_type_' . $key,
					'operator'	=> '!=',
					'value'		=> 'squares',
				),
				array(
					'setting'	=> 'dce_csb_widget_lists_type_' . $key,
					'operator'	=> '!=',
					'value'		=> 'arrows',
				),
				array(
					'setting'	=> 'dce_csb_widget_lists_type_' . $key,
					'operator'	=> '!=',
					'value'		=> 'line',
				),
			),
		) );


	}

}


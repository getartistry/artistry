<?php

/**
 * Customizer controls - Divi modules with custom selectors: Custom Call to Action modules
 *
 * Part of the Divi Children Engine - http://divi4u.com
 */

$custom_ctas_realkeys = dce_get_custom_selectors_realkeys( 'custom_cta' );

if ( $custom_ctas_realkeys ) {

	foreach ( $custom_ctas_realkeys as $key => $value ) {


		check_production_mode( __FILE__, $key );


		$this_section = 'dce_custom_cta_' . $key;


		/* GROUP TITLE: General module setttings */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cctagms_' . $key,
			'label'		=> __( 'General module setttings:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 100,
		) );


		/* Module border radius */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_ccta_module_radius_' . $key,
			'label'			=> __( 'Module border radius (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 110,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 100,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'	=> '.' . $value,
					'function'	=> 'css',
					'property'	=> 'border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value,
					'function'	=> 'css',
					'property'	=> '-webkit-border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value,
					'function'	=> 'css',
					'property'	=> '-moz-border-radius',
					'units'		=> 'px !important;',
				),
			),
		) );


		/* GROUP TITLE: Description area (Title + Content) */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cctac_' . $key,
			'label'		=> __( 'Description area (Title + Content):', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 200,
		) );


		/* Add a text background to the Description */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_ccta_description_background_' . $key,
			'label'			=> __( 'Add a text background to the Description', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 'default',
			'priority'		=> 210,
			'choices'	=> array(
				'default'		=>	__( 'Default Description', 'divi-children-engine' ),
				'background'	=>	__( 'Add Background', 'divi-children-engine' ),
			),
		) );

		$description_background_callback = array (
			array(
				'setting'	=> 'dce_ccta_description_background_' . $key,
				'operator'	=> '==',
				'value'		=> 'background',
			),
		);


		/* Description background color */
		Kirki::add_field( 'dce', array(
			'type'        => 'color',
			'settings'    => 'dce_ccta_description_backcolor_' . $key,
			'label'       => __( 'Description background color', 'divi-children-engine' ),
			'section'     => $this_section,
			'default'     => '#eeeeee',
			'priority'    => 220,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> 'background-color',
					'units'		=> '!important;',
				),
			),
			'active_callback' => $description_background_callback,
		) );


		/* Description vertical padding */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_ccta_description_vertpadding_' . $key,
			'label'			=> __( 'Description vertical padding (px)', 'divi-children-engine' ),
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
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> 'padding-top',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> 'padding-bottom',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $description_background_callback,
		) );


		/* Description horizontal padding (%) */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_ccta_description_horpadding_' . $key,
			'label'			=> __( 'Description horizontal padding (%)', 'divi-children-engine' ),
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
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> 'padding-left',
					'units'		=> '% !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> 'padding-right',
					'units'		=> '% !important;',
				),
			),
			'active_callback' => $description_background_callback,
		) );


		/* Description background border radius */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_ccta_description_radius_' . $key,
			'label'			=> __( 'Description area border radius (px)', 'divi-children-engine' ),
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
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> 'border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> '-webkit-border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description',
					'function'	=> 'css',
					'property'	=> '-moz-border-radius',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $description_background_callback,
		) );


		/* Description bottom margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_ccta_description_bottommargin_' . $key,
			'label'			=> __( 'Description bottom margin (px)', 'divi-children-engine' ),
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
					'element'		=> '.' . $value . ' .et_pb_promo_description',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


		/* GROUP TITLE: Title */
		Kirki::add_field( 'dce', array(
			'type'		=> 'custom',
			'settings'	=> 'gt_cctat_' . $key,
			'label'		=> __( 'Title:', 'divi-children-engine' ),
			'section'	=> $this_section,
			'priority'	=> 300,
		) );


		/* Add a text background to the title */
		Kirki::add_field( 'dce', array(
			'type'			=> 'radio-buttonset',
			'settings'		=> 'dce_ccta_title_background_' . $key,
			'label'			=> __( 'Add a text background to the Title', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 'default',
			'priority'		=> 310,
			'choices'	=> array(
				'default'		=>	__( 'Default Title', 'divi-children-engine' ),
				'background'	=>	__( 'Add Background', 'divi-children-engine' ),
			),
		) );

		$title_background_callback = array (
			array(
				'setting'	=> 'dce_ccta_title_background_' . $key,
				'operator'	=> '==',
				'value'		=> 'background',
			),
		);


		/* Title background color */
		Kirki::add_field( 'dce', array(
			'type'        => 'color',
			'settings'    => 'dce_ccta_title_backcolor_' . $key,
			'label'       => __( 'Title background color', 'divi-children-engine' ),
			'section'     => $this_section,
			'default'     => '#eeeeee',
			'priority'    => 320,
			'choices'	=> array(
				'alpha'	=> true,
			),
			'transport'	=> 'postMessage',
			'js_vars'	=> array(
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
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
			'settings'		=> 'dce_ccta_title_vertpadding_' . $key,
			'label'			=> __( 'Title vertical padding (px)', 'divi-children-engine' ),
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
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
					'function'	=> 'css',
					'property'	=> 'padding-top',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
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
			'settings'		=> 'dce_ccta_title_horpadding_' . $key,
			'label'			=> __( 'Title horizontal padding (%)', 'divi-children-engine' ),
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
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
					'function'	=> 'css',
					'property'	=> 'padding-left',
					'units'		=> '% !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
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
			'settings'		=> 'dce_ccta_title_radius_' . $key,
			'label'			=> __( 'Title background border radius (px)', 'divi-children-engine' ),
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
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
					'function'	=> 'css',
					'property'	=> 'border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
					'function'	=> 'css',
					'property'	=> '-webkit-border-radius',
					'units'		=> 'px !important;',
				),
				array(
					'element'	=> '.' . $value . ' .et_pb_promo_description h2',
					'function'	=> 'css',
					'property'	=> '-moz-border-radius',
					'units'		=> 'px !important;',
				),
			),
			'active_callback' => $title_background_callback,
		) );


		/* Title bottom margin */
		Kirki::add_field( 'dce', array(
			'type'			=> 'slider',
			'settings'		=> 'dce_ccta_title_bottommargin_' . $key,
			'label'			=> __( 'Title bottom margin (px)', 'divi-children-engine' ),
			'section'		=> $this_section,
			'default'		=> 0,
			'priority'		=> 360,
			'choices'	=> array(
				'min'	=> 0,
				'max'	=> 200,
				'step'	=> 1,
			),
			'transport'		=> 'postMessage',
			'js_vars'		=> array(
				array(
					'element'		=> '.' . $value . ' .et_pb_promo_description h2',
					'function'		=> 'css',
					'property'		=> 'margin-bottom',
					'units'			=> 'px !important;',
				),
			),
		) );


	}

}

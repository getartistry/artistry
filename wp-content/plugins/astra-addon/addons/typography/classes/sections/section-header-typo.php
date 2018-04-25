<?php
/**
 * [Header] options for astra theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Site Title Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-site-title]', array(
			'default'           => astra_get_option( 'font-family-site-title' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-site-title]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-header-typo',
				'priority' => 7,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-site-title]',
			)
		)
	);

	/**
	 * Option: Site Title Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-site-title]', array(
			'default'           => astra_get_option( 'font-weight-site-title' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-site-title]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-header-typo',
				'priority' => 8,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-site-title]',
			)
		)
	);

	/**
	 * Option: Site Title Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-site-title]', array(
			'default'           => astra_get_option( 'text-transform-site-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-site-title]', array(
			'section'  => 'section-header-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 9,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Site Title Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-site-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-site-title]', array(
				'section'     => 'section-header-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 10,
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			)
		)
	);

	/**
	 * Option: Site Tagline Font Family
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-family-site-tagline]', array(
			'default'           => astra_get_option( 'font-family-site-tagline' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-family-site-tagline]', array(
				'type'     => 'ast-font-family',
				'label'    => __( 'Font Family', 'astra-addon' ),
				'section'  => 'section-header-typo',
				'priority' => 17,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-weight-site-tagline]',
			)
		)
	);

	/**
	 * Option: Site Tagline Font Weight
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-weight-site-tagline]', array(
			'default'           => astra_get_option( 'font-weight-site-tagline' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Typography(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-weight-site-tagline]', array(
				'type'     => 'ast-font-weight',
				'label'    => __( 'Font Weight', 'astra-addon' ),
				'section'  => 'section-header-typo',
				'priority' => 18,
				'connect'  => ASTRA_THEME_SETTINGS . '[font-family-site-tagline]',
			)
		)
	);

	/**
	 * Option: Site Tagline Text Transform
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[text-transform-site-tagline]', array(
			'default'           => astra_get_option( 'text-transform-site-tagline' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[text-transform-site-tagline]', array(
			'section'  => 'section-header-typo',
			'label'    => __( 'Text Transform', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 19,
			'choices'  => array(
				''           => __( 'Inherit', 'astra-addon' ),
				'none'       => __( 'None', 'astra-addon' ),
				'capitalize' => __( 'Capitalize', 'astra-addon' ),
				'uppercase'  => __( 'Uppercase', 'astra-addon' ),
				'lowercase'  => __( 'Lowercase', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Site Tagline Line Height
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[line-height-site-tagline]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[line-height-site-tagline]', array(
				'section'     => 'section-header-typo',
				'label'       => __( 'Line Height', 'astra-addon' ),
				'type'        => 'ast-slider',
				'priority'    => 20,
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 0.01,
					'max'  => 5,
				),
			)
		)
	);

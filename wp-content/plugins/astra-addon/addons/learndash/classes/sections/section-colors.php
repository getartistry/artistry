<?php
/**
 * LearnDash General Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-colors-divider]', array(
				'label'    => __( 'LearnDash Tables', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'type'     => 'ast-divider',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-table-heading-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-heading-color]', array(
				'label'    => __( 'Heading Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 10,
			)
		)
	);

	/**
	 * Option: Heading Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-table-heading-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-heading-bg-color]', array(
				'label'    => __( 'Heading Background Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 15,
			)
		)
	);

	/**
	 * Option: Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-table-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-title-color]', array(
				'label'    => __( 'Title Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 20,
			)
		)
	);

	/**
	 * Option: Title Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-table-title-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-title-bg-color]', array(
				'label'    => __( 'Title Background Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 25,
			)
		)
	);

	/**
	 * Option: Separator Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-table-title-separator-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-table-title-separator-color]', array(
				'label'    => __( 'Separator Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 30,
			)
		)
	);

	/**
	 * Option: Complete Icon Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-complete-icon-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-complete-icon-color]', array(
				'label'    => __( 'Complete Icon Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 35,
			)
		)
	);

	/**
	 * Option: Incomplete Icon Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[learndash-incomplete-icon-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[learndash-incomplete-icon-color]', array(
				'label'    => __( 'Incomplete Icon Color', 'astra-addon' ),
				'section'  => 'section-learndash-colors',
				'priority' => 40,
			)
		)
	);

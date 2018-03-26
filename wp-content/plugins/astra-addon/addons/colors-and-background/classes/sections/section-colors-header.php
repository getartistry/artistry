<?php
/**
 * Colors and Background - Header Options for our theme.
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

// Check Astra_Control_Color is exist in the theme.
if ( class_exists( 'Astra_Control_Color' ) ) {

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-bg-color]', array(
				'type'    => 'ast-color',
				'label'   => __( 'Background Color', 'astra-addon' ),
				'section' => 'section-colors-header',
			)
		)
	);
} else {

	/**
	 * Option: Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-bg-color]', array(
				'label'   => __( 'Background Color', 'astra-addon' ),
				'section' => 'section-colors-header',
			)
		)
	);

}

	/**
	 * Option: Site Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-color-site-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-site-title]', array(
				'label'   => __( 'Site Title Color', 'astra-addon' ),
				'section' => 'section-colors-header',
			)
		)
	);

	/**
	 * Option: Site Title Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-color-h-site-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-h-site-title]', array(
				'label'   => __( 'Site Title Hover Color', 'astra-addon' ),
				'section' => 'section-colors-header',
			)
		)
	);

	/**
	 * Option: Site Tagline Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-color-site-tagline]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-site-tagline]', array(
				'label'   => __( 'Site Tagline Color', 'astra-addon' ),
				'section' => 'section-colors-header',
			)
		)
	);

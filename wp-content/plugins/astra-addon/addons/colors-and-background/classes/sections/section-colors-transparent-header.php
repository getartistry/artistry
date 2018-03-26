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

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-bg-color]', array(
				'type'    => 'ast-color',
				'label'   => __( 'Background Overlay Color', 'astra-addon' ),
				'section' => 'section-colors-transparent-header',
			)
		)
	);

	/**
	 * Option: Site Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-color-site-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-color-site-title]', array(
				'label'   => __( 'Site Title Color', 'astra-addon' ),
				'section' => 'section-colors-transparent-header',
			)
		)
	);

	/**
	 * Option: Site Title Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-color-h-site-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-color-h-site-title]', array(
				'label'   => __( 'Site Title Hover Color', 'astra-addon' ),
				'section' => 'section-colors-transparent-header',
			)
		)
	);

	/**
	 * Option: Menu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-menu-bg-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-menu-bg-color]', array(
				'type'    => 'ast-color',
				'label'   => __( 'Menu Background Color', 'astra-addon' ),
				'section' => 'section-colors-transparent-header',
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-menu-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-menu-color]', array(
				'label'   => __( 'Menu Link / Text Color', 'astra-addon' ),
				'section' => 'section-colors-transparent-header',
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-menu-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-menu-h-color]', array(
				'label'   => __( 'Menu Link Active / Hover Color', 'astra-addon' ),
				'section' => 'section-colors-transparent-header',
			)
		)
	);

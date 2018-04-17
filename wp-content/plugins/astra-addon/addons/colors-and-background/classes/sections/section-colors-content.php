<?php
/**
 * Colors and Background - Content Options for our theme.
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


// Check Astra_Control_Background is exist in the theme.
/**
 * Option: Content Background Color
 */
if ( class_exists( 'Astra_Control_Background' ) ) {

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[content-bg-obj]', array(
			'default'           => astra_get_option( 'content-bg-obj' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_background_obj' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[content-bg-obj]', array(
				'type'    => 'ast-background',
				'label'   => __( 'Background', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);
} else {
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[content-bg-color]', array(
			'default'           => '#ffffff',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[content-bg-color]', array(
				'label'   => __( 'Content Background Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);
}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-bg-color]', array(
				'section'  => 'section-colors-content',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Heading 1 <h1> Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[h1-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[h1-color]', array(
				'label'   => __( 'Heading 1 (H1) Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);

	/**
	 * Option: Heading 2 <h2> Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[h2-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[h2-color]', array(
				'label'   => __( 'Heading 2 (H2) Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);

	/**
	 * Option: Heading 3 <h3> Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[h3-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[h3-color]', array(
				'label'   => __( 'Heading 3 (H3) Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);

	/**
	 * Option: Heading 4 <h4> Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[h4-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[h4-color]', array(
				'label'   => __( 'Heading 4 (H4) Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);

	/**
	 * Option: Heading 5 <h5> Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[h5-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[h5-color]', array(
				'label'   => __( 'Heading 5 (H5) Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);

	/**
	 * Option: Heading 6 <h6> Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[h6-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[h6-color]', array(
				'label'   => __( 'Heading 6 (H6) Color', 'astra-addon' ),
				'section' => 'section-colors-content',
			)
		)
	);

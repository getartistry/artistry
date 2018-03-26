<?php
/**
 * Scroll To Top Options for our theme.
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
	 * Option: Scroll to Top Display On
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-on-devices]', array(
			'default'           => astra_get_option( 'scroll-to-top-on-devices' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-on-devices]', array(
			'section' => 'section-scroll-to-top',
			'label'   => __( 'Display On', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
				'desktop' => __( 'Desktop', 'astra-addon' ),
				'mobile'  => __( 'Mobile', 'astra-addon' ),
				'both'    => __( 'Desktop + Mobile', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Scroll to Top Position
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-position]', array(
			'default'           => astra_get_option( 'scroll-to-top-icon-position' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-position]', array(
			'section' => 'section-scroll-to-top',
			'label'   => __( 'Position', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
				'right' => __( 'Right', 'astra-addon' ),
				'left'  => __( 'Left', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Scroll To Top Icon Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-size]', array(
			'default'           => astra_get_option( 'scroll-to-top-icon-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);

	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-size]', array(
			'section' => 'section-scroll-to-top',
			'label'   => __( 'Icon Size', 'astra-addon' ),
			'type'    => 'number',
		)
	);

	/**
	 * Option: Scroll To Top Radius
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-radius]', array(
			'default'           => astra_get_option( 'scroll-to-top-icon-radius' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);

	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-radius]', array(
			'section' => 'section-scroll-to-top',
			'label'   => __( 'Icon Background Radius', 'astra-addon' ),
			'type'    => 'number',
		)
	);

	/**
	 * Option: Icon Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-color]', array(
				'label'   => __( 'Icon Color', 'astra-addon' ),
				'section' => 'section-scroll-to-top',
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Icon Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Icon Background Color', 'astra-addon' ),
					'section' => 'section-scroll-to-top',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-bg-color]', array(
					'label'   => __( 'Icon Background Color', 'astra-addon' ),
					'section' => 'section-scroll-to-top',
				)
			)
		);
	}

	/**
	 * Option: Icon Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-color]', array(
				'label'   => __( 'Icon Hover Color', 'astra-addon' ),
				'section' => 'section-scroll-to-top',
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Link Hover Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Icon Hover Background Color', 'astra-addon' ),
					'section' => 'section-scroll-to-top',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[scroll-to-top-icon-h-bg-color]', array(
					'label'   => __( 'Icon Hover Background Color', 'astra-addon' ),
					'section' => 'section-scroll-to-top',
				)
			)
		);
	}

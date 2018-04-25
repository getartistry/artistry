<?php
/**
 * Colors and Background - Archive Options for our theme.
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[archive-summary-box-lable]', array(
				'label'    => __( 'Archive Summary Box', 'astra-addon' ),
				'section'  => 'section-colors-archive',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Archive Summary Box Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[archive-summary-box-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[archive-summary-box-bg-color]', array(
					'type'        => 'ast-color',
					'label'       => __( 'Background Color', 'astra-addon' ),
					'section'     => 'section-colors-archive',
					'description' => __( 'This background color will not work on Full-width layouts.', 'astra-addon' ),
				)
			)
		);
	} else {
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[archive-summary-box-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[archive-summary-box-bg-color]', array(
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-archive',
				)
			)
		);
	}


	/**
	 * Option: Archive Summary Box Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[archive-summary-box-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[archive-summary-box-title-color]', array(
				'label'   => __( 'Title Color', 'astra-addon' ),
				'section' => 'section-colors-archive',
			)
		)
	);

	/**
	 * Option: Archive Summary Box Description Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[archive-summary-box-text-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[archive-summary-box-text-color]', array(
				'label'   => __( 'Description Color', 'astra-addon' ),
				'section' => 'section-colors-archive',
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[archive-summary-box-divider]', array(
				'section'  => 'section-colors-archive',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Blog / Archive Post Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[page-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[page-title-color]', array(
				'label'   => __( 'Blog/Archive Post Title Color', 'astra-addon' ),
				'section' => 'section-colors-archive',
			)
		)
	);

	/**
	 * Option: Post Meta Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[post-meta-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[post-meta-color]', array(
				'label'   => __( 'Post Meta Color', 'astra-addon' ),
				'section' => 'section-colors-archive',
			)
		)
	);

	/**
	 * Option: Post Meta Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[post-meta-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[post-meta-link-color]', array(
				'label'   => __( 'Post Meta Link Color', 'astra-addon' ),
				'section' => 'section-colors-archive',
			)
		)
	);

	/**
	 * Option: Post Meta Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[post-meta-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[post-meta-link-h-color]', array(
				'label'   => __( 'Post Meta Link Hover Color', 'astra-addon' ),
				'section' => 'section-colors-archive',
			)
		)
	);

<?php
/**
 * Colors and Background - Sidebar Options for our theme.
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
	 * Option: Sidebar Background
	 */
if ( class_exists( 'Astra_Control_Background' ) ) {
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sidebar-bg-obj]', array(
			'default'           => astra_get_option( 'sidebar-bg-obj' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_background_obj' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sidebar-bg-obj]', array(
				'type'    => 'ast-background',
				'section' => 'section-colors-sidebar',
				'label'   => __( 'Background', 'astra-addon' ),
			)
		)
	);
}

	/**
	 * Option: Widget Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sidebar-widget-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sidebar-widget-title-color]', array(
				'label'   => __( 'Widget Title Color', 'astra-addon' ),
				'section' => 'section-colors-sidebar',
			)
		)
	);

	/**
	 * Option: Text Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sidebar-text-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sidebar-text-color]', array(
				'label'   => __( 'Text Color', 'astra-addon' ),
				'section' => 'section-colors-sidebar',
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sidebar-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sidebar-link-color]', array(
				'label'   => __( 'Link Color', 'astra-addon' ),
				'section' => 'section-colors-sidebar',
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sidebar-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sidebar-link-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
				'section' => 'section-colors-sidebar',
			)
		)
	);

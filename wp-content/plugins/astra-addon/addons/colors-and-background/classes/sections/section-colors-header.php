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
$defaults = Astra_Theme_Options::defaults();
	/**
	 * Option: Site Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-color-site-title]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-site-title]', array(
				'label'   => __( 'Site Title Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
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
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-h-site-title]', array(
				'label'   => __( 'Site Title Hover Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
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
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-color-site-tagline]', array(
				'label'   => __( 'Site Tagline Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-bg-obj-responsive]', array(
			'default'           => $defaults['header-bg-obj-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_background' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[header-bg-obj-responsive]', array(
				'type'    => 'ast-responsive-background',
				'label'   => __( 'Background', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

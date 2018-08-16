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

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-bg-color-responsive]', array(
			'default'           => $defaults['transparent-header-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Overlay Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Site Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-color-site-title-responsive]', array(
			'default'           => $defaults['transparent-header-color-site-title-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-color-site-title-responsive]', array(
				'label'      => __( 'Site Title Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Site Title Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-color-h-site-title-responsive]', array(
			'default'           => $defaults['transparent-header-color-h-site-title-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-color-h-site-title-responsive]', array(
				'label'      => __( 'Site Title Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-transparent-menu-responsive]', array(
				'label'    => __( 'Menu', 'astra-addon' ),
				'section'  => 'section-colors-transparent-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Menu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-menu-bg-color-responsive]', array(
			'default'           => $defaults['transparent-menu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-menu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-menu-color-responsive]', array(
			'default'           => $defaults['transparent-menu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-menu-color-responsive]', array(
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-menu-h-color-responsive]', array(
			'default'           => $defaults['transparent-menu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-menu-h-color-responsive]', array(
				'label'      => __( 'Link Active / Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-transparent-sub-menu-responsive]', array(
				'label'    => __( 'Submenu', 'astra-addon' ),
				'section'  => 'section-colors-transparent-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Sub menu background color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-submenu-bg-color-responsive]', array(
			'default'           => $defaults['transparent-submenu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-submenu-bg-color-responsive]', array(
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Sub menu text color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-submenu-color-responsive]', array(
			'default'           => $defaults['transparent-submenu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-submenu-color-responsive]', array(
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Sub menu active hover color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-submenu-h-color-responsive]', array(
			'default'           => $defaults['transparent-submenu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-submenu-h-color-responsive]', array(
				'label'      => __( 'Link Active / Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);


	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-transparent-content-section]', array(
				'label'    => __( 'Content Section', 'astra-addon' ),
				'section'  => 'section-colors-transparent-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);
	/**
	 * Option: Content Section Text color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-content-section-text-color-responsive]', array(
			'default'           => $defaults['transparent-content-section-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-content-section-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Text Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Content Section Link color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-content-section-link-color-responsive]', array(
			'default'           => $defaults['transparent-content-section-link-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-content-section-link-color-responsive]', array(
				'label'      => __( 'Link Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Content Section Link Hover color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-content-section-link-h-color-responsive]', array(
			'default'           => $defaults['transparent-content-section-link-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-content-section-link-h-color-responsive]', array(
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-transparent-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);


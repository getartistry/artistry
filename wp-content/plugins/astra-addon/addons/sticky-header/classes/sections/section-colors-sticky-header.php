<?php
/**
 * Colors and Background for Sticky header - Header Options for our theme.
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
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-color-divider]', array(
				'label'    => __( 'Header', 'astra-addon' ),
				'section'  => 'section-colors-sticky-primary-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-bg-color-responsive]', array(
			'default'           => $defaults['sticky-header-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Site Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-color-site-title-responsive]', array(
			'default'           => $defaults['sticky-header-color-site-title-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-color-site-title-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Site Title Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Site Title Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-color-h-site-title-responsive]', array(
			'default'           => $defaults['sticky-header-color-h-site-title-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-color-h-site-title-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Site Title Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);
	/**
	 * Option: Site Tagline Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-color-site-tagline-responsive]', array(
			'default'           => $defaults['sticky-header-color-site-tagline-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-color-site-tagline-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Site Tagline Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-menu-color-divider]', array(
				'label'    => __( 'Primary Menu', 'astra-addon' ),
				'section'  => 'section-colors-sticky-primary-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Menu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-menu-bg-color-responsive]', array(
			'default'           => $defaults['sticky-header-menu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-menu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-menu-color-responsive]', array(
			'default'           => $defaults['sticky-header-menu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-menu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-menu-h-color-responsive]', array(
			'default'           => $defaults['sticky-header-menu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-menu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Link / Hover Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-menu-h-a-bg-color-responsive]', array(
			'default'           => $defaults['sticky-header-menu-h-a-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-menu-h-a-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-submenu-color-divider]', array(
				'label'    => __( 'Primary Submenu', 'astra-addon' ),
				'section'  => 'section-colors-sticky-primary-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: SubMenu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-submenu-bg-color-responsive]', array(
			'default'           => $defaults['sticky-header-submenu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-submenu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-submenu-color-responsive]', array(
			'default'           => $defaults['sticky-header-submenu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-submenu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-submenu-h-color-responsive]', array(
			'default'           => $defaults['sticky-header-submenu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-submenu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: SubMenu Link / Hover Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-submenu-h-a-bg-color-responsive]', array(
			'default'           => $defaults['sticky-header-submenu-h-a-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-submenu-h-a-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-sticky-header-content-section]', array(
				'label'    => __( 'Outside menu item', 'astra-addon' ),
				'section'  => 'section-colors-sticky-primary-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);
	/**
	 * Option: Content Section Text color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-content-section-text-color-responsive]', array(
			'default'           => $defaults['sticky-header-content-section-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-content-section-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Text Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Content Section Link color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-content-section-link-color-responsive]', array(
			'default'           => $defaults['sticky-header-content-section-link-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-content-section-link-color-responsive]', array(
				'label'      => __( 'Link Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Content Section Link Hover color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-content-section-link-h-color-responsive]', array(
			'default'           => $defaults['sticky-header-content-section-link-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-content-section-link-h-color-responsive]', array(
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-primary-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

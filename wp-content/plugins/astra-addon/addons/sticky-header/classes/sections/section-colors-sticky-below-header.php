<?php
/**
 * Colors and Background for Sticky header - Below Header Options
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-color-divider]', array(
				'label'    => __( 'Below Header', 'astra-addon' ),
				'section'  => 'section-colors-sticky-below-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-bg-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-color-divider]', array(
				'label'    => __( 'Menu', 'astra-addon' ),
				'section'  => 'section-colors-sticky-below-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Menu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-bg-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-menu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-menu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-h-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-menu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);
	/**
	 * Option: Menu Link / Hover Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-h-a-bg-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-menu-h-a-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-h-a-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-color-divider]', array(
				'label'    => __( 'Submenu', 'astra-addon' ),
				'section'  => 'section-colors-sticky-below-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: SubMenu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-bg-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-submenu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-submenu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-h-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-submenu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: SubMenu Link / Hover Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-h-a-bg-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-submenu-h-a-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-h-a-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-sticky-below-header-content-section]', array(
				'label'    => __( 'Content Section', 'astra-addon' ),
				'section'  => 'section-colors-sticky-below-header',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);
	/**
	 * Option: Content Section Text color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-text-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-content-section-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Text Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Content Section Link color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-link-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-content-section-link-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-link-color-responsive]', array(
				'label'      => __( 'Link Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Content Section Link Hover color.
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-link-h-color-responsive]', array(
			'default'           => $defaults['sticky-below-header-content-section-link-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-link-h-color-responsive]', array(
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-sticky-below-header',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

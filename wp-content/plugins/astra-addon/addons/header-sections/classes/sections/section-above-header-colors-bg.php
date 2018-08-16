<?php
/**
 * Above Header Header Color Options for our theme.
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
	 * Option: Background
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-bg-obj-responsive]', array(
			'default'           => $defaults['above-header-bg-obj-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_background' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-bg-obj-responsive]', array(
				'type'    => 'ast-responsive-background',
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Background', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Above Header Menu Color Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-color-divider]', array(
				'label'    => __( 'Above Header Menu', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-above-header-colors-bg',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Menu Background Image, Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-bg-obj-responsive]', array(
			'default'           => $defaults['above-header-menu-bg-obj-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_background' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-bg-obj-responsive]', array(
				'type'    => 'ast-responsive-background',
				'label'   => __( 'Background', 'astra-addon' ),
				'section' => 'section-above-header-colors-bg',
			)
		)
	);
	/**
	 * Option: Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-color-responsive]', array(
			'default'           => $defaults['above-header-menu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-h-color-responsive]', array(
			'default'           => $defaults['above-header-menu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Hover Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color-responsive]', array(
			'default'           => $defaults['above-header-menu-h-bg-color-responsive'],
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Hover Background Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);


	/**
	 * Option: Menu Active Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-active-color-responsive]', array(
			'default'           => $defaults['above-header-menu-active-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-active-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Active Link Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Active Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color-responsive]', array(
			'default'           => $defaults['above-header-menu-active-bg-color-responsive'],
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Active Background Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-color-bg-dropdown-menu-divider]', array(
				'label'    => __( 'Above Header Submenu', 'astra-addon' ),
				'section'  => 'section-above-header-colors-bg',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color-responsive]', array(
			'default'           => $defaults['above-header-submenu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-text-color-responsive]', array(
			'default'           => $defaults['above-header-submenu-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);


	/**
	 * Option: Submenu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-hover-color-responsive]', array(
			'default'           => $defaults['above-header-submenu-hover-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Hover Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color-responsive]', array(
			'default'           => $defaults['above-header-submenu-bg-hover-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Hover Background Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Active Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-active-color-responsive]', array(
			'default'           => $defaults['above-header-submenu-active-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-active-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Active Link Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Active Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color-responsive]', array(
			'default'           => $defaults['above-header-submenu-active-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Active Background Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Border
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-border]', array(
			'default'           => astra_get_option( 'above-header-submenu-border' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-border]', array(
			'type'    => 'checkbox',
			'section' => 'section-above-header-colors-bg',
			'label'   => __( 'Enable Border', 'astra-addon' ),
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Border Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-border-color]', array(
			'default'           => $defaults['above-header-submenu-border-color'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-border-color]', array(
				'type'    => 'ast-color',
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Border Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Above Header Content Color Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-content-color-divider]', array(
				'label'    => __( 'Content Section', 'astra-addon' ),
				'type'     => 'ast-divider',
				'section'  => 'section-above-header-colors-bg',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Text Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-text-color-responsive]', array(
			'default'           => $defaults['above-header-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Text Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-link-color-responsive]', array(
			'default'           => $defaults['above-header-link-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-link-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Link Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-link-h-color-responsive]', array(
			'default'           => $defaults['above-header-link-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-link-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-above-header-colors-bg',
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

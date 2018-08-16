<?php
/**
 * Below Header - Colors Options for our theme.
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
		ASTRA_THEME_SETTINGS . '[below-header-bg-obj-responsive]', array(
			'default'           => $defaults['below-header-bg-obj-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_background' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-bg-obj-responsive]', array(
				'type'    => 'ast-responsive-background',
				'section' => 'section-below-header-colors-bg',
				'label'   => __( 'Background', 'astra-addon' ),
			)
		)
	);

	/**
	 * Below Header Navigation Colors
	 */
	/**
	 * Option: Below Header Menu Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-color-bg-primary-menu-divider]', array(
				'label'    => __( 'Below Header Menu', 'astra-addon' ),
				'section'  => 'section-below-header-colors-bg',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Menu Background Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-menu-bg-obj-responsive]', array(
			'default'           => $defaults['below-header-menu-bg-obj-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_background' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-menu-bg-obj-responsive]', array(
				'type'    => 'ast-responsive-background',
				'label'   => __( 'Background', 'astra-addon' ),
				'section' => 'section-below-header-colors-bg',
			)
		)
	);

	/**
	 * Option: Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-menu-text-color-responsive]', array(
			'default'           => $defaults['below-header-menu-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-menu-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
				'label'      => __( 'Link Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-menu-text-hover-color-responsive]', array(
			'default'           => $defaults['below-header-menu-text-hover-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-menu-text-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-menu-bg-hover-color-responsive]', array(
			'default'           => $defaults['below-header-menu-bg-hover-color-responsive'],
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-menu-bg-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
				'label'      => __( 'Hover Background Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Active Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-current-menu-text-color-responsive]', array(
			'default'           => $defaults['below-header-current-menu-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-current-menu-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
				'label'      => __( 'Active Link Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Active Menu Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-current-menu-bg-color-responsive]', array(
			'default'           => $defaults['below-header-current-menu-bg-color-responsive'],
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-current-menu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-color-bg-dropdown-menu-divider]', array(
				'label'    => __( 'Below Header Submenu', 'astra-addon' ),
				'section'  => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-submenu-bg-color-responsive]', array(
			'default'           => $defaults['below-header-submenu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-submenu-text-color-responsive]', array(
			'default'           => $defaults['below-header-submenu-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
				'label'      => __( 'Link Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-submenu-hover-color-responsive]', array(
			'default'           => $defaults['below-header-submenu-hover-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-submenu-bg-hover-color-responsive]', array(
			'default'           => $defaults['below-header-submenu-bg-hover-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-bg-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-submenu-active-color-responsive]', array(
			'default'           => $defaults['below-header-submenu-active-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-active-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-submenu-active-bg-color-responsive]', array(
			'default'           => $defaults['below-header-submenu-active-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-active-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-submenu-border]', array(
			'default'           => astra_get_option( 'below-header-submenu-border' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[below-header-submenu-border]', array(
			'type'    => 'checkbox',
			'section' => 'section-below-header-colors-bg',
			'label'   => __( 'Enable Border', 'astra-addon' ),
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Border Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-submenu-border-color]', array(
			'default'           => $defaults['below-header-submenu-border-color'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-submenu-border-color]', array(
				'type'    => 'ast-color',
				'section' => 'section-below-header-colors-bg',
				'label'   => __( 'Border Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Content Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-color-bg-content-divider]', array(
				'label'    => __( 'Content Section', 'astra-addon' ),
				'section'  => 'section-below-header-colors-bg',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Text Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[below-header-text-color-responsive]', array(
			'default'           => $defaults['below-header-text-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-text-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-link-color-responsive]', array(
			'default'           => $defaults['below-header-link-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-link-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
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
		ASTRA_THEME_SETTINGS . '[below-header-link-hover-color-responsive]', array(
			'default'           => $defaults['below-header-link-hover-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[below-header-link-hover-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'section'    => 'section-below-header-colors-bg',
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

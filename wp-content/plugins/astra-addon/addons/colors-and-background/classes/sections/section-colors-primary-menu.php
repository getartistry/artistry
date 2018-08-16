<?php
/**
 * Colors and Background - Primary Menu Options for our theme.
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-primary-menu]', array(
				'label'    => __( 'Primary Menu', 'astra-addon' ),
				'section'  => 'section-colors-primary-menu',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Menu Background image , color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-bg-obj-responsive]', array(
			'default'           => $defaults['primary-menu-bg-obj-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_background' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-bg-obj-responsive]', array(
				'type'    => 'ast-responsive-background',
				'label'   => __( 'Background', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-color-responsive]', array(
			'default'           => $defaults['primary-menu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-h-color-responsive]', array(
			'default'           => $defaults['primary-menu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Menu Hover Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color-responsive]', array(
			'default'           => $defaults['primary-menu-h-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Hover Background Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Active Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-a-color-responsive]', array(
			'default'           => $defaults['primary-menu-a-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-a-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Active Link Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Active Menu Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color-responsive]', array(
			'default'           => $defaults['primary-menu-a-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Active Background Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-primary-sub-menu]', array(
				'label'    => __( 'Primary Submenu', 'astra-addon' ),
				'section'  => 'section-colors-primary-menu',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Submenu Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color-responsive]', array(
			'default'           => $defaults['primary-submenu-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Background Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);


	/**
	 * Option: Submenu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-color-responsive]', array(
			'default'           => $defaults['primary-submenu-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link / Text Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-h-color-responsive]', array(
			'default'           => $defaults['primary-submenu-h-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-h-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Link Hover Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Submenu Hover Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color-responsive]', array(
			'default'           => $defaults['primary-submenu-h-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Hover Background Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);


	/**
	 * Option: Active Submenu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-a-color-responsive]', array(
			'default'           => $defaults['primary-submenu-a-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-a-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Active Link Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Active Submenu Background Color
	 */

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color-responsive]', array(
			'default'           => $defaults['primary-submenu-a-bg-color-responsive'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_responsive_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color-responsive]', array(
				'type'       => 'ast-responsive-color',
				'label'      => __( 'Active Background Color', 'astra-addon' ),
				'section'    => 'section-colors-primary-menu',
				'responsive' => true,
				'rgba'       => true,
			)
		)
	);

	/**
	 * Option: Primary Menu Border
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-border]', array(
			'default'           => astra_get_option( 'primary-submenu-border' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[primary-submenu-border]', array(
			'type'    => 'checkbox',
			'section' => 'section-colors-primary-menu',
			'label'   => __( 'Enable Border', 'astra-addon' ),
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Border Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]', array(
				'default'           => $defaults['primary-submenu-b-color'],
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Border Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]', array(
				'default'           => $defaults['primary-submenu-b-color'],
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]', array(
					'label'   => __( 'Border Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

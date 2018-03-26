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

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-primary-menu]', array(
				'label'    => __( 'Menu', 'astra-addon' ),
				'section'  => 'section-colors-primary-menu',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-menu-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-menu-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-bg-color]', array(
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

	/**
	 * Option: Primary Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-color]', array(
				'label'   => __( 'Link / Text Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Hover Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color]', array(
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}


	/**
	 * Option: Active Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-menu-a-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-a-color]', array(
				'label'   => __( 'Active Link Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);


	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Active Menu Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Active Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color]', array(
					'label'   => __( 'Active Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-primary-sub-menu]', array(
				'label'    => __( 'Submenu', 'astra-addon' ),
				'section'  => 'section-colors-primary-menu',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color]', array(
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

	/**
	 * Option: Submenu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-color]', array(
				'label'   => __( 'Link / Text Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	/**
	 * Option: Submenu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Hover Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color]', array(
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

	/**
	 * Option: Active Submenu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[primary-submenu-a-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-a-color]', array(
				'label'   => __( 'Active Link Color', 'astra-addon' ),
				'section' => 'section-colors-primary-menu',
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Active Submenu Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Active Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color]', array(
					'label'   => __( 'Active Background Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

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
				'default'           => '',
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
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]', array(
					'label'   => __( 'Border Color', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				)
			)
		);
	}

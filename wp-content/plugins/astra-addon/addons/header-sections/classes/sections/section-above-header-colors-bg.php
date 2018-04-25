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
	/**
	 * Option: Background
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-bg-obj]', array(
			'default'           => astra_get_option( 'above-header-bg-obj' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_background_obj' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-bg-obj]', array(
				'type'    => 'ast-background',
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
	 * Option: Menu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Link / Text Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Menu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-h-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Hover Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color]', array(
					'type'    => 'ast-color',
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-h-bg-color]', array(
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
				)
			)
		);
	}


	/**
	 * Option: Menu Active Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-menu-active-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-active-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Active Link Color', 'astra-addon' ),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Active Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color]', array(
					'type'    => 'ast-color',
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Active Background Color', 'astra-addon' ),
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-menu-active-bg-color]', array(
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Active Background Color', 'astra-addon' ),
				)
			)
		);
	}

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
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color]', array(
					'type'    => 'ast-color',
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Background Color', 'astra-addon' ),
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-color]', array(
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Background Color', 'astra-addon' ),
				)
			)
		);
	}

	/**
	 * Option: Submenu Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-text-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-text-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Link / Text Color', 'astra-addon' ),
			)
		)
	);


	/**
	 * Option: Submenu Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-hover-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-hover-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Menu Hover Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color]', array(
					'type'    => 'ast-color',
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-bg-hover-color]', array(
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Hover Background Color', 'astra-addon' ),
				)
			)
		);
	}

	/**
	 * Option: Submenu Active Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-submenu-active-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-active-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Active Link Color', 'astra-addon' ),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	/**
	 * Option: Submenu Active Background Color
	 */
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color]', array(
					'type'    => 'ast-color',
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Active Background Color', 'astra-addon' ),
				)
			)
		);
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-active-bg-color]', array(
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Active Background Color', 'astra-addon' ),
				)
			)
		);
	}

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
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-border-color]', array(
				'default'           => '',
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
	} else {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[above-header-submenu-border-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-submenu-border-color]', array(
					'section' => 'section-above-header-colors-bg',
					'label'   => __( 'Border Color', 'astra-addon' ),
				)
			)
		);
	}

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
		ASTRA_THEME_SETTINGS . '[above-header-text-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-text-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Text Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-link-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Link Color', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[above-header-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[above-header-link-h-color]', array(
				'section' => 'section-above-header-colors-bg',
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
			)
		)
	);

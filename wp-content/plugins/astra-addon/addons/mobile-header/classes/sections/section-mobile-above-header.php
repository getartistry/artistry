<?php
/**
 * Astra Mobile Header.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Mobile Menu Style Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-header-above-header-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-above-header',
				'settings' => array(),
				'priority' => 102,
			)
		)
	);

	/**
	 * Option: Mobile Menu Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-style]', array(
			'default'           => astra_get_option( 'mobile-above-header-menu-style' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-style]', array(
			'section'  => 'section-above-header',
			'label'    => __( 'Menu Style', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 102,
			'choices'  => array(
				'default'    => __( 'Dropdown', 'astra-addon' ),
				'flyout'     => __( 'Flyout', 'astra-addon' ),
				'fullscreen' => __( 'Full-Screen', 'astra-addon' ),
				'no-toggle'  => __( 'No Toggle', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Mobile Menu Style - Flyout alignments
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[flyout-mobile-above-header-menu-alignment]', array(
			'default'           => astra_get_option( 'flyout-mobile-above-header-menu-alignment' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[flyout-mobile-above-header-menu-alignment]', array(
			'section'  => 'section-above-header',
			'label'    => __( 'Flyout Menu Alignment', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 102,
			'choices'  => array(
				'left'  => __( 'Left', 'astra-addon' ),
				'right' => __( 'Right', 'astra-addon' ),
			),
		)
	);

	/**
	* Option: Mobile Menu Style Divider
	*/
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-above-header-main-menu-style-divider]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-above-header',
				'priority' => 103,
				'settings' => array(),
			)
		)
	);
	/**
	* Option: Toggle Button Style
	*/
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style]', array(
			'default'           => astra_get_option( 'mobile-above-header-toggle-btn-style' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style]', array(
			'section'  => 'section-above-header',
			'label'    => __( 'Toggle Button Style', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 103,
			'choices'  => array(
				'fill'    => __( 'Fill', 'astra-addon' ),
				'outline' => __( 'Outline', 'astra-addon' ),
				'minimal' => __( 'Minimal', 'astra-addon' ),

			),
		)
	);
	/**
	* Option: Toggle Button Color
	*/
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style-color]', array(
			'default'           => astra_get_option( 'mobile-above-header-toggle-btn-style-color' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-style-color]', array(
				'type'     => 'ast-color',
				'label'    => __( 'Toggle Button Color', 'astra-addon' ),
				'section'  => 'section-above-header',
				'priority' => 103,
			)
		)
	);
	/**
	* Option: Border Radius
	*/
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-border-radius]', array(
			'default'           => astra_get_option( 'mobile-above-header-toggle-btn-border-radius' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-above-header-toggle-btn-border-radius]', array(
				'type'        => 'ast-slider',
				'section'     => 'section-above-header',
				'label'       => __( 'Border Radius', 'astra-addon' ),
				'priority'    => 103,
				'suffix'      => '',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
			)
		)
	);

	/**
	 * Option: Mobile Header Menu Border
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-all-border]', array(
			'default'           => astra_get_option( 'mobile-above-header-menu-all-border' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Addon_Customizer', 'sanitize_border' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Border(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-all-border]', array(
				'type'           => 'ast-border',
				'section'        => 'section-above-header',
				'label'          => __( 'Border for Menu Items', 'astra-addon' ),
				'linked_choices' => true,
				'priority'       => 125,
				'choices'        => array(
					'top'    => __( 'Top', 'astra-addon' ),
					'right'  => __( 'Right', 'astra-addon' ),
					'bottom' => __( 'Bottom', 'astra-addon' ),
					'left'   => __( 'Left', 'astra-addon' ),
				),
			)
		)
	);

	/**
	 * Option: Mobile Header Menu Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-b-color]', array(
			'default'           => '#dadada',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Color(
			$wp_customize, ASTRA_THEME_SETTINGS . '[mobile-above-header-menu-b-color]', array(
				'label'    => __( 'Border Color', 'astra-addon' ),
				'section'  => 'section-above-header',
				'priority' => 130,
			)
		)
	);

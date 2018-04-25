<?php
/**
 * Transparent Header Options for our theme.
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
	 * Option: Transparent header logo selector
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-logo]', array(
			'default'           => astra_get_option( 'transparent-header-logo' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-logo]', array(
				'section'        => 'section-transparent-header',
				'priority'       => 5,
				'label'          => __( 'Logo', 'astra-addon' ),
				'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			)
		)
	);

	/**
	 * Option: Transparent header logo selector
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-retina-logo]', array(
			'default'           => astra_get_option( 'transparent-header-retina-logo' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-retina-logo]', array(
				'section'        => 'section-transparent-header',
				'priority'       => 10,
				'label'          => __( 'Retina Logo', 'astra-addon' ),
				'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			)
		)
	);

	/**
	 * Option: Transparent header logo width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-logo-width]', array(
			'default'           => astra_get_option( 'transparent-header-logo-width' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-logo-width]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'section-transparent-header',
				'priority'    => 15,
				'label'       => __( 'Logo Width', 'astra-addon' ),
				'input_attrs' => array(
					'min'  => 50,
					'step' => 1,
					'max'  => 600,
				),
			)
		)
	);

	/**
	 * Option: Enable Transparent Header
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-enable]', array(
			'default'           => astra_get_option( 'transparent-header-enable' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[transparent-header-enable]', array(
			'section'  => 'section-transparent-header',
			'label'    => __( 'Enable on Complete Website', 'astra-addon' ),
			'priority' => 20,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Disable Transparent Header on Archive Pages
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-disable-archive]', array(
			'default'           => astra_get_option( 'transparent-header-disable-archive' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[transparent-header-disable-archive]', array(
			'section'     => 'section-transparent-header',
			'label'       => __( 'Force Disable on Special Pages?', 'astra-addon' ),
			'description' => __( 'This setting is generally not recommended on special pages such as archive, search, 404, etc. If you would like to enable it, uncheck this option', 'astra-addon' ),
			'priority'    => 25,
			'type'        => 'checkbox',
		)
	);

	/**
	 * Option: Bottom Border Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-main-sep]', array(
			'default'           => astra_get_option( 'transparent-header-main-sep' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[transparent-header-main-sep]', array(
			'type'        => 'number',
			'section'     => 'section-transparent-header',
			'priority'    => 25,
			'label'       => __( 'Bottom Border Size', 'astra-addon' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
		)
	);

	/**
	 * Option: Bottom Border Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[transparent-header-main-sep-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[transparent-header-main-sep-color]', array(
				'section'  => 'section-transparent-header',
				'priority' => 30,
				'label'    => __( 'Bottom Border Color', 'astra-addon' ),
			)
		)
	);

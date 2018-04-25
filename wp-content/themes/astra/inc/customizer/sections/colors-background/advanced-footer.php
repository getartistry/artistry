<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Widget Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-color]', array(
				'label'   => __( 'Widget Title Color', 'astra' ),
				'section' => 'section-footer-adv-color-bg',
			)
		)
	);

	/**
	 * Option: Text Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-text-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-text-color]', array(
				'label'   => __( 'Text Color', 'astra' ),
				'section' => 'section-footer-adv-color-bg',
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-link-color]', array(
				'label'   => __( 'Link Color', 'astra' ),
				'section' => 'section-footer-adv-color-bg',
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-link-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra' ),
				'section' => 'section-footer-adv-color-bg',
			)
		)
	);


	/**
	 * Option: Background Color
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-background-divider]', array(
				'section'  => 'section-footer-adv-color-bg',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Footer widget Background
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-obj]', array(
			'default'           => astra_get_option( 'footer-adv-bg-obj' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_background_obj' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-bg-obj]', array(
				'type'    => 'ast-background',
				'section' => 'section-footer-adv-color-bg',
				'label'   => __( 'Background', 'astra' ),
			)
		)
	);

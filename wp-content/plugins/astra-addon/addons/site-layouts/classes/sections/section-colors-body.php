<?php
/**
 * Styling Options for Astra Theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
				'label'   => __( 'Link Color', 'astra-addon' ),
				'section' => 'section-footer-adv-color-bg',
			)
		)
	);

	/**
	 * Option: Background Image
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-img]', array(
			'default'           => astra_get_option( 'site-layout-padded-bg-img' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-img]', array(
				'section'  => 'section-colors-body',
				'priority' => 35,
				'label'    => __( 'Background Image', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Background Image - Repeat
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-rep]', array(
			'default'           => astra_get_option( 'site-layout-padded-bg-rep' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-rep]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 40,
			'label'    => __( 'Background Repeat', 'astra-addon' ),
			'choices'  => array(
				'no-repeat' => __( 'No Repeat', 'astra-addon' ),
				'repeat'    => __( 'Repeat All', 'astra-addon' ),
				'repeat-x'  => __( 'Repeat Horizontally', 'astra-addon' ),
				'repeat-y'  => __( 'Repeat Vertically', 'astra-addon' ),
				'inherit'   => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image - Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-size]', array(
			'default'           => astra_get_option( 'site-layout-padded-bg-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-size]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 45,
			'label'    => __( 'Background Size', 'astra-addon' ),
			'choices'  => array(
				'contain' => __( 'Contain', 'astra-addon' ),
				'cover'   => __( 'Cover', 'astra-addon' ),
				'initial' => __( 'Initial', 'astra-addon' ),
				'inherit' => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image - Position
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-pos]', array(
			'default'           => astra_get_option( 'site-layout-padded-bg-pos' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-padded-bg-pos]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 50,
			'label'    => __( 'Background Position', 'astra-addon' ),
			'choices'  => array(
				'left-top'      => __( 'Left Top', 'astra-addon' ),
				'left-center'   => __( 'Left Center', 'astra-addon' ),
				'left bottom'   => __( 'Left Bottom', 'astra-addon' ),
				'center-top'    => __( 'Center Top', 'astra-addon' ),
				'center-center' => __( 'Center Center', 'astra-addon' ),
				'center-bottom' => __( 'Center Bottom', 'astra-addon' ),
				'right-top'     => __( 'Right Top', 'astra-addon' ),
				'right-center'  => __( 'Right Center', 'astra-addon' ),
				'right-bottom'  => __( 'Right Bottom', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-img]', array(
			'default'           => astra_get_option( 'site-layout-box-bg-img' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-box-bg-img]', array(
				'section'  => 'section-colors-body',
				'priority' => 55,
				'label'    => __( 'Background Image', 'astra-addon' ),
			)
		)
	);

	/**
	 * Option: Background Image - Repeat
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-rep]', array(
			'default'           => astra_get_option( 'site-layout-box-bg-rep' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-rep]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 60,
			'label'    => __( 'Background Repeat', 'astra-addon' ),
			'choices'  => array(
				'no-repeat' => __( 'No Repeat', 'astra-addon' ),
				'repeat'    => __( 'Repeat All', 'astra-addon' ),
				'repeat-x'  => __( 'Repeat Horizontally', 'astra-addon' ),
				'repeat-y'  => __( 'Repeat Vertically', 'astra-addon' ),
				'inherit'   => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image - Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-size]', array(
			'default'           => astra_get_option( 'site-layout-box-bg-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-size]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 65,
			'label'    => __( 'Background Size', 'astra-addon' ),
			'choices'  => array(
				'contain' => __( 'Contain', 'astra-addon' ),
				'cover'   => __( 'Cover', 'astra-addon' ),
				'initial' => __( 'Initial', 'astra-addon' ),
				'inherit' => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image - Attachment
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-atch]', array(
			'default'           => astra_get_option( 'site-layout-box-bg-atch' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-atch]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 70,
			'label'    => __( 'Background Attachment', 'astra-addon' ),
			'choices'  => array(
				'fixed'   => __( 'Fixed', 'astra-addon' ),
				'scroll'  => __( 'Scroll', 'astra-addon' ),
				'inherit' => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image - Position
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-pos]', array(
			'default'           => astra_get_option( 'site-layout-box-bg-pos' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-layout-box-bg-pos]', array(
			'type'     => 'select',
			'section'  => 'section-colors-body',
			'priority' => 75,
			'label'    => __( 'Background Position', 'astra-addon' ),
			'choices'  => array(
				'left-top'      => __( 'Left Top', 'astra-addon' ),
				'left-center'   => __( 'Left Center', 'astra-addon' ),
				'left-bottom'   => __( 'Left Bottom', 'astra-addon' ),
				'center-top'    => __( 'Center Top', 'astra-addon' ),
				'center-center' => __( 'Center Center', 'astra-addon' ),
				'center-bottom' => __( 'Center Bottom', 'astra-addon' ),
				'right-top'     => __( 'Right Top', 'astra-addon' ),
				'right-center'  => __( 'Right Center', 'astra-addon' ),
				'right-bottom'  => __( 'Right Bottom', 'astra-addon' ),
			),
		)
	);

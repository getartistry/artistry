<?php
/**
 * Colors and Background - Footer Options for our theme.
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
	 * Option: Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-color]', array(
				'label'   => __( 'Text Color', 'astra-addon' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Link Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-link-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-link-color]', array(
				'label'   => __( 'Link Color', 'astra-addon' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Link Hover Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-link-h-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-link-h-color]', array(
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-footer-image]', array(
				'section'  => 'section-colors-footer',
				'type'     => 'ast-divider',
				'settings' => array(),
			)
		)
	);

	// Check Astra_Control_Color is exist in the theme.
	if ( class_exists( 'Astra_Control_Color' ) ) {

		/**
		 * Option: Background Color
		 */
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-footer',
				)
			)
		);

	} else {

		/**
		 * Option: Background Color
		 */
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-bg-color]', array(
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-colors-footer',
				)
			)
		);

		/**
		 * Option: Background Color Opacity
		 */
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-bg-color-opc]', array(
				'default'           => '0.8',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Slider(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-bg-color-opc]', array(
					'type'        => 'ast-slider',
					'label'       => __( 'Background Color Opacity', 'astra-addon' ),
					'section'     => 'section-colors-footer',
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 0,
						'step' => .05,
						'max'  => 1,
					),
				)
			)
		);
	}


	/**
	 * Option: Background Image
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-bg-img]', array(
			'default'           => astra_get_option( 'footer-bg-img' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-bg-img]', array(
				'label'   => __( 'Background Image', 'astra-addon' ),
				'section' => 'section-colors-footer',
			)
		)
	);

	/**
	 * Option: Background Image - Repeat
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-bg-rep]', array(
			'default'           => astra_get_option( 'footer-bg-rep' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-bg-rep]', array(
			'section' => 'section-colors-footer',
			'label'   => __( 'Background Repeat', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
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
		ASTRA_THEME_SETTINGS . '[footer-bg-size]', array(
			'default'           => astra_get_option( 'footer-bg-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-bg-size]', array(
			'section' => 'section-colors-footer',
			'label'   => __( 'Background Size', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
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
		ASTRA_THEME_SETTINGS . '[footer-bg-pos]', array(
			'default'           => astra_get_option( 'footer-bg-pos' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-bg-pos]', array(
			'section' => 'section-colors-footer',
			'label'   => __( 'Background Position', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
				'left top'      => __( 'Left Top', 'astra-addon' ),
				'left center'   => __( 'Left Center', 'astra-addon' ),
				'left bottom'   => __( 'Left Bottom', 'astra-addon' ),
				'center top'    => __( 'Center Top', 'astra-addon' ),
				'center center' => __( 'Center Center', 'astra-addon' ),
				'center bottom' => __( 'Center Bottom', 'astra-addon' ),
				'right top'     => __( 'Right Top', 'astra-addon' ),
				'right center'  => __( 'Right Center', 'astra-addon' ),
				'right bottom'  => __( 'Right Bottom', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Image - Attachment
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-bg-atch]', array(
			'default'           => astra_get_option( 'footer-bg-atch' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-bg-atch]', array(
			'section' => 'section-colors-footer',
			'label'   => __( 'Background Attachment', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
				'fixed'   => __( 'Fixed', 'astra-addon' ),
				'scroll'  => __( 'Scroll', 'astra-addon' ),
				'inherit' => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

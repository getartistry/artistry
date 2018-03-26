<?php
/**
 * Footer Widgets Options for our theme.
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
				'label'   => __( 'Widget Title Color', 'astra-addon' ),
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
				'label'   => __( 'Text Color', 'astra-addon' ),
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
				'label'   => __( 'Link Color', 'astra-addon' ),
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
				'label'   => __( 'Link Hover Color', 'astra-addon' ),
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

	// Check Astra_Control_Color is exist in the theme.
	if ( class_exists( 'Astra_Control_Color' ) ) {

		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Color(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]', array(
					'type'    => 'ast-color',
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-footer-adv-color-bg',
				)
			)
		);
	} else {
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]', array(
				'default'           => '',
				'type'              => 'option',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-bg-color]', array(
					'label'   => __( 'Background Color', 'astra-addon' ),
					'section' => 'section-footer-adv-color-bg',
				)
			)
		);


		/**
		* Option: Background Color Opacity
		*/
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-adv-bg-color-opac]', array(
				'default'           => '0.8',
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
			)
		);

		$wp_customize->add_control(
			new Astra_Control_Slider(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-bg-color-opac]', array(
					'type'        => 'ast-slider',
					'label'       => __( 'Background Color Opacity', 'astra-addon' ),
					'section'     => 'section-footer-adv-color-bg',
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
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-img]', array(
			'default'           => astra_get_option( 'footer-adv-bg-img' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-bg-img]', array(
				'section'        => 'section-footer-adv-color-bg',
				'label'          => __( 'Background Image', 'astra-addon' ),
				'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			)
		)
	);

	/**
	 * Option: Background Repeat
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-repeat]', array(
			'default'           => astra_get_option( 'footer-adv-bg-repeat' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-repeat]', array(
			'section' => 'section-footer-adv-color-bg',
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
	 * Option: Background Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-size]', array(
			'default'           => astra_get_option( 'footer-adv-bg-size' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-size]', array(
			'section' => 'section-footer-adv-color-bg',
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
	 * Option: Background Attachment
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-attac]', array(
			'default'           => astra_get_option( 'footer-adv-bg-attac' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-attac]', array(
			'section' => 'section-footer-adv-color-bg',
			'label'   => __( 'Background Attachment', 'astra-addon' ),
			'type'    => 'select',
			'choices' => array(
				'fixed'   => __( 'Fixed', 'astra-addon' ),
				'scroll'  => __( 'Scroll', 'astra-addon' ),
				'inherit' => __( 'Inherit', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Background Position
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-pos]', array(
			'default'           => astra_get_option( 'footer-adv-bg-pos' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-bg-pos]', array(
			'section' => 'section-footer-adv-color-bg',
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

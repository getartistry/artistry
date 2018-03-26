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
	 * Option: Footer Widgets Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv]', array(
			'default'           => astra_get_option( 'footer-adv' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv]', array(
				'type'    => 'ast-radio-image',
				'label'   => __( 'Footer Widgets Layout', 'astra-addon' ),
				'section' => 'section-footer-adv',
				'choices' => array(
					'disabled' => array(
						'label' => __( 'Disable', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/no-adv-footer-115x48.png',
					),
					'layout-1' => array(
						'label' => __( 'Layout 1', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-1-115x48.png',
					),
					'layout-2' => array(
						'label' => __( 'Layout 2', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-2-115x48.png',
					),
					'layout-3' => array(
						'label' => __( 'Layout 3', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-3-115x48.png',
					),
					'layout-4' => array(
						'label' => __( 'Layout 4', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-4-115x48.png',
					),
					'layout-5' => array(
						'label' => __( 'Layout 5', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-5-115x48.png',
					),
					'layout-6' => array(
						'label' => __( 'Layout 6', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-6-115x48.png',
					),
					'layout-7' => array(
						'label' => __( 'Layout 7', 'astra-addon' ),
						'path'  => ASTRA_EXT_ADVANCED_FOOTER_URL . '/assets/images/layout-7-115x48.png',
					),
				),
			)
		)
	);

	/**
	 * Footer Widgets Padding
	 *
	 * @since 1.2.0 Updated to support responsive spacing param
	 */
	// Astra_Control_Responsive_Spacing introduced in Astra 1.2.0.
	// If found older version then do not load any settings from customizer.
	if ( version_compare( ASTRA_THEME_VERSION, '1.2.0', '>=' ) ) {
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[footer-adv-area-padding]', array(
				'default'           => astra_get_option( 'footer-adv-area-padding' ),
				'type'              => 'option',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			)
		);
		$wp_customize->add_control(
			new Astra_Control_Responsive_Spacing(
				$wp_customize, ASTRA_THEME_SETTINGS . '[footer-adv-area-padding]', array(
					'type'           => 'ast-responsive-spacing',
					'section'        => 'section-footer-adv',
					'label'          => __( 'Footer Widgets Padding', 'astra-addon' ),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				)
			)
		);
	}

	/**
	 * Option: Footer Widgets Width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[footer-adv-layout-width]', array(
			'default'           => astra_get_option( 'footer-adv-layout-width' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[footer-adv-layout-width]', array(
			'type'     => 'select',
			'section'  => 'section-footer-adv',
			'priority' => 35,
			'label'    => __( 'Footer Widgets Width', 'astra-addon' ),
			'choices'  => array(
				'full'    => __( 'Full Width', 'astra-addon' ),
				'content' => __( 'Content Width', 'astra-addon' ),
			),
		)
	);

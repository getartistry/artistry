<?php
/**
 * Sticky Header Options for our theme.
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
 * Option: Stick Above Header
 */
if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-above-stick]', array(
			'default'           => astra_get_option( 'header-above-stick' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-above-stick]', array(
			'section'  => 'section-sticky-header',
			'label'    => __( 'Stick Above Header Section', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);
}

	/**
	 * Option: Stick Primary Header
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-stick]', array(
			'default'           => astra_get_option( 'header-main-stick' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-stick]', array(
			'section'  => 'section-sticky-header',
			'label'    => __( 'Stick Primary Header', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Stick Below Header
	 */
	if ( Astra_Ext_Extension::is_active( 'header-sections' ) ) {
		$wp_customize->add_setting(
			ASTRA_THEME_SETTINGS . '[header-below-stick]', array(
				'default'           => astra_get_option( 'header-below-stick' ),
				'type'              => 'option',
				'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
			)
		);
		$wp_customize->add_control(
			ASTRA_THEME_SETTINGS . '[header-below-stick]', array(
				'section'  => 'section-sticky-header',
				'label'    => __( 'Stick Below Header Section', 'astra-addon' ),
				'priority' => 15,
				'type'     => 'checkbox',
			)
		);
	}

	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[different-sticky-logo]', array(
			'default'           => astra_get_option( 'different-sticky-logo' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[different-sticky-logo]', array(
			'section'  => 'section-sticky-header',
			'label'    => __( 'Different Logo for Sticky Header?', 'astra-addon' ),
			'priority' => 15,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Sticky header logo selector
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-logo]', array(
			'default'           => astra_get_option( 'sticky-header-logo' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-logo]', array(
				'section'        => 'section-sticky-header',
				'priority'       => 15,
				'label'          => __( 'Sticky Logo', 'astra-addon' ),
				'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			)
		)
	);

	/**
	 * Option: Different retina logo
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[different-sticky-retina-logo]', array(
			'default'           => false,
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[different-sticky-retina-logo]', array(
			'section'  => 'section-sticky-header',
			'label'    => __( 'Different Logo for retina devices?', 'astra-addon' ),
			'priority' => 20,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Sticky header logo selector
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-retina-logo]', array(
			'default'           => astra_get_option( 'sticky-header-retina-logo' ),
			'type'              => 'option',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-retina-logo]', array(
				'section'        => 'section-sticky-header',
				'priority'       => 20,
				'label'          => __( 'Sticky Retina Logo', 'astra-addon' ),
				'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
			)
		)
	);

	/**
	 * Option: Sticky header logo width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-logo-width]', array(
			'default'           => astra_get_option( 'sticky-header-logo-width' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Slider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[sticky-header-logo-width]', array(
				'type'        => 'ast-responsive-slider',
				'section'     => 'section-sticky-header',
				'priority'    => 25,
				'label'       => __( 'Sticky Logo Width', 'astra-addon' ),
				'input_attrs' => array(
					'min'  => 50,
					'step' => 1,
					'max'  => 600,
				),
			)
		)
	);

	/**
	 * Option: Shrink Primary Header
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[header-main-shrink]', array(
			'default'           => astra_get_option( 'header-main-shrink' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[header-main-shrink]', array(
			'section'  => 'section-sticky-header',
			'label'    => __( 'Enable Shrink Effect', 'astra-addon' ),
			'priority' => 35,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable disable mobile header
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-style]', array(
			'default'           => astra_get_option( 'sticky-header-style' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[sticky-header-style]', array(
			'type'     => 'select',
			'section'  => 'section-sticky-header',
			'priority' => 40,
			'label'    => __( 'Select Animation Effect', 'astra-addon' ),
			'choices'  => array(
				'none'  => __( 'None', 'astra-addon' ),
				'slide' => __( 'Slide', 'astra-addon' ),
				'fade'  => __( 'Fade', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Hide on scroll
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-hide-on-scroll]', array(
			'default'           => astra_get_option( 'sticky-hide-on-scroll' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[sticky-hide-on-scroll]', array(
			'section'  => 'section-sticky-header',
			'label'    => __( 'Hide when scrolling down', 'astra-addon' ),
			'priority' => 45,
			'type'     => 'checkbox',
		)
	);


	/**
	 * Option: Sticky Header Display On
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[sticky-header-on-devices]', array(
			'default'           => astra_get_option( 'sticky-header-on-devices' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[sticky-header-on-devices]', array(
			'section'  => 'section-sticky-header',
			'priority' => 50,
			'label'    => __( 'Enable On', 'astra-addon' ),
			'type'     => 'select',
			'choices'  => array(
				'desktop' => __( 'Desktop', 'astra-addon' ),
				'mobile'  => __( 'Mobile', 'astra-addon' ),
				'both'    => __( 'Desktop + Mobile', 'astra-addon' ),
			),
		)
	);


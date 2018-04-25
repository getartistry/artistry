<?php
/**
 * General Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-content-layout-divider]', array(
				'type'     => 'ast-divider',
				'priority' => 50,
				'section'  => 'section-container-layout',
				'settings' => array(),
			)
		)
	);
	/**
	 * Option: Site Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-content-layout]', array(
			'default'           => astra_get_option( 'site-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[site-content-layout]', array(
			'type'     => 'select',
			'section'  => 'section-container-layout',
			'priority' => 50,
			'label'    => __( 'Default Container', 'astra' ),
			'choices'  => array(
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Single Page Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-page-content-layout]', array(
			'default'           => astra_get_option( 'single-page-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-page-content-layout]', array(
			'type'     => 'select',
			'section'  => 'section-container-layout',
			'label'    => __( 'Container for Pages', 'astra' ),
			'priority' => 55,
			'choices'  => array(
				'default'                 => __( 'Default', 'astra' ),
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Single Post Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-post-content-layout]', array(
			'default'           => astra_get_option( 'single-post-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-post-content-layout]', array(
			'type'     => 'select',
			'section'  => 'section-container-layout',
			'priority' => 60,
			'label'    => __( 'Container for Blog Posts', 'astra' ),
			'choices'  => array(
				'default'                 => __( 'Default', 'astra' ),
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	/**
	 * Option: Archive Post Content Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[archive-post-content-layout]', array(
			'default'           => astra_get_option( 'archive-post-content-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[archive-post-content-layout]', array(
			'type'     => 'select',
			'section'  => 'section-container-layout',
			'priority' => 65,
			'label'    => __( 'Container for Blog Archives', 'astra' ),
			'choices'  => array(
				'default'                 => __( 'Default', 'astra' ),
				'boxed-container'         => __( 'Boxed', 'astra' ),
				'content-boxed-container' => __( 'Content Boxed', 'astra' ),
				'plain-container'         => __( 'Full Width / Contained', 'astra' ),
				'page-builder'            => __( 'Full Width / Stretched', 'astra' ),
			),
		)
	);

	// Learn More link if Astra Pro is not activated.
	if ( ! defined( 'ASTRA_EXT_VER' ) ) {

		/**
		 * Option: Divider
		 */
		$wp_customize->add_control(
			new Astra_Control_Divider(
				$wp_customize, ASTRA_THEME_SETTINGS . '[ast-container-more-feature-divider]', array(
					'type'     => 'ast-divider',
					'section'  => 'section-container-layout',
					'priority' => 70,
					'settings' => array(),
				)
			)
		);
		/**
		 * Option: Learn More about Container
		 */
		$wp_customize->add_control(
			new Astra_Control_Description(
				$wp_customize, ASTRA_THEME_SETTINGS . '[ast-container-more-feature-description]', array(
					'type'     => 'ast-description',
					'section'  => 'section-container-layout',
					'priority' => 70,
					'label'    => '',
					'help'     => '<p>' . __( 'More Options Available for Container in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/site-layout-overview/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
					'settings' => array(),
				)
			)
		);
	}

	/**
	 * Option: Body Background
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[site-layout-outside-bg-obj]', array(
			'default'           => astra_get_option( 'site-layout-outside-bg-obj' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_background_obj' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Background(
			$wp_customize, ASTRA_THEME_SETTINGS . '[site-layout-outside-bg-obj]', array(
				'type'     => 'ast-background',
				'section'  => 'section-colors-body',
				'priority' => 25,
				'label'    => __( 'Background', 'astra' ),
			)
		)
	);

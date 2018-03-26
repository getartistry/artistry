<?php
/**
 * Typography Options for Astra Theme.
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
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-header-single-title]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-single-typo',
				'priority' => 5,
				'label'    => __( 'Single Post / Page Title', 'astra' ),
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Single Post / Page Title Font Size
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[font-size-entry-title]', array(
			'default'           => astra_get_option( 'font-size-entry-title' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_typo' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Responsive(
			$wp_customize, ASTRA_THEME_SETTINGS . '[font-size-entry-title]', array(
				'type'        => 'ast-responsive',
				'section'     => 'section-single-typo',
				'priority'    => 10,
				'label'       => __( 'Font Size', 'astra' ),
				'input_attrs' => array(
					'min' => 0,
				),
				'units'       => array(
					'px' => 'px',
					'em' => 'em',
				),
			)
		)
	);

	// Learn More link if Astra Pro is not activated.
	if ( ! defined( 'ASTRA_EXT_VER' ) ) {

		/**
		 * Option: Divider
		 */
		$wp_customize->add_control(
			new Astra_Control_Divider(
				$wp_customize, ASTRA_THEME_SETTINGS . '[ast-sngle-blog-typography-more-feature-divider]', array(
					'type'     => 'ast-divider',
					'section'  => 'section-single-typo',
					'priority' => 15,
					'settings' => array(),
				)
			)
		);
		/**
		 * Option: Learn More about Typography
		 */
		$wp_customize->add_control(
			new Astra_Control_Description(
				$wp_customize, ASTRA_THEME_SETTINGS . '[ast-sngle-blog-typography-more-feature-description]', array(
					'type'     => 'ast-description',
					'section'  => 'section-single-typo',
					'priority' => 15,
					'label'    => '',
					'help'     => '<p>' . __( 'More Options Available for Typography in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/typography-module/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
					'settings' => array(),
				)
			)
		);
	}

<?php
/**
 * Colors and Background - Single Options for our theme.
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
	 * Option: Single Post / Page Title Color
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[entry-title-color]', array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_hex_color' ),
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize, ASTRA_THEME_SETTINGS . '[entry-title-color]', array(
				'label'    => __( 'Single Post/Page Title Color', 'astra-addon' ),
				'section'  => 'section-colors-single',
				'priority' => 5,
			)
		)
	);

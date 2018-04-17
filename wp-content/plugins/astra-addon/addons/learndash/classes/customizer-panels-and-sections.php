<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

	/**
	 * Section
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-learndash-colors',
			array(
				'priority' => 65,
				'title'    => __( 'LearnDash LMS', 'astra-addon' ),
				'panel'    => 'panel-colors-background',
			)
		)
	);

	/**
	 * Section
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-learndash-typo',
			array(
				'priority' => 65,
				'title'    => __( 'LearnDash LMS', 'astra-addon' ),
				'panel'    => 'panel-typography',
			)
		)
	);

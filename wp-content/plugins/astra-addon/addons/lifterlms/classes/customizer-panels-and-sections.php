<?php
/**
 * Register customizer panels & sections.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.0.0
 */

	/**
	 * General Section
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-lifterlms-general',
			array(
				'priority' => 0,
				'title'    => __( 'General', 'astra-addon' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-lifterlms',
			)
		)
	);

	/**
	 * Course / Lesson Section
	 */
	$wp_customize->add_section(
		new Astra_WP_Customize_Section(
			$wp_customize, 'section-lifterlms-course-lesson',
			array(
				'priority' => 5,
				'title'    => __( 'Course / Lesson', 'astra-addon' ),
				'panel'    => 'panel-layout',
				'section'  => 'section-lifterlms',
			)
		)
	);

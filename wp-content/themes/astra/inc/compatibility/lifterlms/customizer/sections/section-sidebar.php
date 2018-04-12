<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[lifterlms-course-lesson-sidebar-layout-divider]', array(
				'section'  => 'section-sidebars',
				'type'     => 'ast-divider',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Shop Page
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-sidebar-layout]', array(
			'default'           => astra_get_option( 'lifterlms-sidebar-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-sidebar-layout]', array(
			'type'     => 'select',
			'section'  => 'section-sidebars',
			'priority' => 5,
			'label'    => __( 'LifterLMS', 'astra' ),
			'choices'  => array(
				'default'       => __( 'Default', 'astra' ),
				'no-sidebar'    => __( 'No Sidebar', 'astra' ),
				'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
				'right-sidebar' => __( 'Right Sidebar', 'astra' ),
			),
		)
	);

	/**
	 * Option: LifterLMS Course/Lesson
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-course-lesson-sidebar-layout]', array(
			'default'           => astra_get_option( 'lifterlms-course-lesson-sidebar-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-course-lesson-sidebar-layout]', array(
			'type'     => 'select',
			'section'  => 'section-sidebars',
			'priority' => 5,
			'label'    => __( 'LifterLMS Course/Lesson', 'astra' ),
			'choices'  => array(
				'default'       => __( 'Default', 'astra' ),
				'no-sidebar'    => __( 'No Sidebar', 'astra' ),
				'left-sidebar'  => __( 'Left Sidebar', 'astra' ),
				'right-sidebar' => __( 'Right Sidebar', 'astra' ),
			),
		)
	);


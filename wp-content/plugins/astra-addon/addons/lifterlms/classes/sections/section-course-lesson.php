<?php
/**
 * LifterLMS Learning Options for our theme.
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
	 * Option: Student
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[lifterlms-enable-student-divider]', array(
				'section'  => 'section-lifterlms-course-lesson',
				'label'    => __( 'Student View', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 5,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Distraction Free Learning
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-distraction-free-learning]', array(
			'default'           => astra_get_option( 'lifterlms-distraction-free-learning' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-distraction-free-learning]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Distraction Free Learning', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Featured Image
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-featured-image]', array(
			'default'           => astra_get_option( 'lifterlms-enable-featured-image' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-featured-image]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Featured Image', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Course Description
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-course-description]', array(
			'default'           => astra_get_option( 'lifterlms-enable-course-description' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-course-description]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Course Description', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Course Meta
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-course-meta]', array(
			'default'           => astra_get_option( 'lifterlms-enable-course-meta' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-course-meta]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Course Meta', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Instructor Detail
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-instructor-detail]', array(
			'default'           => astra_get_option( 'lifterlms-enable-instructor-detail' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-instructor-detail]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Instructor Detail', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Progress Bar
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-progress-bar]', array(
			'default'           => astra_get_option( 'lifterlms-enable-progress-bar' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-progress-bar]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Progress Bar', 'astra-addon' ),
			'priority' => 5,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Visitors
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-divider]', array(
				'section'  => 'section-lifterlms-course-lesson',
				'label'    => __( 'Vistor View', 'astra-addon' ),
				'type'     => 'ast-divider',
				'priority' => 10,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Enable Featured Image
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-featured-image]', array(
			'default'           => astra_get_option( 'lifterlms-enable-visitor-featured-image' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-featured-image]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Featured Image', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Course Description
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-course-description]', array(
			'default'           => astra_get_option( 'lifterlms-enable-visitor-course-description' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-course-description]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Course Description', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Course Meta
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-course-meta]', array(
			'default'           => astra_get_option( 'lifterlms-enable-visitor-course-meta' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-course-meta]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Course Meta', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Instructor Detail
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-instructor-detail]', array(
			'default'           => astra_get_option( 'lifterlms-enable-visitor-instructor-detail' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-instructor-detail]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Instructor Detail', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

	/**
	 * Option: Enable Syllabus
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-syllabus]', array(
			'default'           => astra_get_option( 'lifterlms-enable-visitor-syllabus' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[lifterlms-enable-visitor-syllabus]', array(
			'section'  => 'section-lifterlms-course-lesson',
			'label'    => __( 'Enable Syllabus', 'astra-addon' ),
			'priority' => 10,
			'type'     => 'checkbox',
		)
	);

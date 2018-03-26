<?php
/**
 * Blog Pro Options for our theme.
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

	$wp_customize->add_control(
		new Astra_Control_Sortable(
			$wp_customize, ASTRA_THEME_SETTINGS . '[blog-single-meta]', array(
				'type'     => 'ast-sortable',
				'section'  => 'section-blog-single',
				'priority' => 5,
				'label'    => __( 'Single Post Meta', 'astra-addon' ),
				'choices'  => array(
					'comments'  => __( 'Comments', 'astra-addon' ),
					'category'  => __( 'Category', 'astra-addon' ),
					'author'    => __( 'Author', 'astra-addon' ),
					'date'      => __( 'Publish Date', 'astra-addon' ),
					'tag'       => __( 'Tag', 'astra-addon' ),
					'read-time' => __( 'Read Time', 'astra-addon' ),
				),
			)
		)
	);

	/**
	 * Option: Author info
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[ast-author-info]', array(
			'default'           => astra_get_option( 'ast-author-info' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[ast-author-info]', array(
			'section' => 'section-blog-single',
			'label'   => __( 'Author Info', 'astra-addon' ),
			'type'    => 'checkbox',
		)
	);

	/**
	 * Option: Autoposts
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[ast-auto-prev-post]', array(
			'default'           => astra_get_option( 'ast-auto-prev-post' ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[ast-auto-prev-post]', array(
			'section'     => 'section-blog-single',
			'label'       => __( 'Auto Load Previous Posts', 'astra-addon' ),
			'type'        => 'checkbox',
			'description' => __( 'Auto Load Previous Posts cannot be previewed in the Customizer.', 'astra-addon' ),
		)
	);

	/**
	 * Option: Remove feature image padding
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[single-featured-image-padding]', array(
			'default'           => astra_get_option( 'single-featured-image-padding' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[single-featured-image-padding]', array(
			'type'        => 'checkbox',
			'section'     => 'section-blog-single',
			'label'       => __( 'Remove Featured Image Padding', 'astra-addon' ),
			'description' => __( 'This option will not work on full width layouts.', 'astra-addon' ),
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[divider-section-single-featured-image-padding]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-blog-single',
				'settings' => array(),
			)
		)
	);

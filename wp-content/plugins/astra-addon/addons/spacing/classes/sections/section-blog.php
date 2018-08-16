<?php
/**
 * Blog Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option - Blog Spacing divider
 */
$wp_customize->add_control(
	new Astra_Control_Heading(
		$wp_customize, ASTRA_THEME_SETTINGS . '[blog-post-spacing-divider]', array(
			'section'  => 'section-blog',
			'type'     => 'ast-heading',
			'label'    => __( 'Spacing', 'astra-addon' ),
			'priority' => 125,
			'settings' => array(),
		)
	)
);

/**
 * Option: Post Outside Spacing
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[blog-post-outside-spacing]', array(
		'default'           => astra_get_option( 'blog-post-outside-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[blog-post-outside-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-blog',
			'priority'       => 130,
			'label'          => __( 'Space Outside Post', 'astra-addon' ),
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

/**
 * Option: Post Inside Spacing
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[blog-post-inside-spacing]', array(
		'default'           => astra_get_option( 'blog-post-inside-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[blog-post-inside-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-blog',
			'priority'       => 135,
			'label'          => __( 'Space Inside Post', 'astra-addon' ),
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


/**
 * Option: Post Pagination Spacing
 */
$wp_customize->add_setting(
	ASTRA_THEME_SETTINGS . '[blog-post-pagination-spacing]', array(
		'default'           => astra_get_option( 'blog-post-pagination-spacing' ),
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
	)
);
$wp_customize->add_control(
	new Astra_Control_Responsive_Spacing(
		$wp_customize, ASTRA_THEME_SETTINGS . '[blog-post-pagination-spacing]', array(
			'type'           => 'ast-responsive-spacing',
			'section'        => 'section-blog',
			'priority'       => 90,
			'label'          => __( 'Post Pagination Space', 'astra-addon' ),
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

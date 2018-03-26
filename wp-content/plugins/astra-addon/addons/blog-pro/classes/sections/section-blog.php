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
			$wp_customize, ASTRA_THEME_SETTINGS . '[blog-meta]', array(
				'type'     => 'ast-sortable',
				'section'  => 'section-blog',
				'priority' => 105,
				'label'    => __( 'Blog Meta', 'astra-addon' ),
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
	 * Option: Blog Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-layout]', array(
			'default'           => astra_get_option( 'blog-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		new Astra_Control_Radio_Image(
			$wp_customize, ASTRA_THEME_SETTINGS . '[blog-layout]', array(
				'section'  => 'section-blog',
				'label'    => __( 'Blog Layout', 'astra-addon' ),
				'type'     => 'ast-radio-image',
				'priority' => 5,
				'choices'  => array(
					'blog-layout-1' => array(
						'label' => __( 'Blog Layout 1', 'astra-addon' ),
						'path'  => ASTRA_EXT_BLOG_PRO_URI . 'assets/images/blog-layout-1-76x48.png',
					),
					'blog-layout-2' => array(
						'label' => __( 'Blog Layout 2', 'astra-addon' ),
						'path'  => ASTRA_EXT_BLOG_PRO_URI . 'assets/images/blog-layout-2-76x48.png',
					),
					'blog-layout-3' => array(
						'label' => __( 'Blog Layout 3', 'astra-addon' ),
						'path'  => ASTRA_EXT_BLOG_PRO_URI . 'assets/images/blog-layout-3-76x48.png',
					),
				),
			)
		)
	);

	/**
	 * Option: Grid Layout
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-grid]', array(
			'default'           => astra_get_option( 'blog-grid' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-grid]', array(
			'section'  => 'section-blog',
			'label'    => __( 'Grid Layout', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 10,
			'choices'  => array(
				'1' => __( '1 Column', 'astra-addon' ),
				'2' => __( '2 Columns', 'astra-addon' ),
				'3' => __( '3 Columns', 'astra-addon' ),
				'4' => __( '4 Columns', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Grid Layout for Blog Layout 2 & 3
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-grid-layout]', array(
			'default'           => astra_get_option( 'blog-grid-layout' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);

	/**
	 * Option: Space Between Post
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-space-bet-posts]', array(
			'default'           => astra_get_option( 'blog-space-bet-posts' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-space-bet-posts]', array(
			'type'     => 'checkbox',
			'section'  => 'section-blog',
			'label'    => __( 'Add Space Between Posts', 'astra-addon' ),
			'priority' => 15,
		)
	);

	/**
	 * Option: Masonry Effect
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-masonry]', array(
			'default'           => astra_get_option( 'blog-masonry' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-masonry]', array(
			'type'     => 'checkbox',
			'section'  => 'section-blog',
			'label'    => __( 'Masonry Effect', 'astra-addon' ),
			'priority' => 20,
		)
	);

	/**
	 * Option: First Post full width
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[first-post-full-width]', array(
			'default'           => astra_get_option( 'first-post-full-width' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[first-post-full-width]', array(
			'type'        => 'checkbox',
			'section'     => 'section-blog',
			'label'       => __( 'Highlight First Post', 'astra-addon' ),
			'description' => __( 'This will not work if Masonry Effect is enabled.', 'astra-addon' ),
			'priority'    => 25,
		)
	);

	/**
	 * Option: Disable Date Box
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-date-box]', array(
			'default'           => astra_get_option( 'blog-date-box' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-date-box]', array(
			'type'     => 'checkbox',
			'section'  => 'section-blog',
			'label'    => __( 'Enable Date Box', 'astra-addon' ),
			'priority' => 30,
		)
	);

	/**
	 * Option: Date Box Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-date-box-style]', array(
			'default'           => astra_get_option( 'blog-date-box-style' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-date-box-style]', array(
			'section'  => 'section-blog',
			'label'    => __( 'Date Box Style', 'astra-addon' ),
			'type'     => 'select',
			'priority' => 35,
			'choices'  => array(
				'square' => __( 'Square', 'astra-addon' ),
				'circle' => __( 'Circle', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Remove feature image padding
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-featured-image-padding]', array(
			'default'           => astra_get_option( 'blog-featured-image-padding' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-featured-image-padding]', array(
			'type'        => 'checkbox',
			'section'     => 'section-blog',
			'label'       => __( 'Remove Featured Image Padding', 'astra-addon' ),
			'description' => __( 'This option will not work on full width layouts.', 'astra-addon' ),
			'priority'    => 40,
		)
	);
	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-grid]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-blog',
				'priority' => 45,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Excerpt Count
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-excerpt-count]', array(
			'default'           => astra_get_option( 'blog-excerpt-count' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-excerpt-count]', array(
			'type'        => 'number',
			'section'     => 'section-blog',
			'priority'    => 55,
			'label'       => __( 'Excerpt Count', 'astra-addon' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 3000,
			),
		)
	);

	/**
	 * Option: Read more text
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-read-more-text]', array(
			'default'           => astra_get_option( 'blog-read-more-text' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-read-more-text]', array(
			'section'  => 'section-blog',
			'priority' => 60,
			'label'    => __( 'Read more text', 'astra-addon' ),
			'type'     => 'text',
		)
	);

	/**
	 * Option: Display read more as button
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-read-more-as-button]', array(
			'default'           => astra_get_option( 'blog-read-more-as-button' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_checkbox' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-read-more-as-button]', array(
			'type'     => 'checkbox',
			'section'  => 'section-blog',
			'label'    => __( 'Display Read More as Button', 'astra-addon' ),
			'priority' => 65,
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-meta]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-blog',
				'priority' => 70,
				'settings' => array(),
			)
		)
	);

	/**
	 * Option: Post Pagination
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-pagination]', array(
			'default'           => astra_get_option( 'blog-pagination' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-pagination]', array(
			'type'     => 'select',
			'section'  => 'section-blog',
			'priority' => 75,
			'label'    => __( 'Post Pagination', 'astra-addon' ),
			'choices'  => array(
				'number'   => __( 'Number', 'astra-addon' ),
				'infinite' => __( 'Infinite Scroll', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Post Pagination Style
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-pagination-style]', array(
			'default'           => astra_get_option( 'blog-pagination-style' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-pagination-style]', array(
			'type'     => 'select',
			'section'  => 'section-blog',
			'priority' => 80,
			'label'    => __( 'Post Pagination Style', 'astra-addon' ),
			'choices'  => array(
				'default' => __( 'Default', 'astra-addon' ),
				'square'  => __( 'Square', 'astra-addon' ),
				'circle'  => __( 'Circle', 'astra-addon' ),
			),
		)
	);
	/**
	 * Option: Event to Trigger Infinite Loading
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-infinite-scroll-event]', array(
			'default'           => astra_get_option( 'blog-infinite-scroll-event' ),
			'type'              => 'option',
			'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_choices' ),
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-infinite-scroll-event]', array(
			'type'        => 'select',
			'section'     => 'section-blog',
			'description' => __( 'Infinite Scroll cannot be previewed in the Customizer.', 'astra-addon' ),
			'priority'    => 85,
			'label'       => __( 'Event to Trigger Infinite Loading', 'astra-addon' ),
			'choices'     => array(
				'scroll' => __( 'Scroll', 'astra-addon' ),
				'click'  => __( 'Click', 'astra-addon' ),
			),
		)
	);

	/**
	 * Option: Read more text
	 */
	$wp_customize->add_setting(
		ASTRA_THEME_SETTINGS . '[blog-load-more-text]', array(
			'default'           => astra_get_option( 'blog-load-more-text' ),
			'type'              => 'option',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		ASTRA_THEME_SETTINGS . '[blog-load-more-text]', array(
			'section'  => 'section-blog',
			'priority' => 90,
			'label'    => __( 'Load More Text', 'astra-addon' ),
			'type'     => 'text',
		)
	);

	/**
	 * Option: Divider
	 */
	$wp_customize->add_control(
		new Astra_Control_Divider(
			$wp_customize, ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-pagination]', array(
				'type'     => 'ast-divider',
				'section'  => 'section-blog',
				'priority' => 95,
				'settings' => array(),
			)
		)
	);

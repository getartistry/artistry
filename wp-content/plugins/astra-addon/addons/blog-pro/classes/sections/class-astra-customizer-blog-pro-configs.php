<?php
/**
 * Blog Pro General Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Blog_Pro_Configs' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Blog_Pro_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Display Post Meta
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-meta]',
					'type'     => 'control',
					'control'  => 'ast-sortable',
					'section'  => 'section-blog',
					'default'  => astra_get_option( 'blog-meta' ),
					'priority' => 105,
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-post-structure]', 'contains', 'title-meta' ),
					'title'    => __( 'Blog Meta', 'astra-addon' ),
					'choices'  => array(
						'comments'  => __( 'Comments', 'astra-addon' ),
						'category'  => __( 'Category', 'astra-addon' ),
						'author'    => __( 'Author', 'astra-addon' ),
						'date'      => __( 'Publish Date', 'astra-addon' ),
						'tag'       => __( 'Tag', 'astra-addon' ),
						'read-time' => __( 'Read Time', 'astra-addon' ),
					),
				),

				/**
				 * Option: Blog Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-layout]',
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'section'  => 'section-blog',
					'default'  => astra_get_option( 'blog-layout' ),
					'priority' => 5,
					'title'    => __( 'Blog Layout', 'astra-addon' ),
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
				),

				/**
				 * Option: Grid Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-grid]',
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-blog',
					'default'  => astra_get_option( 'blog-grid' ),
					'priority' => 10,
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-layout]', '===', 'blog-layout-1' ),
					'title'    => __( 'Grid Layout', 'astra-addon' ),
					'choices'  => array(
						'1' => __( '1 Column', 'astra-addon' ),
						'2' => __( '2 Columns', 'astra-addon' ),
						'3' => __( '3 Columns', 'astra-addon' ),
						'4' => __( '4 Columns', 'astra-addon' ),
					),
				),

				/**
				 * Option: Space Between Post
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-space-bet-posts]',
					'default'  => astra_get_option( 'blog-space-bet-posts' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-blog',
					'title'    => __( 'Add Space Between Posts', 'astra-addon' ),
					'priority' => 15,
				),

				/**
				 * Option: Masonry Effect
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-masonry]',
					'default'  => astra_get_option( 'blog-masonry' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-blog',
					'title'    => __( 'Masonry Effect', 'astra-addon' ),
					'priority' => 20,
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[blog-layout]', '===', 'blog-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[blog-grid]', '!=', 1 ),
						),
					),

				),

				/**
				 * Option: First Post full width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[first-post-full-width]',
					'default'     => astra_get_option( 'first-post-full-width' ),
					'type'        => 'control',
					'control'     => 'checkbox',
					'section'     => 'section-blog',
					'title'       => __( 'Highlight First Post', 'astra-addon' ),
					'description' => __( 'This will not work if Masonry Effect is enabled.', 'astra-addon' ),
					'priority'    => 25,
					'required'    => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[blog-layout]', '===', 'blog-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[blog-grid]', '!=', 1 ),
						),
					),
				),

				/**
				 * Option: Disable Date Box
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-date-box]',
					'default'  => astra_get_option( 'blog-date-box' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-blog',
					'title'    => __( 'Enable Date Box', 'astra-addon' ),
					'priority' => 30,
				),

				/**
				 * Option: Date Box Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-date-box-style]',
					'default'  => astra_get_option( 'blog-date-box-style' ),
					'type'     => 'control',
					'section'  => 'section-blog',
					'title'    => __( 'Date Box Style', 'astra-addon' ),
					'control'  => 'select',
					'priority' => 35,
					'choices'  => array(
						'square' => __( 'Square', 'astra-addon' ),
						'circle' => __( 'Circle', 'astra-addon' ),
					),
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-date-box]', '===', true ),
				),

				/**
				 * Option: Remove feature image padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[blog-featured-image-padding]',
					'default'     => astra_get_option( 'blog-featured-image-padding' ),
					'type'        => 'control',
					'control'     => 'checkbox',
					'section'     => 'section-blog',
					'title'       => __( 'Remove Featured Image Padding', 'astra-addon' ),
					'description' => __( 'This option will not work on full width layouts.', 'astra-addon' ),
					'priority'    => 40,
					'required'    => array( ASTRA_THEME_SETTINGS . '[blog-layout]', '===', 'blog-layout-1' ),
				),
				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-grid]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog',
					'priority' => 45,
					'settings' => array(),
				),

				/**
				 * Option: Excerpt Count
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[blog-excerpt-count]',
					'default'     => astra_get_option( 'blog-excerpt-count' ),
					'type'        => 'control',
					'control'     => 'number',
					'section'     => 'section-blog',
					'priority'    => 55,
					'title'       => __( 'Excerpt Count', 'astra-addon' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 3000,
					),
					'required'    => array( ASTRA_THEME_SETTINGS . '[blog-post-content]', '===', 'excerpt' ),
				),

				/**
				 * Option: Read more text
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-read-more-text]',
					'default'  => astra_get_option( 'blog-read-more-text' ),
					'type'     => 'control',
					'section'  => 'section-blog',
					'priority' => 60,
					'title'    => __( 'Read more text', 'astra-addon' ),
					'control'  => 'text',
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-post-content]', '===', 'excerpt' ),
				),

				/**
				 * Option: Display read more as button
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-read-more-as-button]',
					'default'  => astra_get_option( 'blog-read-more-as-button' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-blog',
					'title'    => __( 'Display Read More as Button', 'astra-addon' ),
					'priority' => 65,
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-post-content]', '===', 'excerpt' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-meta]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog',
					'priority' => 70,
					'settings' => array(),
				),

				/**
				 * Option: Post Pagination
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-pagination]',
					'default'  => astra_get_option( 'blog-pagination' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-blog',
					'priority' => 75,
					'title'    => __( 'Post Pagination', 'astra-addon' ),
					'choices'  => array(
						'number'   => __( 'Number', 'astra-addon' ),
						'infinite' => __( 'Infinite Scroll', 'astra-addon' ),
					),
				),

				/**
				 * Option: Post Pagination Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-pagination-style]',
					'default'  => astra_get_option( 'blog-pagination-style' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-blog',
					'priority' => 80,
					'title'    => __( 'Post Pagination Style', 'astra-addon' ),
					'choices'  => array(
						'default' => __( 'Default', 'astra-addon' ),
						'square'  => __( 'Square', 'astra-addon' ),
						'circle'  => __( 'Circle', 'astra-addon' ),
					),
					'required' => array( ASTRA_THEME_SETTINGS . '[blog-pagination]', '===', 'number' ),
				),
				/**
				 * Option: Event to Trigger Infinite Loading
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[blog-infinite-scroll-event]',
					'default'     => astra_get_option( 'blog-infinite-scroll-event' ),
					'type'        => 'control',
					'control'     => 'select',
					'section'     => 'section-blog',
					'description' => __( 'Infinite Scroll cannot be previewed in the Customizer.', 'astra-addon' ),
					'priority'    => 85,
					'title'       => __( 'Event to Trigger Infinite Loading', 'astra-addon' ),
					'choices'     => array(
						'scroll' => __( 'Scroll', 'astra-addon' ),
						'click'  => __( 'Click', 'astra-addon' ),
					),
					'required'    => array( ASTRA_THEME_SETTINGS . '[blog-pagination]', '===', 'infinite' ),
				),

				/**
				 * Option: Read more text
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-load-more-text]',
					'default'  => astra_get_option( 'blog-load-more-text' ),
					'type'     => 'control',
					'section'  => 'section-blog',
					'priority' => 90,
					'title'    => __( 'Load More Text', 'astra-addon' ),
					'control'  => 'text',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[blog-pagination]', '===', 'infinite' ),
							array( ASTRA_THEME_SETTINGS . '[blog-infinite-scroll-event]', '===', 'click' ),
						),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[ast-styling-section-blog-pagination]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog',
					'priority' => 95,
					'settings' => array(),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Blog_Pro_Configs;

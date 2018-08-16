<?php
/**
 * Blog Pro Single General Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Blog_Pro_Single_Configs' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Blog_Pro_Single_Configs extends Astra_Customizer_Config_Base {

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
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-single-meta]',
					'type'     => 'ast-sortable',
					'section'  => 'section-blog-single',
					'priority' => 5,
					'title'    => __( 'Single Post Meta', 'astra-addon' ),
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
				 * Option: Author info
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[ast-author-info]',
					'default' => astra_get_option( 'ast-author-info' ),
					'type'    => 'control',
					'section' => 'section-blog-single',
					'title'   => __( 'Author Info', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/**
				 * Option: Disable Single Post Navigation
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[ast-single-post-navigation]',
					'default' => astra_get_option( 'ast-single-post-navigation' ),
					'type'    => 'control',
					'section' => 'section-blog-single',
					'title'   => __( 'Disable Single Post Navigation', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/**
				 * Option: Autoposts
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[ast-auto-prev-post]',
					'default'     => astra_get_option( 'ast-auto-prev-post' ),
					'type'        => 'control',
					'section'     => 'section-blog-single',
					'title'       => __( 'Auto Load Previous Posts', 'astra-addon' ),
					'control'     => 'checkbox',
					'description' => __( 'Auto Load Previous Posts cannot be previewed in the Customizer.', 'astra-addon' ),
				),

				/**
				 * Option: Remove feature image padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[single-featured-image-padding]',
					'default'     => astra_get_option( 'single-featured-image-padding' ),
					'type'        => 'control',
					'control'     => 'checkbox',
					'section'     => 'section-blog-single',
					'title'       => __( 'Remove Featured Image Padding', 'astra-addon' ),
					'description' => __( 'This option will not work on full width layouts.', 'astra-addon' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-single-post-spacing]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-blog-single',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'settings' => array(),
				),

				/**
				 * Option: Single Post Spacing
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[single-post-outside-spacing]',
					'default'        => astra_get_option( 'single-post-outside-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-blog-single',
					'title'          => __( 'Space Outside Container', 'astra-addon' ),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Single Post Margin
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[single-post-inside-spacing]',
					'default'        => astra_get_option( 'single-post-inside-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-blog-single',
					'title'          => __( 'Space Inside Container', 'astra-addon' ),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-single-featured-image-padding]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-blog-single',
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
new Astra_Customizer_Blog_Pro_Single_Configs;

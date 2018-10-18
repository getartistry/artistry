<?php
/**
 * Blog Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.3
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
if ( ! class_exists( 'Astra_Customizer_Blog_Spacing_Configs' ) ) {

	/**
	 * Register Blog Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Blog_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option - Blog Spacing divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[blog-post-spacing-divider]',
					'section'  => 'section-blog',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'priority' => 125,
					'settings' => array(),
				),

				/**
				 * Option: Post Outside Spacing
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[blog-post-outside-spacing]',
					'default'        => astra_get_option( 'blog-post-outside-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-blog',
					'priority'       => 130,
					'title'          => __( 'Space Outside Post', 'astra-addon' ),
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
				 * Option: Post Inside Spacing
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[blog-post-inside-spacing]',
					'default'        => astra_get_option( 'blog-post-inside-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-blog',
					'priority'       => 135,
					'title'          => __( 'Space Inside Post', 'astra-addon' ),
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
				 * Option: Post Pagination Spacing
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[blog-post-pagination-spacing]',
					'default'        => astra_get_option( 'blog-post-pagination-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-blog',
					'priority'       => 90,
					'title'          => __( 'Post Pagination Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[blog-pagination]', '==', 'number' ),
						),
					),
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Blog_Spacing_Configs;

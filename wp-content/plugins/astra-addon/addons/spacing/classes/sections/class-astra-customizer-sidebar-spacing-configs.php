<?php
/**
 * Sidebar Spacing Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Sidebar_Spacing_Configs' ) ) {

	/**
	 * Register Sidebar Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Sidebar_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Sidebar Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-section-sidebar-spacing]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-sidebars',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'priority' => 25,
					'settings' => array(),
				),

				/**
				 * Option - Sidebar Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[sidebar-outside-spacing]',
					'default'        => astra_get_option( 'sidebar-outside-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-sidebars',
					'priority'       => 25,
					'title'          => __( 'Space Outside Sidebar', 'astra-addon' ),
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
				 * Option - Two Boxed Sidebar Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[sidebar-inside-spacing]',
					'default'        => astra_get_option( 'sidebar-inside-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-sidebars',
					'priority'       => 25,
					'title'          => __( 'Space Inside Sidebar', 'astra-addon' ),
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
new Astra_Customizer_Sidebar_Spacing_Configs;

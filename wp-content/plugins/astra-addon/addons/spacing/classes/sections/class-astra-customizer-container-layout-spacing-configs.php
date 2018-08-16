<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.3
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
if ( ! class_exists( 'Astra_Customizer_Container_Layout_Spacing_Configs' ) ) {

	/**
	 * Register Content Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Container_Layout_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Content Spacing Customizer Configurations.
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
					'name'     => ASTRA_THEME_SETTINGS . '[content-spacing-divider]',
					'section'  => 'section-container-layout',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'priority' => 90,
					'settings' => array(),
				),

				/**
				 * Option - Content Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[container-outside-spacing]',
					'default'        => astra_get_option( 'container-outside-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-container-layout',
					'priority'       => 95,
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
				 * Option - Content Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[container-inside-spacing]',
					'default'        => astra_get_option( 'container-inside-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-container-layout',
					'priority'       => 100,
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
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Container_Layout_Spacing_Configs;

<?php
/**
 * Header Spacing Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Header_Spacing_Configs' ) ) {

	/**
	 * Register Header Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Header_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Mobile Menu label Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-spacing-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-header',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'priority' => 100,
					'settings' => array(),
				),

				/**
				 * Option - Header Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[header-spacing]',
					'default'        => astra_get_option( 'header-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-header',
					'priority'       => 105,
					'title'          => __( 'Header Space', 'astra-addon' ),
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
				 * Option - Primary Menu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[primary-menu-spacing]',
					'default'        => astra_get_option( 'primary-menu-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-header',
					'priority'       => 110,
					'title'          => __( 'Primary Menu Space', 'astra-addon' ),
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
				 * Option - Primary Menu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[primary-submenu-spacing]',
					'default'        => astra_get_option( 'primary-submenu-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-header',
					'priority'       => 115,
					'title'          => __( 'Primary Submenu Space', 'astra-addon' ),
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
new Astra_Customizer_Header_Spacing_Configs;

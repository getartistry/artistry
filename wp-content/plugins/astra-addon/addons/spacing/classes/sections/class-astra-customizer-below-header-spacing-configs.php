<?php
/**
 * Below Header Spacing Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Below_Header_Spacing_Configs' ) ) {

	/**
	 * Register Below Header Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Below_Header_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Below Header Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option - Below Header Space Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-spacing-divider]',
					'section'  => 'section-below-header',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'title'    => __( 'Spacing', 'astra-addon' ),
					'priority' => 150,
					'settings' => array(),
				),

				/**
				 * Option - Below Header Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[below-header-spacing]',
					'default'        => astra_get_option( 'below-header-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-below-header',
					'priority'       => 155,
					'title'          => __( 'Space Below Header', 'astra-addon' ),
					'required'       => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
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
				 * Option - Below Header Menu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[below-header-menu-spacing]',
					'default'        => astra_get_option( 'below-header-menu-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-below-header',
					'priority'       => 160,
					'title'          => __( 'Menu Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
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

				/**
				 * Option - Below Header Subenu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[below-header-submenu-spacing]',
					'default'        => astra_get_option( 'below-header-submenu-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-below-header',
					'priority'       => 165,
					'title'          => __( 'Submenu Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
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
new Astra_Customizer_Below_Header_Spacing_Configs;

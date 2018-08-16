<?php
/**
 * Above Header Spacing Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Above_Header_Spacing_Configs' ) ) {

	/**
	 * Register Above Header Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Above_Header_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Above Header Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option - Top Menu Space
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-spacing-divider]',
					'section'  => 'section-above-header',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Spacing', 'astra-addon' ),
					'priority' => 150,
					'settings' => array(),
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
				),

				/**
				 * Option - Above Header Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[above-header-spacing]',
					'default'        => astra_get_option( 'above-header-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-above-header',
					'priority'       => 160,
					'title'          => __( 'Space Above Header', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
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

				/**
				 * Option - Above Header Menu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[above-header-menu-spacing]',
					'default'        => astra_get_option( 'above-header-menu-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-above-header',
					'priority'       => 165,
					'title'          => __( 'Menu Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
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
				 * Option - Above Header Submenu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[above-header-submenu-spacing]',
					'default'        => astra_get_option( 'above-header-submenu-spacing' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-above-header',
					'priority'       => 170,
					'title'          => __( 'Submenu Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'menu' ),
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
new Astra_Customizer_Above_Header_Spacing_Configs;

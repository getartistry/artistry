<?php
/**
 * Footer Spacing Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Footer_Small_Spacing_Configs' ) ) {

	/**
	 * Register Footer Spacing Customizer Configurations.
	 */
	class Astra_Customizer_Footer_Small_Spacing_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Footer Spacing Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option - Footer Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[footer-sml-spacing]',
					'default'        => astra_get_option( 'footer-sml-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-footer-small',
					'priority'       => 36,
					'title'          => __( 'Footer Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							ASTRA_THEME_SETTINGS . '[footer-sml-layout]',
							'!=',
							'disabled',
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
				 * Option - Footer Menu Space
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[footer-menu-spacing]',
					'default'        => astra_get_option( 'footer-menu-spacing' ),
					'type'           => 'control',
					'transport'      => 'postMessage',
					'control'        => 'ast-responsive-spacing',
					'section'        => 'section-footer-small',
					'priority'       => 37,
					'title'          => __( 'Footer Menu Space', 'astra-addon' ),
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[footer-sml-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[footer-sml-section-2]', '==', 'menu' ),
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
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-spacing-divider]',
					'section'  => 'section-footer-small',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array(
						'conditions' => array(
							ASTRA_THEME_SETTINGS . '[footer-sml-layout]',
							'!=',
							'disabled',
						),
					),
					'priority' => 37,
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
new Astra_Customizer_Footer_Small_Spacing_Configs;

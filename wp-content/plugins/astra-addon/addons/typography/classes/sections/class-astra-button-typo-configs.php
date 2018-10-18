<?php
/**
 * Section Button options for astra theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Button_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Button_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Button typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Button Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-button]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'default'   => astra_get_option( 'font-family-button' ),
					'section'   => 'section-button-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-button]',
					'priority'  => 1,
				),

				/**
				 * Option: Button Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-button]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-button' ),
					'section'           => 'section-button-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-button]',
					'priority'          => 2,
				),

				/**
				 * Option: Button Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-button]',
					'section'   => 'section-button-typo',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'text-transform-button' ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'type'      => 'control',
					'control'   => 'select',
					'priority'  => 3,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Button Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-button]',
					'section'     => 'section-button-typo',
					'transport'   => 'postMessage',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'default'     => astra_get_option( 'font-size-button' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Button_Typo_Configs;



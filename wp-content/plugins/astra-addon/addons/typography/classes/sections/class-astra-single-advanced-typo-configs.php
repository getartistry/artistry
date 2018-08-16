<?php
/**
 * Section [Footer] options for astra theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Single_Advanced_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Single_Advanced_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Side bar typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Single Post / Page Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-entry-title]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-entry-title' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-single-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-entry-title]',
					'priority'  => 7,
				),

				/**
				 * Option: Single Post / Page Title Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-entry-title]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-entry-title' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-single-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-entry-title]',
					'priority'          => 8,
				),

				/**
				 * Option: Single Post / Page Title Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-entry-title]',
					'type'      => 'control',
					'section'   => 'section-single-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-entry-title' ),
					'transport' => 'postMessage',
					'control'   => 'select',
					'priority'  => 9,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Single Post / Page Title Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-entry-title]',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-single-typo',
					'default'     => '',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'priority'    => 10,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Single_Advanced_Typo_Configs;



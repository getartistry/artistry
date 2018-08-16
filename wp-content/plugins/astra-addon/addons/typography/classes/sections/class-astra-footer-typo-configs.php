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

if ( ! class_exists( 'Astra_Footer_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Footer_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Fotter typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Footer Content Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-footer-content]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'default'   => astra_get_option( 'font-family-footer-content' ),
					'section'   => 'section-footer-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-footer-content]',
				),

				/**
				 * Option: Footer Content Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-footer-content]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'required'          => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-footer-typo',
					'default'           => astra_get_option( 'font-weight-footer-content' ),
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-footer-content]',
				),

				/**
				 * Option: Footer Content Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-footer-content]',
					'section'   => 'section-footer-typo',
					'type'      => 'control',
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'control'   => 'select',
					'default'   => astra_get_option( 'text-transform-footer-content' ),
					'transport' => 'postMessage',
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Footer Content Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-footer-content]',
					'section'     => 'section-footer-typo',
					'default'     => astra_get_option( 'font-size-footer-content' ),
					'title'       => __( 'Font Size', 'astra-addon' ),
					'transport'   => 'postMessage',
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Footer Content Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-footer-content]',
					'section'     => 'section-footer-typo',
					'default'     => '',
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-sml-layout]', '!=', 'disabled' ),
					'title'       => __( 'Line Height', 'astra-addon' ),
					'transport'   => 'postMessage',
					'type'        => 'control',
					'control'     => 'ast-slider',
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

new Astra_Footer_Typo_Configs;



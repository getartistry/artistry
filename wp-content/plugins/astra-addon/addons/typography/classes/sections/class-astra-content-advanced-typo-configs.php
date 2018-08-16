<?php
/**
 * Section [Content] options for astra theme.
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

if ( ! class_exists( 'Astra_Content_Advanced_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Content_Advanced_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Heading <H1> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h1]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-h1' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-content-typo',
					'priority'  => 4,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h1]',
				),

				/**
				 * Option: Heading <H1> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h1]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-h1' ),
					'section'           => 'section-content-typo',
					'priority'          => 4,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h1]',
				),

				/**
				 * Option: Heading <H1> Text Transform
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[text-transform-h1]',
					'section'  => 'section-content-typo',
					'default'  => astra_get_option( 'text-transform-h1' ),
					'title'    => __( 'Text Transform', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'select',
					'priority' => 4,
					'choices'  => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Heading <H1> Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-h1]',
					'section'     => 'section-content-typo',
					'default'     => '',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'transport'   => 'postMessage',
					'priority'    => 5,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H2> Font Family
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-family-h2]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-family',
					'title'             => __( 'Font Family', 'astra-addon' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-family-h2' ),
					'section'           => 'section-content-typo',
					'priority'          => 9,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-weight-h2]',
				),

				/**
				 * Option: Heading <H2> Font Weight
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-weight-h2]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-weight',
					'title'     => __( 'Font Weight', 'astra-addon' ),
					'section'   => 'section-content-typo',
					'default'   => astra_get_option( 'font-weight-h2' ),
					'priority'  => 9,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-family-h2]',
				),

				/**
				 * Option: Heading <H2> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h2]',
					'section'   => 'section-content-typo',
					'default'   => astra_get_option( 'text-transform-h2' ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'type'      => 'control',
					'control'   => 'select',
					'transport' => 'postMessage',
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
				 * Option: Heading <H2> Line Height
				 */

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-h2]',
					'section'     => 'section-content-typo',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => '',
					'transport'   => 'postMessage',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'priority'    => 10,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H3> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h3]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-h3' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-content-typo',
					'priority'  => 14,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h3]',
				),

				/**
				 * Option: Heading <H3> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h3]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-h3' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-content-typo',
					'priority'          => 14,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h3]',
				),

				/**
				 * Option: Heading <H3> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h3]',
					'type'      => 'control',
					'section'   => 'section-content-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-h3' ),
					'transport' => 'postMessage',
					'control'   => 'select',
					'priority'  => 14,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Heading <H3> Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-h3]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-content-typo',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'transport'   => 'postMessage',
					'default'     => '',
					'priority'    => 15,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H4> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h4]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'default'   => astra_get_option( 'font-family-h4' ),
					'section'   => 'section-content-typo',
					'priority'  => 19,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h4]',
				),

				/**
				 * Option: Heading <H4> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h4]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'default'           => astra_get_option( 'font-weight-h4' ),
					'section'           => 'section-content-typo',
					'priority'          => 19,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h4]',
				),

				/**
				 * Option: Heading <H4> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h4]',
					'section'   => 'section-content-typo',
					'type'      => 'control',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'text-transform-h4' ),
					'transport' => 'postMessage',
					'control'   => 'select',
					'priority'  => 19,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Heading <H4> Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-h4]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'default'     => '',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'priority'    => 20,
					'transport'   => 'postMessage',
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H5> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h5]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-h5' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-content-typo',
					'priority'  => 24,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h5]',
				),

				/**
				 * Option: Heading <H5> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h5]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-content-typo',
					'default'           => astra_get_option( 'font-weight-h5' ),
					'priority'          => 24,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h5]',
				),

				/**
				 * Option: Heading <H5> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h5]',
					'type'      => 'control',
					'section'   => 'section-content-typo',
					'control'   => 'select',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'text-transform-h5' ),
					'priority'  => 24,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Heading <H5> Line Height
				 */

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-h5]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-content-typo',
					'default'     => '',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'transport'   => 'postMessage',
					'priority'    => 25,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Heading <H6> Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-h6]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'default'   => astra_get_option( 'font-family-h6' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-content-typo',
					'priority'  => 29,
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-h6]',
				),

				/**
				 * Option: Heading <H6> Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-h6]',
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => astra_get_option( 'font-weight-h6' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-content-typo',
					'priority'          => 29,
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-h6]',
				),

				/**
				 * Option: Heading <H6> Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-h6]',
					'section'   => 'section-content-typo',
					'type'      => 'control',
					'control'   => 'select',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'transport' => 'postMessage',
					'priority'  => 29,
					'default'   => astra_get_option( 'text-transform-h6' ),
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Heading <H6> Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-h6]',
					'type'        => 'control',
					'section'     => 'section-content-typo',
					'transport'   => 'postMessage',
					'default'     => '',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'priority'    => 30,
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

new Astra_Content_Advanced_Typo_Configs;

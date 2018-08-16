<?php
/**
 * Above Header - Typography Options for our theme.
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


if ( ! class_exists( 'Astra_Above_Header_Typo_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Above_Header_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Above Header Typo Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-font-family]',
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'required'  => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'default'   => astra_get_option( 'above-header-font-family' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-above-header-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[above-header-font-weight]',
				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[above-header-font-weight]',
					'default'           => astra_get_option( 'above-header-font-weight' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'required'          => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-above-header-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[above-header-font-family]',
				),

				/**
				 * Option: Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-text-transform]',
					'section'   => 'section-above-header-typo',
					'transport' => 'postMessage',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'default'   => astra_get_option( 'above-header-text-transform' ),
					'type'      => 'control',
					'required'  => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'control'   => 'select',
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[above-header-font-size]',
					'transport'   => 'postMessage',
					'type'        => 'control',
					'control'     => 'ast-responsive',
					'required'    => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'section'     => 'section-above-header-typo',
					'default'     => astra_get_option( 'above-header-font-size' ),
					'title'       => __( 'Font Size', 'astra-addon' ),
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

new Astra_Above_Header_Typo_Configs;




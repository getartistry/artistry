<?php
/**
 * Advanced Footer Typography Options for our theme.
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

if ( ! class_exists( 'Astra_Advanced_Footer_Typo_Configs' ) ) {

	/**
	 * Register Advanced Footer Typography Customizer Configurations.
	 */
	class Astra_Advanced_Footer_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Advanced Footer Typography Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_config = array(

				/**
				 * Footer Widgets Title Typography
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-typo-title]',
					'title'    => __( 'Widget Title', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-footer-adv-typo',
					'settings' => array(),
					'priority' => 5,
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-family]',
					'default'   => astra_get_option( 'footer-adv-wgt-title-font-family' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-footer-adv-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-weight]',
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-weight]',
					'default'           => astra_get_option( 'footer-adv-wgt-title-font-weight' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-footer-adv-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-family]',
					'required'          => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-text-transform]',
					'default'  => astra_get_option( 'footer-adv-wgt-title-text-transform' ),
					'type'     => 'control',
					'section'  => 'section-footer-adv-typo',
					'title'    => __( 'Text Transform', 'astra-addon' ),
					'control'  => 'select',
					'choices'  => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-font-size]',
					'default'     => astra_get_option( 'footer-adv-wgt-title-font-size' ),
					'type'        => 'control',
					'section'     => 'section-footer-adv-typo',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-title-line-height]',
					'default'     => '',
					'type'        => 'control',
					'section'     => 'section-footer-adv-typo',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				/**
				 * Footer Widgets Content Typography
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-typo-content]',
					'title'    => __( 'Widget Content', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-footer-adv-typo',
					'settings' => array(),
					'priority' => 10,
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-family]',
					'default'   => astra_get_option( 'footer-adv-wgt-content-font-family' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-footer-adv-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-weight]',
					'required'  => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'              => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-weight]',
					'default'           => astra_get_option( 'footer-adv-wgt-content-font-weight' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-footer-adv-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-family]',
					'required'          => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-text-transform]',
					'default'  => astra_get_option( 'footer-adv-wgt-content-text-transform' ),
					'type'     => 'control',
					'section'  => 'section-footer-adv-typo',
					'title'    => __( 'Text Transform', 'astra-addon' ),
					'control'  => 'select',
					'choices'  => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
					'required' => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-font-size]',
					'default'     => astra_get_option( 'footer-adv-wgt-content-font-size' ),
					'type'        => 'control',
					'section'     => 'section-footer-adv-typo',
					'title'       => __( 'Font Size', 'astra-addon' ),
					'control'     => 'ast-responsive',
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

				array(
					'name'        => ASTRA_THEME_SETTINGS . '[footer-adv-wgt-content-line-height]',
					'default'     => '',
					'type'        => 'control',
					'section'     => 'section-footer-adv-typo',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
					'required'    => array( ASTRA_THEME_SETTINGS . '[footer-adv]', '!=', 'disabled' ),
				),

			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Advanced_Footer_Typo_Configs;




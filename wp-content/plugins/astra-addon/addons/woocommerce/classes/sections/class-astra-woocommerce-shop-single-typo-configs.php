<?php
/**
 * Shop Options for our theme.
 *
 * @package     Astra Addon
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

if ( ! class_exists( 'Astra_Woocommerce_Shop_Single_Typo_Configs' ) ) {

	/**
	 * Register Blog Single Layout Configurations.
	 */
	class Astra_Woocommerce_Shop_Single_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Single Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Single Product Title Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-product-title-divider]',
					'section'  => 'section-woo-single-product-typo',
					'title'    => __( 'Product Title', 'astra-addon' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'title' ),
					'control'  => 'ast-divider',
					'priority' => 5,
					'settings' => array(),
				),

				/**
				 * Option: Single Product Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-product-title]',
					'default'   => astra_get_option( 'font-family-product-title' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-woo-single-product-typo',
					'required'  => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'title' ),
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-product-title]',
					'priority'  => 5,
				),

				/**
				 * Option: Single Product Title Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-product-title]',
					'default'           => astra_get_option( 'font-weight-product-title' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-woo-single-product-typo',
					'required'          => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'title' ),
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-product-title]',
					'priority'          => 5,
				),

				/**
					 * Option: Single Product Title Text Transform
					 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-product-title]',
					'default'   => astra_get_option( 'text-transform-product-title' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-woo-single-product-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'required'  => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'title' ),
					'control'   => 'select',
					'priority'  => 5,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Single Product Title Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-product-title]',
					'default'     => astra_get_option( 'font-size-product-title' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'section'     => 'section-woo-single-product-typo',
					'priority'    => 5,
					'title'       => __( 'Font Size', 'astra-addon' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'title' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Single Product Title Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-product-title]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-single-product-typo',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'required'    => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'title' ),
					'priority'    => 5,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Single Product Price Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-product-single-price-divider]',
					'section'  => 'section-woo-single-product-typo',
					'title'    => __( 'Product Price', 'astra-addon' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'price' ),
					'control'  => 'ast-divider',
					'priority' => 10,
					'settings' => array(),
				),

				/**
				 * Option: Single Product Price Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-product-price]',
					'default'   => astra_get_option( 'font-family-product-price' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'required'  => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'price' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-woo-single-product-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-product-price]',
					'priority'  => 10,
				),

				/**
				 * Option: Single Product price Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-product-price]',
					'default'           => astra_get_option( 'font-weight-product-price' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'required'          => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'price' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-woo-single-product-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-product-price]',
					'priority'          => 10,
				),

				/**
				 * Option: Single Product Price Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-product-price]',
					'default'     => astra_get_option( 'font-size-product-price' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'required'    => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'price' ),
					'section'     => 'section-woo-single-product-typo',
					'priority'    => 10,
					'title'       => __( 'Font Size', 'astra-addon' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Single Product Price Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-product-price]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-single-product-typo',
					'required'    => array( ASTRA_THEME_SETTINGS . '[single-product-structure]', 'contains', 'price' ),
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

				/**
				 * Option: Single Product Breadcrumb Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-product-breadcrumb-divider]',
					'section'  => 'section-woo-single-product-typo',
					'title'    => __( 'Product Breadcrumb', 'astra-addon' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-disable]', '!=', 1 ),
					'control'  => 'ast-divider',
					'priority' => 15,
					'settings' => array(),
				),

				/**
				 * Option: Single Product Breadcrumb Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-product-breadcrumb]',
					'default'   => astra_get_option( 'font-family-product-breadcrumb' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'required'  => array( ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-disable]', '!=', 1 ),
					'section'   => 'section-woo-single-product-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-product-breadcrumb]',
					'priority'  => 15,
				),

				/**
				 * Option: Single Product Breadcrumb Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-product-breadcrumb]',
					'default'           => astra_get_option( 'font-weight-product-breadcrumb' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'required'          => array( ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-disable]', '!=', 1 ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-woo-single-product-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-product-breadcrumb]',
					'priority'          => 15,
				),

				/**
					 * Option: Single Product Breadcrumb Text Transform
					 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-product-breadcrumb]',
					'default'   => astra_get_option( 'text-transform-product-breadcrumb' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-woo-single-product-typo',
					'required'  => array( ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-disable]', '!=', 1 ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'control'   => 'select',
					'priority'  => 15,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Single Product Breadcrumb Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-product-breadcrumb]',
					'default'     => astra_get_option( 'font-size-product-breadcrumb' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'required'    => array( ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-disable]', '!=', 1 ),
					'section'     => 'section-woo-single-product-typo',
					'priority'    => 15,
					'title'       => __( 'Font Size', 'astra-addon' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Single Product Breadcrumb Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-product-breadcrumb]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-single-product-typo',
					'required'    => array( ASTRA_THEME_SETTINGS . '[single-product-breadcrumb-disable]', '!=', 1 ),
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'priority'    => 15,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Single Product Content Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-product-content-divider]',
					'section'  => 'section-woo-single-product-typo',
					'title'    => __( 'Product Content', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 20,
					'settings' => array(),
				),

				/**
				 * Option: Single Product Content Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-product-content]',
					'default'   => astra_get_option( 'font-family-product-content' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-woo-single-product-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-product-content]',
					'priority'  => 20,
				),

				/**
				 * Option: Single Product Content Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-product-content]',
					'default'           => astra_get_option( 'font-weight-product-content' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-woo-single-product-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-product-content]',
					'priority'          => 20,
				),

				/**
					 * Option: Single Product Content Text Transform
					 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-product-content]',
					'default'   => astra_get_option( 'text-transform-product-content' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-woo-single-product-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'control'   => 'select',
					'priority'  => 20,
					'choices'   => array(
						''           => __( 'Inherit', 'astra-addon' ),
						'none'       => __( 'None', 'astra-addon' ),
						'capitalize' => __( 'Capitalize', 'astra-addon' ),
						'uppercase'  => __( 'Uppercase', 'astra-addon' ),
						'lowercase'  => __( 'Lowercase', 'astra-addon' ),
					),
				),

				/**
				 * Option: Single Product Content Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-product-content]',
					'default'     => astra_get_option( 'font-size-product-content' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'section'     => 'section-woo-single-product-typo',
					'priority'    => 20,
					'title'       => __( 'Font Size', 'astra-addon' ),
					'input_attrs' => array(
						'min' => 0,
					),
					'units'       => array(
						'px' => 'px',
						'em' => 'em',
					),
				),

				/**
				 * Option: Single Product Content Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-product-content]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-single-product-typo',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'priority'    => 20,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_Shop_Single_Typo_Configs;






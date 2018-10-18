<?php
/**
 * Shop Options for our theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
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

if ( ! class_exists( 'Astra_Woocommerce_Shop_Typo_Configs' ) ) {

	/**
	 * Register Woocommerce Shop Typo Layout Configurations.
	 */
	class Astra_Woocommerce_Shop_Typo_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Woocommerce Shop Typo Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Product Title Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-shop-product-title-divider]',
					'section'  => 'section-woo-shop-typo',
					'required' => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
					'title'    => __( 'Product Title', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 5,
					'settings' => array(),
				),

				/**
				 * Option: Product Title Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-shop-product-title]',
					'default'   => astra_get_option( 'font-family-shop-product-title' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'required'  => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-woo-shop-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-title]',
					'priority'  => 5,
				),

				/**
				 * Option: Product Title Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-title]',
					'default'           => astra_get_option( 'font-weight-shop-product-title' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'required'          => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-woo-shop-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-shop-product-title]',
					'priority'          => 5,
				),

				/**
					 * Option: Product Title Text Transform
					 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-shop-product-title]',
					'default'   => astra_get_option( 'text-transform-shop-product-title' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-woo-shop-typo',
					'required'  => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
					'title'     => __( 'Text Transform', 'astra-addon' ),
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
				 * Option: Product Title Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-shop-product-title]',
					'default'     => astra_get_option( 'font-size-shop-product-title' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'section'     => 'section-woo-shop-typo',
					'priority'    => 5,
					'required'    => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
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
				 * Option: Product Title Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-shop-product-title]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-shop-typo',
					'required'    => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
					'title'       => __( 'Line Height', 'astra-addon' ),
					'control'     => 'ast-slider',
					'priority'    => 5,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Product Price Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-product-shop-price-divider]',
					'section'  => 'section-woo-shop-typo',
					'required' => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'price' ),
					'title'    => __( 'Product Price', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 10,
					'settings' => array(),
				),

				/**
				 * Option: Product Price Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-shop-product-price]',
					'default'   => astra_get_option( 'font-family-shop-product-price' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'required'  => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'price' ),
					'title'     => __( 'Font Family', 'astra-addon' ),
					'section'   => 'section-woo-shop-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-price]',
					'priority'  => 10,
				),

				/**
				 * Option: Product Price Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-price]',
					'default'           => astra_get_option( 'font-weight-shop-product-price' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'required'          => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'price' ),
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'section'           => 'section-woo-shop-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-shop-product-price]',
					'priority'          => 10,
				),

				/**
				 * Option: Product Price Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-shop-product-price]',
					'default'     => astra_get_option( 'font-size-shop-product-price' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'section'     => 'section-woo-shop-typo',
					'priority'    => 10,
					'required'    => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'price' ),
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
				 * Option: Product Price Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-shop-product-price]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-shop-typo',
					'required'    => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'price' ),
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
				 * Option: Product Content Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[typo-product-shop-content-divider]',
					'section'  => 'section-woo-shop-typo',
					'title'    => __( 'Product Content', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 15,
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'category' ),
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'structure' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Product Content Font Family
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[font-family-shop-product-content]',
					'default'   => astra_get_option( 'font-family-shop-product-content' ),
					'type'      => 'control',
					'control'   => 'ast-font',
					'font-type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'astra-addon' ),
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'category' ),
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'structure' ),
						),
						'operator'   => 'OR',
					),
					'section'   => 'section-woo-shop-typo',
					'connect'   => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-content]',
					'priority'  => 15,
				),

				/**
				 * Option: Product Content Font Weight
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[font-weight-shop-product-content]',
					'default'           => astra_get_option( 'font-weight-shop-product-content' ),
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'type'              => 'control',
					'control'           => 'ast-font',
					'font-type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'astra-addon' ),
					'required'          => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'category' ),
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'structure' ),
						),
						'operator'   => 'OR',
					),
					'section'           => 'section-woo-shop-typo',
					'connect'           => ASTRA_THEME_SETTINGS . '[font-family-shop-product-content]',
					'priority'          => 15,
				),

				/**
				 * Option: Product Title Text Transform
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[text-transform-shop-product-content]',
					'default'   => astra_get_option( 'text-transform-shop-product-content' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-woo-shop-typo',
					'title'     => __( 'Text Transform', 'astra-addon' ),
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'category' ),
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'structure' ),
						),
						'operator'   => 'OR',
					),
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
				 * Option: Product Content Font Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[font-size-shop-product-content]',
					'default'     => astra_get_option( 'font-size-shop-product-content' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive',
					'section'     => 'section-woo-shop-typo',
					'required'    => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'category' ),
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'structure' ),
						),
						'operator'   => 'OR',
					),
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
				 * Option: Product Content Line Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[line-height-shop-product-content]',
					'default'     => '',
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-shop-typo',
					'title'       => __( 'Line Height', 'astra-addon' ),
					'required'    => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'category' ),
							array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'structure' ),
						),
						'operator'   => 'OR',
					),
					'control'     => 'ast-slider',
					'priority'    => 15,
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


new Astra_Woocommerce_Shop_Typo_Configs;






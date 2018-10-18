<?php
/**
 * Register customizer panels & sections.
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

if ( ! class_exists( 'Astra_Woocommerce_Panels_And_Sections' ) ) {

	/**
	 * Register Woocommerce Panels and sections Layout Configurations.
	 */
	class Astra_Woocommerce_Panels_And_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register Woocommerce Panels and sections Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Section Checkout Page
				 */
				array(
					'name'     => 'section-checkout-page',
					'priority' => 25,
					'title'    => __( 'Checkout Page', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-layout',
					'section'  => 'section-woo-group',
				),

				/**
				 * WooCommerce
				 *
				 * Customizer > Typography
				 */
				array(
					'name'     => 'section-woo-typo',
					'priority' => 60,
					'title'    => __( 'WooCommerce', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-typography',
				),

				/**
				 * General
				 *
				 * Customizer > Typography > WooCommerce
				 */
				array(
					'name'     => 'section-woo-general-typo',
					'priority' => 5,
					'title'    => __( 'General', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-typography',
					'section'  => 'section-woo-typo',
				),

				/**
				 * Shop
				 *
				 * Customizer > Typography > WooCommerce
				 */
				array(
					'name'     => 'section-woo-shop-typo',
					'priority' => 10,
					'title'    => __( 'Shop', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-typography',
					'section'  => 'section-woo-typo',
				),

				/**
				 * Single Product
				 *
				 * Customizer > Typography > WooCommerce
				 */
				array(
					'name'     => 'section-woo-single-product-typo',
					'priority' => 15,
					'title'    => __( 'Single Product', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-typography',
					'section'  => 'section-woo-typo',
				),

				/**
				 * WooCommerce
				 *
				 * Customizer > Colors & Background
				 */
				array(
					'name'     => 'section-woo-colors-bg',
					'priority' => 60,
					'title'    => __( 'WooCommerce', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-colors-background',
				),

				/**
				 * General
				 *
				 * Customizer > Colors & Background > WooCommerce
				 */
				array(
					'name'     => 'section-woo-general-color',
					'priority' => 5,
					'title'    => __( 'General', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-colors-background',
					'section'  => 'section-woo-colors-bg',
				),

				/**
				 * Shop
				 *
				 * Customizer > Colors & Background > WooCommerce
				 */
				array(
					'name'     => 'section-woo-shop-color',
					'priority' => 10,
					'title'    => __( 'Shop', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-colors-background',
					'section'  => 'section-woo-colors-bg',
				),

				/**
				 * Single Product
				 *
				 * Customizer > Colors & Background > WooCommerce
				 */
				array(
					'name'     => 'section-woo-single-product-color',
					'priority' => 15,
					'title'    => __( 'Single Product', 'astra-addon' ),
					'type'     => 'section',
					'panel'    => 'panel-colors-background',
					'section'  => 'section-woo-colors-bg',
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_Panels_And_Sections;






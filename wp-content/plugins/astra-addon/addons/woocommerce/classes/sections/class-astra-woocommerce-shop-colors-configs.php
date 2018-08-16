<?php
/**
 * Shop Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Woocommerce_Shop_Colors_Configs' ) ) {

	/**
	 * Register Blog Single Layout Configurations.
	 */
	class Astra_Woocommerce_Shop_Colors_Configs extends Astra_Customizer_Config_Base {

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
				 * Shop Product Title Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[shop-product-title-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'required'  => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'title' ),
					'title'     => __( 'Product Title Color', 'astra-addon' ),
					'section'   => 'section-woo-shop-color',
				),

				/**
				 * Shop Product Price Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[shop-product-price-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'required'  => array( ASTRA_THEME_SETTINGS . '[shop-product-structure]', 'contains', 'price' ),
					'title'     => __( 'Product Price Color', 'astra-addon' ),
					'section'   => 'section-woo-shop-color',
				),

				/**
				 * Shop Product Content Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[shop-product-content-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'title'     => __( 'Product Content Color', 'astra-addon' ),
					'section'   => 'section-woo-shop-color',
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_Shop_Colors_Configs;






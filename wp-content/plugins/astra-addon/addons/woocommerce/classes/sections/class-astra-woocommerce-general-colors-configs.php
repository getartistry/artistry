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

if ( ! class_exists( 'Astra_Woocommerce_General_Colors_Configs' ) ) {

	/**
	 * Register Woocommerce general Color Layout Configurations.
	 */
	class Astra_Woocommerce_General_Colors_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Woocommerce general Color Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Single Product Rating Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[single-product-rating-color]',
					'default'   => '',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'title'     => __( 'Product Rating Color', 'astra-addon' ),
					'section'   => 'section-woo-general-color',
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_General_Colors_Configs;






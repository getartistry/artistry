<?php
/**
 * Register customizer panels & sections fro Woocommerce.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.1.0
 * @since       1.4.6 Chnaged to using Astra_Customizer API
 */

if ( ! class_exists( 'Astra_Customizer_Register_Woo_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class Astra_Customizer_Register_Woo_Section extends Astra_Customizer_Config_Base {

		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(
				/**
				 * WooCommerce
				 */
				array(
					'name'     => 'section-woo-group',
					'type'     => 'section',
					'title'    => __( 'WooCommerce', 'astra' ),
					'panel'    => 'panel-layout',
					'priority' => 60,
				),

				array(
					'name'     => 'section-woo-general',
					'title'    => __( 'General', 'astra' ),
					'type'     => 'section',
					'panel'    => 'panel-layout',
					'section'  => 'section-woo-group',
					'priority' => 5,
				),

				array(
					'name'     => 'section-woo-shop',
					'title'    => __( 'Shop', 'astra' ),
					'type'     => 'section',
					'panel'    => 'panel-layout',
					'section'  => 'section-woo-group',
					'priority' => 10,
				),

				array(
					'name'     => 'section-woo-shop-single',
					'type'     => 'section',
					'title'    => __( 'Single Product', 'astra' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-woo-group',
					'priority' => 15,
				),

				array(
					'name'     => 'section-woo-shop-cart',
					'type'     => 'section',
					'title'    => __( 'Cart Page', 'astra' ),
					'panel'    => 'panel-layout',
					'section'  => 'section-woo-group',
					'priority' => 20,
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}


new Astra_Customizer_Register_Woo_Section();

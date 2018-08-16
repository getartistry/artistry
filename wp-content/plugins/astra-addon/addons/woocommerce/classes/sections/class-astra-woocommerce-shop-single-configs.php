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

if ( ! class_exists( 'Astra_Woocommerce_Shop_Single_Configs' ) ) {

	/**
	 * Register Woocommerce shop single Layout Configurations.
	 */
	class Astra_Woocommerce_Shop_Single_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Woocommerce shop single Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Product Gallery Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-gallery-layout]',
					'default'  => astra_get_option( 'single-product-gallery-layout' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Gallery Layout', 'astra-addon' ),
					'control'  => 'select',
					'priority' => 5,
					'choices'  => array(
						'vertical'   => __( 'Vertical', 'astra-addon' ),
						'horizontal' => __( 'Horizontal', 'astra-addon' ),
					),
				),

				/**
				 * Option: Product Image Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[single-product-image-width]',
					'default'     => astra_get_option( 'single-product-image-width' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Image Width', 'astra-addon' ),
					'suffix'      => '%',
					'priority'    => 5,
					'input_attrs' => array(
						'min'  => 20,
						'step' => 1,
						'max'  => 70,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-meta-divider]',
					'section'  => 'section-woo-shop-single',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'priority' => 9,
				),

				/**
				 * Option: Single Post Meta
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-structure]',
					'default'  => astra_get_option( 'single-product-structure' ),
					'type'     => 'control',
					'control'  => 'ast-sortable',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Single Product Structure', 'astra-addon' ),
					'priority' => 15,
					'choices'  => array(
						'title'      => __( 'Title', 'astra-addon' ),
						'price'      => __( 'Price', 'astra-addon' ),
						'ratings'    => __( 'Ratings', 'astra-addon' ),
						'add_cart'   => __( 'Add To Cart', 'astra-addon' ),
						'short_desc' => __( 'Short Description', 'astra-addon' ),
						'meta'       => __( 'Meta', 'astra-addon' ),
					),
				),

				/**
				 * Option: Enable Ajax add to cart.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-ajax-add-to-cart]',
					'default'  => astra_get_option( 'single-product-ajax-add-to-cart' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Enable Ajax Add To Cart', 'astra-addon' ),
					'priority' => 18,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Enable product zoom effect.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-image-zoom-effect]',
					'default'  => astra_get_option( 'single-product-image-zoom-effect' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Enable Image Zoom Effect', 'astra-addon' ),
					'priority' => 18,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Navigation Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-nav-style]',
					'default'  => astra_get_option( 'single-product-nav-style' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Product Navigation', 'astra-addon' ),
					'control'  => 'select',
					'priority' => 20,
					'choices'  => array(
						'disable'        => __( 'Disable', 'astra-addon' ),
						'circle'         => __( 'Circle', 'astra-addon' ),
						'circle-outline' => __( 'Circle Outline', 'astra-addon' ),
						'square'         => __( 'Square', 'astra-addon' ),
						'square-outline' => __( 'Square Outline', 'astra-addon' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-tabs-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Product Description Tabs', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 25,
					'settings' => array(),
				),

				/**
				 * Option: Enable Product Tabs Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-tabs-display]',
					'default'  => astra_get_option( 'single-product-tabs-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Display Product Tabs', 'astra-addon' ),
					'control'  => 'checkbox',
					'priority' => 30,
				),

				/**
				 * Option: Product Tabs Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-tabs-layout]',
					'default'  => astra_get_option( 'single-product-tabs-layout' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Layout', 'astra-addon' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[single-product-tabs-display]', '==', true ),
					'control'  => 'select',
					'priority' => 35,
					'choices'  => array(
						'horizontal' => __( 'Horizontal', 'astra-addon' ),
						'vertical'   => __( 'Vertical', 'astra-addon' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-related-upsell-divider]',
					'section'  => 'section-woo-shop-single',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'title'    => __( 'Related & Up Sell Products', 'astra-addon' ),
					'settings' => array(),
					'priority' => 55,
				),

				/**
				 * Option: Display related products
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-related-display]',
					'default'  => astra_get_option( 'single-product-related-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Display related products', 'astra-addon' ),
					'control'  => 'checkbox',
					'priority' => 60,
				),

				/**
				 * Option: Display Up Sells
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-up-sells-display]',
					'default'  => astra_get_option( 'single-product-up-sells-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Display Up Sells', 'astra-addon' ),
					'control'  => 'checkbox',
					'priority' => 65,
				),

				/**
				 * Option: Related Product Columns
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[single-product-related-upsell-grid]',
					'default'     => array(
						'desktop' => 4,
						'tablet'  => 3,
						'mobile'  => 2,
					),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive-slider',
					'section'     => 'section-woo-shop-single',
					'required'    => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[single-product-related-display]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[single-product-up-sells-display]', '==', true ),
						),
					),
					'priority'    => 70,
					'title'       => __( 'Columns', 'astra-addon' ),
					'input_attrs' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
				),

				/**
				 * Option: No. of Related Product
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[single-product-related-upsell-per-page]',
					'default'  => astra_get_option( 'single-product-related-upsell-per-page' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'No. of Related Product', 'astra-addon' ),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[single-product-related-display]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[single-product-up-sells-display]', '==', true ),
						),
					),
					'control'  => 'number',
					'priority' => 75,
				),

			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_Shop_Single_Configs;






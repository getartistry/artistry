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

if ( ! class_exists( 'Astra_Woocommerce_Shop_Configs' ) ) {

	/**
	 * Register Woocommerce Shop Layout Configurations.
	 */
	class Astra_Woocommerce_Shop_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Woocommerce Shop Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Choose Product Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-style]',
					'default'  => astra_get_option( 'shop-style' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Choose Product Style', 'astra-addon' ),
					'control'  => 'ast-radio-image',
					'priority' => 5,
					'choices'  => array(
						'shop-page-grid-style' => array(
							'label' => __( 'Grid View', 'astra-addon' ),
							'path'  => ASTRA_EXT_WOOCOMMERCE_URI . 'assets/images/blog-layout-1-76x48.png',
						),
						'shop-page-list-style' => array(
							'label' => __( 'List View', 'astra-addon' ),
							'path'  => ASTRA_EXT_WOOCOMMERCE_URI . 'assets/images/blog-layout-3-76x48.png',
						),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-box-styling]',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Product Styling', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 75,
					'settings' => array(),
				),

				/**
				 * Option: Content Alignment
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[shop-product-align]',
					'default'   => astra_get_option( 'shop-product-align' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'select',
					'section'   => 'section-woo-shop',
					'priority'  => 80,
					'title'     => __( 'Content Alignment', 'astra-addon' ),
					'choices'   => array(
						'align-left'   => __( 'Left', 'astra-addon' ),
						'align-center' => __( 'Center', 'astra-addon' ),
						'align-right'  => __( 'Right', 'astra-addon' ),
					),
				),

				/**
				 * Option: Box shadow
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[shop-product-shadow]',
					'default'     => astra_get_option( 'shop-product-shadow' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'title'       => __( 'Box Shadow', 'astra-addon' ),
					'section'     => 'section-woo-shop',
					'suffix'      => '',
					'priority'    => 85,
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 5,
					),
				),

				/**
				 * Option: Box hover shadow
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[shop-product-shadow-hover]',
					'default'     => astra_get_option( 'shop-product-shadow-hover' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'title'       => __( 'Box Hover Shadow', 'astra-addon' ),
					'section'     => 'section-woo-shop',
					'suffix'      => '',
					'priority'    => 90,
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 5,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-button-divider]',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Button', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 110,
					'settings' => array(),
				),

				/**
				 * Option: Vertical Padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[shop-button-v-padding]',
					'default'     => astra_get_option( 'shop-button-v-padding' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-shop',
					'title'       => __( 'Vertical Padding', 'astra-addon' ),
					'control'     => 'number',
					'priority'    => 110,
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Horizontal Padding
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[shop-button-h-padding]',
					'default'     => astra_get_option( 'shop-button-h-padding' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'section'     => 'section-woo-shop',
					'priority'    => 110,
					'title'       => __( 'Horizontal Padding', 'astra-addon' ),
					'control'     => 'number',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-pagination-divider]',
					'section'  => 'section-woo-shop',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 140,
					'settings' => array(),
				),

				/**
				 * Option: Shop Pagination
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-pagination]',
					'default'  => astra_get_option( 'shop-pagination' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-woo-shop',
					'priority' => 145,
					'title'    => __( 'Shop Pagination', 'astra-addon' ),
					'choices'  => array(
						'number'   => __( 'Number', 'astra-addon' ),
						'infinite' => __( 'Infinite Scroll', 'astra-addon' ),
					),
				),

				/**
				 * Option: Shop Pagination Style
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[shop-pagination-style]',
					'default'   => astra_get_option( 'shop-pagination-style' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'select',
					'section'   => 'section-woo-shop',
					'required'  => array( ASTRA_THEME_SETTINGS . '[shop-pagination]', '==', 'number' ),
					'priority'  => 150,
					'title'     => __( 'Shop Pagination Style', 'astra-addon' ),
					'choices'   => array(
						'default' => __( 'Default', 'astra-addon' ),
						'square'  => __( 'Square', 'astra-addon' ),
						'circle'  => __( 'Circle', 'astra-addon' ),
					),
				),

				/**
				 * Option: Event to Trigger Infinite Loading
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[shop-infinite-scroll-event]',
					'default'     => astra_get_option( 'shop-infinite-scroll-event' ),
					'type'        => 'control',
					'control'     => 'select',
					'section'     => 'section-woo-shop',
					'description' => __( 'Infinite Scroll cannot be previewed in the Customizer.', 'astra-addon' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[shop-pagination]', '==', 'infinite' ),
					'priority'    => 155,
					'title'       => __( 'Event to Trigger Infinite Loading', 'astra-addon' ),
					'choices'     => array(
						'scroll' => __( 'Scroll', 'astra-addon' ),
						'click'  => __( 'Click', 'astra-addon' ),
					),
				),

				/**
				 * Option: Read more text
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[shop-load-more-text]',
					'default'   => astra_get_option( 'shop-load-more-text' ),
					'type'      => 'control',
					'transport' => 'postMessage',
					'section'   => 'section-woo-shop',
					'priority'  => 160,
					'title'     => __( 'Load More Text', 'astra-addon' ),
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-pagination]', '==', 'infinite' ),
							array( ASTRA_THEME_SETTINGS . '[shop-infinite-scroll-event]', '==', 'click' ),
						),
					),
					'control'   => 'text',
					'partial'   => array(
						'selector'            => '.ast-shop-pagination-infinite .ast-shop-load-more',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Ext_WooCommerce_Partials', '_render_shop_load_more' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-meta-divider]',
					'section'  => 'section-woo-shop',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 29,
					'settings' => array(),
				),

				/**
				 * Option: Display Page Title
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-page-title-display]',
					'default'  => astra_get_option( 'shop-page-title-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Display Page Title', 'astra-addon' ),
					'priority' => 29,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Display Breadcrumb
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-breadcrumb-display]',
					'default'  => astra_get_option( 'shop-breadcrumb-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Display Breadcrumb', 'astra-addon' ),
					'priority' => 29,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Display Toolbar
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-toolbar-display]',
					'default'  => astra_get_option( 'shop-toolbar-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Display Toolbar', 'astra-addon' ),
					'priority' => 29,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-filters-off-canvas-divider]',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Off Canvas Sidebar', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 195,
					'settings' => array(),
				),

				/**
				 * Option: Display Off Canvas On Click Of
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]',
					'default'  => astra_get_option( 'shop-off-canvas-trigger-type' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-woo-shop',
					'priority' => 200,
					'title'    => __( 'Trigger for Off Canvas Sidebar', 'astra-addon' ),
					'choices'  => array(
						'disable'      => __( 'Disable', 'astra-addon' ),
						'link'         => __( 'Link', 'astra-addon' ),
						'button'       => __( 'Button', 'astra-addon' ),
						'custom-class' => __( 'Custom Class', 'astra-addon' ),
					),
				),

				/**
				 * Option: Filter Button Text
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-filter-trigger-link]',
					'default'  => astra_get_option( 'shop-filter-trigger-link' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]', '==', 'button' ),
							array( ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]', '==', 'link' ),
						),
						'operator'   => 'OR',
					),
					'priority' => 205,
					'title'    => __( 'Off Canvas Button/Link Text', 'astra-addon' ),
					'control'  => 'text',
				),

				/**
				 * Option: Custom Class
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-filter-trigger-custom-class]',
					'default'  => astra_get_option( 'shop-filter-trigger-custom-class' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'required' => array( ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]', '==', 'custom-class' ),
					'priority' => 210,
					'title'    => __( 'Custom Class', 'astra-addon' ),
					'control'  => 'text',
				),

				/**
				 * Option: Display Active Filters
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-active-filters-display]',
					'default'  => astra_get_option( 'shop-active-filters-display' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'required' => array( ASTRA_THEME_SETTINGS . '[shop-off-canvas-trigger-type]', '!=', 'disable' ),
					'title'    => __( 'Display Active Filters', 'astra-addon' ),
					'priority' => 215,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-quick-view-divider]',
					'section'  => 'section-woo-shop',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'priority' => 190,
					'settings' => array(),
				),

				/**
				 * Option: Enable Quick View
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[shop-quick-view-enable]',
					'default'  => astra_get_option( 'shop-quick-view-enable' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop',
					'title'    => __( 'Quick View', 'astra-addon' ),
					'control'  => 'select',
					'priority' => 190,
					'choices'  => array(
						'disabled'       => __( 'Disabled', 'astra-addon' ),
						'on-image'       => __( 'On Image', 'astra-addon' ),
						'on-image-click' => __( 'On Image Click', 'astra-addon' ),
						'after-summary'  => __( 'After Summary', 'astra-addon' ),
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_Shop_Configs;






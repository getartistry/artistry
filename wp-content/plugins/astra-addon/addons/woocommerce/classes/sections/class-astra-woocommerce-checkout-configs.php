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

if ( ! class_exists( 'Astra_Woocommerce_Checkout_Configs' ) ) {

	/**
	 * Register Woocommerce Checkout Layout Configurations.
	 */
	class Astra_Woocommerce_Checkout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Woocommerce Checkout Layout Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Two Step Checkout
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[two-step-checkout]',
					'default' => astra_get_option( 'two-step-checkout' ),
					'type'    => 'control',
					'section' => 'section-checkout-page',
					'title'   => __( 'Two Step Checkout', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/**
				 * Option: Display Order Note on Checkout Page
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[checkout-order-notes-display]',
					'default' => astra_get_option( 'checkout-order-notes-display' ),
					'type'    => 'control',
					'section' => 'section-checkout-page',
					'title'   => __( 'Display Order Note', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/**
				 * Option: Display Coupon on Checkout Page
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[checkout-coupon-display]',
					'default' => astra_get_option( 'checkout-coupon-display' ),
					'type'    => 'control',
					'section' => 'section-checkout-page',
					'title'   => __( 'Display Apply Coupon Field', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/*
				 * Option: Distraction free Checkout.
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[checkout-distraction-free]',
					'default' => astra_get_option( 'checkout-distraction-free' ),
					'type'    => 'control',
					'section' => 'section-checkout-page',
					'title'   => __( 'Distraction Free Checkout', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/*
				 * Option: Replace Form lable with placeholder
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[checkout-titles-as-placeholders]',
					'default' => astra_get_option( 'checkout-titles-as-placeholders' ),
					'type'    => 'control',
					'section' => 'section-checkout-page',
					'title'   => __( 'Use Labels as Placeholders', 'astra-addon' ),
					'control' => 'checkbox',
				),

				/*
				 * Option: Preserve form data.
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[checkout-persistence-form-data]',
					'default'     => astra_get_option( 'checkout-persistence-form-data' ),
					'type'        => 'control',
					'section'     => 'section-checkout-page',
					'title'       => __( 'Persistent Checkout Form Data', 'astra-addon' ),
					'description' => __( 'Retain the Checkout form fields even if the visitor accidentally reloads the checkout page.', 'astra-addon' ),
					'control'     => 'checkbox',
				),

				/**
				 * Option: Checkout Content Width
				 */
				array(
					'name'    => ASTRA_THEME_SETTINGS . '[checkout-content-width]',
					'default' => astra_get_option( 'checkout-content-width' ),
					'type'    => 'control',
					'control' => 'select',
					'section' => 'section-checkout-page',
					'title'   => __( 'Checkout Form Width', 'astra-addon' ),
					'choices' => array(
						'default' => __( 'Default', 'astra-addon' ),
						'custom'  => __( 'Custom', 'astra-addon' ),
					),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[checkout-content-max-width]',
					'default'     => 1200,
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-slider',
					'required'    => array( ASTRA_THEME_SETTINGS . '[checkout-content-width]', '==', 'custom' ),
					'section'     => 'section-checkout-page',
					'title'       => __( 'Enter Width', 'astra-addon' ),
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			return $configurations;

		}
	}
}


new Astra_Woocommerce_Checkout_Configs;






<?php
/**
 * Transparent Header Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       Astra 1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Transparent_Header_Configs' ) ) {

	/**
	 * Register Transparent Header Customizer Configurations.
	 */
	class Astra_Customizer_Transparent_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Transparent Header Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Enable Transparent Header
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[transparent-header-enable]',
					'default'  => astra_get_option( 'transparent-header-enable' ),
					'type'     => 'control',
					'section'  => 'section-transparent-header',
					'title'    => __( 'Enable on Complete Website', 'astra-addon' ),
					'priority' => 20,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Disable Transparent Header on Archive Pages
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-disable-archive]',
					'default'     => astra_get_option( 'transparent-header-disable-archive' ),
					'type'        => 'control',
					'section'     => 'section-transparent-header',
					'title'       => __( 'Force Disable on Special Pages?', 'astra-addon' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', true ),
					'description' => __( 'This setting is generally not recommended on special pages such as archive, search, 404, etc. If you would like to enable it, uncheck this option', 'astra-addon' ),
					'priority'    => 25,
					'control'     => 'checkbox',
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-transparent-logo]',
					'default'  => astra_get_option( 'different-transparent-logo', false ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', true ),
					'section'  => 'section-transparent-header',
					'title'    => __( 'Different Logo for Transparent Header?', 'astra-addon' ),
					'priority' => 25,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Transparent header logo selector
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[transparent-header-logo]',
					'default'        => astra_get_option( 'transparent-header-logo' ),
					'type'           => 'control',
					'control'        => 'image',
					'section'        => 'section-transparent-header',
					'required'       => array( ASTRA_THEME_SETTINGS . '[different-transparent-logo]', '==', true ),
					'priority'       => 25,
					'title'          => __( 'Logo', 'astra-addon' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
				),

				/**
				 * Option: Different retina logo
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-transparent-retina-logo]',
					'default'  => false,
					'type'     => 'control',
					'section'  => 'section-transparent-header',
					'title'    => __( 'Different Logo for retina devices?', 'astra-addon' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[different-transparent-logo]', '==', true ),
					'priority' => 25,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Transparent header logo selector
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[transparent-header-retina-logo]',
					'default'        => astra_get_option( 'transparent-header-retina-logo' ),
					'type'           => 'control',
					'control'        => 'image',
					'section'        => 'section-transparent-header',
					'required'       => array( ASTRA_THEME_SETTINGS . '[different-transparent-retina-logo]', '==', true ),
					'priority'       => 25,
					'title'          => __( 'Retina Logo', 'astra-addon' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
				),

				/**
				 * Option: Transparent header logo width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-logo-width]',
					'default'     => astra_get_option( 'transparent-header-logo-width' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive-slider',
					'section'     => 'section-transparent-header',
					'required'    => array( ASTRA_THEME_SETTINGS . '[different-transparent-logo]', '==', true ),
					'priority'    => 25,
					'title'       => __( 'Logo Width', 'astra-addon' ),
					'input_attrs' => array(
						'min'  => 50,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Bottom Border Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[transparent-header-main-sep]',
					'default'     => astra_get_option( 'transparent-header-main-sep' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'number',
					'required'    => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', true ),
					'section'     => 'section-transparent-header',
					'priority'    => 25,
					'title'       => __( 'Bottom Border Size', 'astra-addon' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Bottom Border Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[transparent-header-main-sep-color]',
					'default'   => '',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-color',
					'required'  => array( ASTRA_THEME_SETTINGS . '[transparent-header-enable]', '==', true ),
					'section'   => 'section-transparent-header',
					'priority'  => 30,
					'title'     => __( 'Bottom Border Color', 'astra-addon' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Transparent_Header_Configs;

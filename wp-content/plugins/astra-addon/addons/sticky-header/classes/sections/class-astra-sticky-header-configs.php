<?php
/**
 * Sticky Header Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {
	return;
}

if ( ! class_exists( 'Astra_Sticky_Header_Configs' ) ) {

	/**
	 * Register Sticky Header Customizer Configurations.
	 */
	class Astra_Sticky_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Sticky Header Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_config = array(
				/**
				 * Option: Stick Primary Header
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-stick]',
					'default'  => astra_get_option( 'header-main-stick' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Stick Primary Header', 'astra-addon' ),
					'priority' => 10,
					'control'  => 'checkbox',
				),
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-sticky-logo]',
					'default'  => astra_get_option( 'different-sticky-logo' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Different Logo for Sticky Header?', 'astra-addon' ),
					'priority' => 15,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Sticky header logo selector
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[sticky-header-logo]',
					'default'        => astra_get_option( 'sticky-header-logo' ),
					'type'           => 'control',
					'control'        => 'image',
					'section'        => 'section-sticky-header',
					'priority'       => 15,
					'title'          => __( 'Sticky Logo', 'astra-addon' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'required'       => array( ASTRA_THEME_SETTINGS . '[different-sticky-logo]', '==', 1 ),
				),

				/**
				 * Option: Different retina logo
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[different-sticky-retina-logo]',
					'default'  => false,
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Different Logo for retina devices?', 'astra-addon' ),
					'priority' => 20,
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[different-sticky-logo]', '==', 1 ),
				),

				/**
				 * Option: Sticky header logo selector
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[sticky-header-retina-logo]',
					'default'        => astra_get_option( 'sticky-header-retina-logo' ),
					'type'           => 'control',
					'control'        => 'image',
					'section'        => 'section-sticky-header',
					'priority'       => 20,
					'title'          => __( 'Sticky Retina Logo', 'astra-addon' ),
					'library_filter' => array( 'gif', 'jpg', 'jpeg', 'png', 'ico' ),
					'required'       => array( ASTRA_THEME_SETTINGS . '[different-sticky-retina-logo]', '==', 1 ),
				),

				/**
				 * Option: Sticky header logo width
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[sticky-header-logo-width]',
					'default'     => astra_get_option( 'sticky-header-logo-width' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'control'     => 'ast-responsive-slider',
					'section'     => 'section-sticky-header',
					'priority'    => 25,
					'title'       => __( 'Sticky Logo Width', 'astra-addon' ),
					'input_attrs' => array(
						'min'  => 50,
						'step' => 1,
						'max'  => 600,
					),
					'required'    => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[different-sticky-logo]', '==', 1 ),
							array( ASTRA_THEME_SETTINGS . '[different-sticky-retina-logo]', '==', 1 ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Shrink Primary Header
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-shrink]',
					'default'  => astra_get_option( 'header-main-shrink' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Enable Shrink Effect', 'astra-addon' ),
					'priority' => 35,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Enable disable mobile header
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-header-style]',
					'default'  => astra_get_option( 'sticky-header-style' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-sticky-header',
					'priority' => 40,
					'title'    => __( 'Select Animation Effect', 'astra-addon' ),
					'choices'  => array(
						'none'  => __( 'None', 'astra-addon' ),
						'slide' => __( 'Slide', 'astra-addon' ),
						'fade'  => __( 'Fade', 'astra-addon' ),
					),
					'required' => array( ASTRA_THEME_SETTINGS . '[sticky-hide-on-scroll]', '!=', 1 ),
				),

				/**
				 * Option: Hide on scroll
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-hide-on-scroll]',
					'default'  => astra_get_option( 'sticky-hide-on-scroll' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'title'    => __( 'Hide when scrolling down', 'astra-addon' ),
					'priority' => 45,
					'control'  => 'checkbox',
				),

				/**
				 * Option: Sticky Header Display On
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-header-on-devices]',
					'default'  => astra_get_option( 'sticky-header-on-devices' ),
					'type'     => 'control',
					'section'  => 'section-sticky-header',
					'priority' => 50,
					'title'    => __( 'Enable On', 'astra-addon' ),
					'control'  => 'select',
					'choices'  => array(
						'desktop' => __( 'Desktop', 'astra-addon' ),
						'mobile'  => __( 'Mobile', 'astra-addon' ),
						'both'    => __( 'Desktop + Mobile', 'astra-addon' ),
					),
				),

			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Sticky_Header_Configs;




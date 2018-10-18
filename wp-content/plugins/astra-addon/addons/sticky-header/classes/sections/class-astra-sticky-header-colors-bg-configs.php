<?php
/**
 * Sticky Header Colors Options for our theme.
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

if ( ! class_exists( 'Astra_Sticky_Header_Colors_Bg_Configs' ) ) {

	/**
	 * Register Sticky Header  ColorsCustomizer Configurations.
	 */
	class Astra_Sticky_Header_Colors_Bg_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Sticky Header Colors Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$defaults = Astra_Theme_Options::defaults();

			$_config = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-header-color-divider]',
					'title'    => __( 'Header', 'astra-addon' ),
					'section'  => 'section-colors-sticky-primary-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-bg-color-responsive]',
					'default'    => $defaults['sticky-header-bg-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				* Option: Site Title Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-color-site-title-responsive]',
					'default'    => $defaults['sticky-header-color-site-title-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Site Title Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						ASTRA_THEME_SETTINGS . '[display-site-title]',
						'==',
						true,
					),
				),
				/**
				* Option: Site Title Hover Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-color-h-site-title-responsive]',
					'default'    => $defaults['sticky-header-color-h-site-title-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Site Title Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						ASTRA_THEME_SETTINGS . '[display-site-title]',
						'==',
						true,
					),
				),
				/**
				* Option: Site Tagline Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-color-site-tagline-responsive]',
					'default'    => $defaults['sticky-header-color-site-tagline-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Site Tagline Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						ASTRA_THEME_SETTINGS . '[display-site-tagline]',
						'==',
						true,
					),
				),
				/**
				* Option: Divider
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-header-menu-color-divider]',
					'title'    => __( 'Primary Menu', 'astra-addon' ),
					'section'  => 'section-colors-sticky-primary-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),
				/**
				* Option: Menu Background Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-menu-bg-color-responsive]',
					'default'    => $defaults['sticky-header-menu-bg-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: Primary Menu Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-menu-color-responsive]',
					'default'    => $defaults['sticky-header-menu-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: Menu Hover Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-menu-h-color-responsive]',
					'default'    => $defaults['sticky-header-menu-h-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Active / Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: Menu Link / Hover Background Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-menu-h-a-bg-color-responsive]',
					'default'    => $defaults['sticky-header-menu-h-a-bg-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: Divider
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-header-submenu-color-divider]',
					'title'    => __( 'Primary Submenu', 'astra-addon' ),
					'section'  => 'section-colors-sticky-primary-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),
				/**
				* Option: SubMenu Background Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-submenu-bg-color-responsive]',
					'default'    => $defaults['sticky-header-submenu-bg-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: Primary Menu Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-submenu-color-responsive]',
					'default'    => $defaults['sticky-header-submenu-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: Menu Hover Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-submenu-h-color-responsive]',
					'default'    => $defaults['sticky-header-submenu-h-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Active / Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				* Option: SubMenu Link / Hover Background Color
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-submenu-h-a-bg-color-responsive]',
					'default'    => $defaults['sticky-header-submenu-h-a-bg-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-sticky-header-content-section]',
					'title'    => __( 'Outside menu item', 'astra-addon' ),
					'section'  => 'section-colors-sticky-primary-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),
				/**
				 * Option: Content Section Text color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-content-section-text-color-responsive]',
					'default'    => $defaults['sticky-header-content-section-text-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Content Section Link color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-content-section-link-color-responsive]',
					'default'    => $defaults['sticky-header-content-section-link-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Content Section Link Hover color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-header-content-section-link-h-color-responsive]',
					'default'    => $defaults['sticky-header-content-section-link-h-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-primary-header',
					'responsive' => true,
					'rgba'       => true,
				),
			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Sticky_Header_Colors_Bg_Configs;




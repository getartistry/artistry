<?php
/**
 * Colors and Background - Header Options for our theme.
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

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'Astra_Customizer_Colors_Transparent_Header_Configs' ) ) {

	/**
	 * Register Colors and Background - Header Options Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Transparent_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Colors and Background - Header Options Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$defaults = Astra_Theme_Options::defaults();

			$_configs = array(

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-header-bg-color-responsive]',
					'default'    => $defaults['transparent-header-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Overlay Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Site Title Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-header-color-site-title-responsive]',
					'default'    => $defaults['transparent-header-color-site-title-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Site Title Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
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
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-header-color-h-site-title-responsive]',
					'default'    => $defaults['transparent-header-color-h-site-title-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Site Title Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						ASTRA_THEME_SETTINGS . '[display-site-title]',
						'==',
						true,
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-transparent-menu-responsive]',
					'title'    => __( 'Menu', 'astra-addon' ),
					'section'  => 'section-colors-transparent-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				/**
				 * Option: Menu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-menu-bg-color-responsive]',
					'default'    => $defaults['transparent-menu-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Primary Menu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-menu-color-responsive]',
					'default'    => $defaults['transparent-menu-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Menu Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-menu-h-color-responsive]',
					'default'    => $defaults['transparent-menu-h-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Active / Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-transparent-sub-menu-responsive]',
					'title'    => __( 'Submenu', 'astra-addon' ),
					'section'  => 'section-colors-transparent-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				/**
				 * Option: Sub menu background color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-submenu-bg-color-responsive]',
					'default'    => $defaults['transparent-submenu-bg-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Sub menu text color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-submenu-color-responsive]',
					'default'    => $defaults['transparent-submenu-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Sub menu active hover color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-submenu-h-color-responsive]',
					'default'    => $defaults['transparent-submenu-h-color-responsive'],
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Active / Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				* Option: Divider
				*/
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-transparent-content-section]',
					'title'    => __( 'Content Section', 'astra-addon' ),
					'section'  => 'section-colors-transparent-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),
				/**
				* Option: Content Section Text color.
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-content-section-text-color-responsive]',
					'default'    => $defaults['transparent-content-section-text-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),
				/**
				 * Option: Content Section Link color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-content-section-link-color-responsive]',
					'default'    => $defaults['transparent-content-section-link-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Content Section Link Hover color.
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[transparent-content-section-link-h-color-responsive]',
					'default'    => $defaults['transparent-content-section-link-h-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-transparent-header',
					'responsive' => true,
					'rgba'       => true,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Colors_Transparent_Header_Configs;

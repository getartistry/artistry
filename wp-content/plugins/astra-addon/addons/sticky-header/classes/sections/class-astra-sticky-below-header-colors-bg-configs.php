<?php
/**
 * Sticky Header - Below Header Colors Options for our theme.
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

if ( ! class_exists( 'Astra_Sticky_Below_Header_Colors_Bg_Configs' ) ) {

	/**
	 * Register Sticky Header Below Header ColorsCustomizer Configurations.
	 */
	class Astra_Sticky_Below_Header_Colors_Bg_Configs extends Astra_Customizer_Config_Base {

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
			$_config  = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-below-header-color-divider]',
					'title'    => __( 'Below Header', 'astra-addon' ),
					'section'  => 'section-colors-sticky-below-header',
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-bg-color-responsive]',
					'default'    => $defaults['sticky-below-header-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'required'   => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-color-divider]',
					'title'    => __( 'Menu', 'astra-addon' ),
					'section'  => 'section-colors-sticky-below-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Menu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-bg-color-responsive]',
					'default'    => $defaults['sticky-below-header-menu-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Primary Menu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-color-responsive]',
					'default'    => $defaults['sticky-below-header-menu-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Menu Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-h-color-responsive]',
					'default'    => $defaults['sticky-below-header-menu-h-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Active / Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),
				/**
				 * Option: Menu Link / Hover Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-menu-h-a-bg-color-responsive]',
					'default'    => $defaults['sticky-below-header-menu-h-a-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-color-divider]',
					'title'    => __( 'Submenu', 'astra-addon' ),
					'section'  => 'section-colors-sticky-below-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: SubMenu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-bg-color-responsive]',
					'default'    => $defaults['sticky-below-header-submenu-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Primary Menu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-color-responsive]',
					'default'    => $defaults['sticky-below-header-submenu-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Menu Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-h-color-responsive]',
					'default'    => $defaults['sticky-below-header-submenu-h-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Active / Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: SubMenu Link / Hover Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-submenu-h-a-bg-color-responsive]',
					'default'    => $defaults['sticky-below-header-submenu-h-a-bg-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Active / Hover Background Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[divider-sticky-below-header-content-section]',
					'title'    => __( 'Content Section', 'astra-addon' ),
					'section'  => 'section-colors-sticky-below-header',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
					'required' => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
				),
				/**
				* Option: Content Section Text color.
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-text-color-responsive]',
					'default'    => $defaults['sticky-below-header-content-section-text-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Text Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
				),

				/**
				* Option: Content Section Link color.
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-link-color-responsive]',
					'default'    => $defaults['sticky-below-header-content-section-link-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
				),
				/**
				* Option: Content Section Link Hover color.
				*/
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[sticky-below-header-content-section-link-h-color-responsive]',
					'default'    => $defaults['sticky-below-header-content-section-link-h-color-responsive'],
					'type'       => 'control',
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-sticky-below-header',
					'responsive' => true,
					'rgba'       => true,
					'required'   => array(
						'conditions' => array(
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-1]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
							array(
								ASTRA_THEME_SETTINGS . '[below-header-section-2]',
								'==',
								array( 'search', 'widget', 'text-html' ),
							),
						),
						'operator'   => 'OR',
					),
				),

			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Sticky_Below_Header_Colors_Bg_Configs;




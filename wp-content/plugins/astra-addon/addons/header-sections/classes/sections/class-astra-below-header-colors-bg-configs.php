<?php
/**
 * Below Header - Colors Options for our theme.
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

if ( ! class_exists( 'Astra_Below_Header_Colors_Bg_Configs' ) ) {

	/**
	 * Register Header Layout Customizer Configurations.
	 */
	class Astra_Below_Header_Colors_Bg_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$defaults = Astra_Theme_Options::defaults();

			$_configs = array(

				/**
				 * Option: Background
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-bg-obj-responsive]',
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'transport' => 'postMessage',
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'default'   => $defaults['below-header-bg-obj-responsive'],
					'section'   => 'section-below-header-colors-bg',
					'title'     => __( 'Background', 'astra-addon' ),
				),

				/**
				 * Below Header Navigation Colors
				 */
				/**
				 * Option: Below Header Menu Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-color-bg-primary-menu-divider]',
					'title'    => __( 'Below Header Menu', 'astra-addon' ),
					'type'     => 'control',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'control'  => 'ast-divider',
					'section'  => 'section-below-header-colors-bg',
					'settings' => array(),
				),

				/**
				 * Option: Menu Background Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-menu-bg-obj-responsive]',
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'transport' => 'postMessage',
					'default'   => $defaults['below-header-menu-bg-obj-responsive'],
					'title'     => __( 'Background', 'astra-addon' ),
					'section'   => 'section-below-header-colors-bg',
				),

				/**
				 * Option: Menu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-menu-text-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['below-header-menu-text-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Menu Hover Color
				 */

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-menu-text-hover-color-responsive]',
					'transport'  => 'postMessage',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['below-header-menu-text-hover-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Menu Hover Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-menu-bg-hover-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['below-header-menu-bg-hover-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Hover Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Active Menu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-current-menu-text-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['below-header-current-menu-text-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Active Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Active Menu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-current-menu-bg-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['below-header-current-menu-bg-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Active Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-color-bg-dropdown-menu-divider]',
					'type'     => 'control',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'control'  => 'ast-divider',
					'title'    => __( 'Below Header Submenu', 'astra-addon' ),
					'section'  => 'section-below-header-colors-bg',
					'settings' => array(),
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Submenu Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-submenu-bg-color-responsive]',
					'transport'  => 'postMessage',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['below-header-submenu-bg-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'title'      => __( 'Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-submenu-text-color-responsive]',
					'transport'  => 'postMessage',
					'type'       => 'control',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['below-header-submenu-text-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-submenu-hover-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'    => $defaults['below-header-submenu-hover-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Menu Hover Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-submenu-bg-hover-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'default'    => $defaults['below-header-submenu-bg-hover-color-responsive'],
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Hover Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Submenu Active Color
				 */

				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-submenu-active-color-responsive]',
					'transport'  => 'postMessage',
					'type'       => 'control',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['below-header-submenu-active-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Active Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				// Check Astra_Control_Color is exist in the theme.
				/**
				 * Option: Submenu Active Background Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-submenu-active-bg-color-responsive]',
					'transport'  => 'postMessage',
					'type'       => 'control',
					'required'   => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['below-header-submenu-active-bg-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Active Background Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Content Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-color-bg-content-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
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
					'title'    => __( 'Content Section', 'astra-addon' ),
					'section'  => 'section-below-header-colors-bg',
					'settings' => array(),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-text-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'default'    => $defaults['below-header-text-color-responsive'],
					'transport'  => 'postMessage',
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
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Text Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-link-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
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
					'default'    => $defaults['below-header-link-color-responsive'],
					'transport'  => 'postMessage',
					'section'    => 'section-below-header-colors-bg',
					'title'      => __( 'Link Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'       => ASTRA_THEME_SETTINGS . '[below-header-link-hover-color-responsive]',
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
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
					'default'    => $defaults['below-header-link-hover-color-responsive'],
					'section'    => 'section-below-header-colors-bg',
					'transport'  => 'postMessage',
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'responsive' => true,
					'rgba'       => true,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new Astra_Below_Header_Colors_Bg_Configs;

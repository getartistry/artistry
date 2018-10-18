<?php
/**
 * Below Header - Layout Options for our theme.
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

if ( ! class_exists( 'Astra_Below_Header_Configs' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class Astra_Below_Header_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$sections = apply_filters(
				'astra_header_section_elements',
				array(
					'none'      => __( 'None', 'astra-addon' ),
					'menu'      => __( 'Menu', 'astra-addon' ),
					'search'    => __( 'Search', 'astra-addon' ),
					'text-html' => __( 'Text / HTML', 'astra-addon' ),
					'widget'    => __( 'Widget', 'astra-addon' ),
				),
				'below-header'
			);

			$_config = array(

				/**
				 * Option: Below Header Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-layout]',
					'section'  => 'section-below-header',
					'default'  => astra_get_option( 'below-header-layout' ),
					'priority' => 5,
					'title'    => __( 'Below Header Layout', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'choices'  => array(
						'disabled'              => array(
							'label' => __( 'Disabled', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/disabled-76x48.png',
						),
						'below-header-layout-1' => array(
							'label' => __( 'Below Header Layout 1', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/below-header-1-76x48.png',
						),
						'below-header-layout-2' => array(
							'label' => __( 'Below Header Layout 2', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/below-header-2-76x48.png',
						),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-layout-section-1-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'section'  => 'section-below-header',
					'title'    => __( 'Section 1', 'astra-addon' ),
					'priority' => 10,
					'settings' => array(),
				),

				/**
				 * Option: Section 1
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-section-1]',
					'section'  => 'section-below-header',
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'  => 'select',
					'default'  => astra_get_option( 'below-header-section-1' ),
					'priority' => 15,
					'choices'  => $sections,
				),

				/**
				 * Option: Text/HTML
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-section-1-html]',
					'section'   => 'section-below-header',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'below-header-section-1-html' ),
					'priority'  => 20,
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'text-html' ),
						),
					),
					'title'     => __( 'Text/HTML', 'astra-addon' ),
					'type'      => 'control',
					'control'   => 'textarea',
					'partials'  => array(
						'selector'            => '.below-header-section-1 .user-select .ast-custom-html',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_below_header_section_1' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-layout-section-2-divider]',
					'section'  => 'section-below-header',
					'title'    => __( 'Section 2', 'astra-addon' ),
					'priority' => 30,
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '==', 'below-header-layout-1' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				/**
				 * Option: Section 2
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-section-2]',
					'default'  => astra_get_option( 'below-header-section-2' ),
					'section'  => 'section-below-header',
					'priority' => 35,
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '==', 'below-header-layout-1' ),
					'type'     => 'control',
					'control'  => 'select',
					'choices'  => $sections,
				),

				/**
				 * Option: Text/HTML
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-section-2-html]',
					'section'   => 'section-below-header',
					'type'      => 'control',
					'control'   => 'textarea',
					'default'   => astra_get_option( 'below-header-section-2-html' ),
					'transport' => 'postMessage',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '==', 'below-header-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'text-html' ),
						),
					),
					'partials'  => array(
						'selector'            => '.below-header-section-2 .user-select .ast-custom-html',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_below_header_section_2' ),
					),
					'priority'  => 40,
					'title'     => __( 'Text/HTML', 'astra-addon' ),
				),

				/**
				 * Option: Below Header Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-layout-options-separator-divider]',
					'section'  => 'section-below-header',
					'priority' => 50,
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),

				/**
				 * Option: Below Header Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[below-header-separator]',
					'section'     => 'section-below-header',
					'priority'    => 55,
					'default'     => astra_get_option( 'below-header-separator' ),
					'title'       => __( 'Bottom Border', 'astra-addon' ),
					'type'        => 'control',
					'required'    => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'     => 'number',
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
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-bottom-border-color]',
					'transport' => 'postMessage',
					'default'   => '',
					'type'      => 'control',
					'required'  => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'   => 'ast-color',
					'section'   => 'section-below-header',
					'priority'  => 60,
					'title'     => __( 'Bottom Border Color', 'astra-addon' ),
				),

				/**
				 * Option: Below Header Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[below-header-height]',
					'section'     => 'section-below-header',
					'transport'   => 'postMessage',
					'default'     => 60,
					'priority'    => 75,
					'required'    => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'title'       => __( 'Below Header Height', 'astra-addon' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 30,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Below Header Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-submenu-border-divider]',
					'section'  => 'section-below-header',
					'priority' => 75,
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'type'     => 'control',
					'control'  => 'ast-divider',
					'settings' => array(),
				),
				/**
				 * Option: Submenu Border
				 */
				array(
					'name'           => ASTRA_THEME_SETTINGS . '[below-header-submenu-border]',
					'default'        => astra_get_option( 'below-header-submenu-border' ),
					'type'           => 'control',
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'priority'       => 75,
					'required'       => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'        => 'section-below-header',
					'title'          => __( 'Submenu Container Border', 'astra-addon' ),
					'linked_choices' => true,
					'choices'        => array(
						'top'    => __( 'Top', 'astra-addon' ),
						'right'  => __( 'Right', 'astra-addon' ),
						'bottom' => __( 'Bottom', 'astra-addon' ),
						'left'   => __( 'Left', 'astra-addon' ),
					),
				),

				/**
				 * Option: Submenu Border Color
				 */

				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-submenu-border-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'priority'  => 75,
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'default'   => astra_get_option( 'below-header-submenu-border-color' ),
					'section'   => 'section-below-header',
					'title'     => __( 'Border Color', 'astra-addon' ),
				),

				/**
				 * Option: Submenu Item Border
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-submenu-item-border]',
					'default'   => astra_get_option( 'below-header-submenu-item-border' ),
					'type'      => 'control',
					'control'   => 'checkbox',
					'transport' => 'postMessage',
					'priority'  => 75,
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'section'   => 'section-below-header',
					'title'     => __( 'Submenu Item Border', 'astra-addon' ),
				),

				/**
				 * Option: Submenu Item Border Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-submenu-item-b-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'priority'  => 75,
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[below-header-submenu-item-border]',
						'==',
						true,
					),
					'default'   => astra_get_option( 'below-header-submenu-item-b-color' ),
					'section'   => 'section-below-header',
					'title'     => __( 'Submenu Item Border Color', 'astra-addon' ),
				),

				// Option: Submenu Container Animation.
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-submenu-container-animation]',
					'default'  => astra_get_option( 'below-header-submenu-container-animation' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-below-header',
					'priority' => 75,
					'title'    => __( 'Submenu Container Animation', 'astra-addon' ),
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-section-1]', '==', 'menu' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-section-2]', '==', 'menu' ),
						),
						'operator'   => 'OR',
					),
					'choices'  => array(
						''           => __( 'Default', 'astra-addon' ),
						'slide-down' => __( 'Slide Down', 'astra-addon' ),
						'slide-up'   => __( 'Slide Up', 'astra-addon' ),
						'fade'       => __( 'Fade', 'astra-addon' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-mobile-menu-divider]',
					'section'  => 'section-below-header',
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'control'  => 'ast-heading',
					'priority' => 100,
					'title'    => __( 'Mobile Header', 'astra-addon' ),
					'settings' => array(),
				),

				/**
				 * Option: Display Below Header on Mobile
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-on-mobile]',
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'default'  => astra_get_option( 'below-header-on-mobile' ),
					'section'  => 'section-below-header',
					'title'    => __( 'Display on mobile devices', 'astra-addon' ),
					'priority' => 105,
				),

				/**
				 * Option: Merged with primary header menu
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[below-header-merge-menu]',
					'type'        => 'control',
					'control'     => 'checkbox',
					'required'    => array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '!=', 'disabled' ),
					'default'     => astra_get_option( 'below-header-merge-menu' ),
					'section'     => 'section-below-header',
					'title'       => __( 'Merge menu on mobile devices', 'astra-addon' ),
					'description' => __( 'You can merge menu with Primary menu in mobile devices by enabling this option.', 'astra-addon' ),
					'priority'    => 105,
				),

				/**
				 * Option: Mobile Menu Alignment
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-menu-align]',
					'section'  => 'section-below-header',
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'default'  => astra_get_option( 'below-header-menu-align' ),
					'priority' => 110,
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '==', 'below-header-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
						),
					),
					'title'    => __( 'Mobile Header Alignment', 'astra-addon' ),
					'choices'  => array(
						'inline' => array(
							'label' => __( 'Inline', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/above-header-1-76x47.png',
						),
						'stack'  => array(
							'label' => __( 'Stack', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/mobile-header-stack-76x48.png',
						),
					),
				),

				/**
				 * Option: Mobile Menu Label
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[below-header-menu-label]',
					'section'   => 'section-below-header',
					'type'      => 'control',
					'control'   => 'text',
					'transport' => 'postMessage',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[below-header-merge-menu]', '!=', true ),
						),
					),
					'priority'  => 107,
					'default'   => astra_get_option( 'below-header-menu-label' ),
					'title'     => __( 'Menu Label on Small Devices', 'astra-addon' ),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[below-header-swap-mobile]',
					'default'  => astra_get_option( 'below-header-section-swap-mobile' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[below-header-layout]', '==', 'below-header-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[below-header-on-mobile]', '==', true ),
						),
						'operator'   => 'AND',
					),
					'section'  => 'section-below-header',
					'title'    => __( 'Swap sections on mobile devices', 'astra-addon' ),
					'priority' => 120,
				),
			);

			return array_merge( $configurations, $_config );
		}
	}
}

new Astra_Below_Header_Configs;



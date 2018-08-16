<?php
/**
 * Above Header - Layout Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2015, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Astra_Above_Header_Configs' ) ) {

	/**
	 * Register Header Layout Customizer Configurations.
	 */
	class Astra_Above_Header_Configs extends Astra_Customizer_Config_Base {

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
					''          => __( 'None', 'astra-addon' ),
					'menu'      => __( 'Menu', 'astra-addon' ),
					'search'    => __( 'Search', 'astra-addon' ),
					'text-html' => __( 'Text / HTML', 'astra-addon' ),
					'widget'    => __( 'Widget', 'astra-addon' ),
				),
				'above-header'
			);

			$_config = array(

				/**
				 * Option: Above Header Layout
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-layout]',
					'section'  => 'section-above-header',
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'default'  => astra_get_option( 'above-header-layout' ),
					'priority' => 1,
					'title'    => __( 'Above Header Layout', 'astra-addon' ),
					'choices'  => array(
						'disabled'              => array(
							'label' => __( 'Disabled', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/disabled-76x48.png',
						),
						'above-header-layout-1' => array(
							'label' => __( 'Above Header Layout 1', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/above-header-1-76x47.png',
						),
						'above-header-layout-2' => array(
							'label' => __( 'Above Header Layout 2', 'astra-addon' ),
							'path'  => ASTRA_EXT_HEADER_SECTIONS_URL . '/assets/images/above-header-2-76x47.png',
						),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-layout-section-1-divider]',
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'control'  => 'ast-divider',
					'section'  => 'section-above-header',
					'title'    => __( 'Section 1', 'astra-addon' ),
					'priority' => 5,
					'settings' => array(),
				),

				/**
				 *  Section: Section
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-section-1]',
					'default'  => astra_get_option( 'above-header-section-1' ),
					'type'     => 'control',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'control'  => 'select',
					'section'  => 'section-above-header',
					'priority' => 35,
					'choices'  => $sections,
				),

				/**
				 * Option: Text/HTML
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-section-1-html]',
					'section'   => 'section-above-header',
					'type'      => 'control',
					'control'   => 'textarea',
					'default'   => astra_get_option( 'above-header-section-1-html' ),
					'transport' => 'postMessage',
					'priority'  => 50,
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-1]', '==', 'text-html' ),
						),
					),
					'partials'  => array(
						'selector'            => '.ast-above-header-section-1 .user-select  .ast-custom-html',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_above_header_section_1_html' ),
					),
					'title'     => __( 'Text/HTML', 'astra-addon' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-layout-section-2-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '==', 'above-header-layout-1' ),
					'section'  => 'section-above-header',
					'title'    => __( 'Section 2', 'astra-addon' ),
					'priority' => 55,
					'settings' => array(),
				),

				/**
				 * Option: Section 2
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-section-2]',
					'type'     => 'control',
					'control'  => 'select',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '==', 'above-header-layout-1' ),
					'section'  => 'section-above-header',
					'priority' => 60,
					'default'  => astra_get_option( 'above-header-section-2' ),
					'choices'  => $sections,
				),

				/**
				 * Option: Text/HTML
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-section-2-html]',
					'type'      => 'control',
					'control'   => 'textarea',
					'section'   => 'section-above-header',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'above-header-section-2-html' ),
					'priority'  => 75,
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-section-2]', '==', 'text-html' ),
						),
					),
					'partials'  => array(
						'selector'            => '.ast-above-header-section-2 .user-select .ast-custom-html',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Header_Sections_Partials', '_render_above_header_section_2_html' ),
					),
					'title'     => __( 'Text/HTML', 'astra-addon' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[section-ast-above-header-border]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'section'  => 'section-above-header',
					'priority' => 80,
					'settings' => array(),
				),

				/**
				 * Option: Above Header Bottom Border
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[above-header-divider]',
					'section'     => 'section-above-header',
					'priority'    => 85,
					'transport'   => 'postMessage',
					'required'    => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'default'     => astra_get_option( 'above-header-divider' ),
					'title'       => __( 'Above Header Bottom Border', 'astra-addon' ),
					'type'        => 'control',
					'control'     => 'number',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Above Header Bottom Border Color
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-divider-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'default'   => '',
					'required'  => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'section'   => 'section-above-header',
					'priority'  => 90,
					'title'     => __( 'Above Header Bottom Border Color', 'astra-addon' ),
				),

				/**
				 * Option: Above Header Height
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[above-header-height]',
					'section'     => 'section-above-header',
					'priority'    => 95,
					'transport'   => 'postMessage',
					'title'       => __( 'Above Header Height', 'astra-addon' ),
					'default'     => 40,
					'required'    => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
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
				 * Option: Mobile Menu Label Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-mobile-menu-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'section'  => 'section-above-header',
					'title'    => __( 'Mobile Header', 'astra-addon' ),
					'priority' => 100,
					'settings' => array(),
				),

				/**
				 * Option: Display Above Header on Mobile
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-on-mobile]',
					'type'     => 'control',
					'control'  => 'checkbox',
					'default'  => astra_get_option( 'above-header-on-mobile' ),
					'required' => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'section'  => 'section-above-header',
					'title'    => __( 'Display on mobile devices', 'astra-addon' ),
					'priority' => 101,
				),

				/**
				 * Option: Merged with primary header menu
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[above-header-merge-menu]',
					'default'     => astra_get_option( 'above-header-merge-menu' ),
					'type'        => 'control',
					'control'     => 'checkbox',
					'required'    => array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '!=', 'disabled' ),
					'section'     => 'section-above-header',
					'title'       => __( 'Merge menu on mobile devices', 'astra-addon' ),
					'description' => __( 'You can merge menu with Primary menu in mobile devices by enabling this option.', 'astra-addon' ),
					'priority'    => 101,
				),

				/**
				 * Option: Mobile Menu Alignment
				 */

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-menu-align]',
					'default'  => astra_get_option( 'above-header-menu-align' ),
					'section'  => 'section-above-header',
					'priority' => 105,
					'title'    => __( 'Mobile Header Alignment', 'astra-addon' ),
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '==', 'above-header-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
						),
					),
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
					'name'      => ASTRA_THEME_SETTINGS . '[above-header-menu-label]',
					'type'      => 'control',
					'control'   => 'text',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
							array( ASTRA_THEME_SETTINGS . '[above-header-merge-menu]', '!=', true ),
						),
					),
					'section'   => 'section-above-header',
					'default'   => astra_get_option( 'above-header-menu-label' ),
					'transport' => 'postMessage',
					'priority'  => 103,
					'title'     => __( 'Menu Label on Small Devices', 'astra-addon' ),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[above-header-swap-mobile]',
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[above-header-layout]', '==', 'above-header-layout-1' ),
							array( ASTRA_THEME_SETTINGS . '[above-header-on-mobile]', '==', true ),
						),
					),
					'section'  => 'section-above-header',
					'default'  => astra_get_option( 'above-header-section-swap-mobile' ),
					'title'    => __( 'Swap sections on mobile devices', 'astra-addon' ),
					'priority' => 115,
				),
			);

			return array_merge( $configurations, $_config );
		}

	}
}

new Astra_Above_Header_Configs;

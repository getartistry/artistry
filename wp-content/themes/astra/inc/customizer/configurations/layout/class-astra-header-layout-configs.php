<?php
/**
 * General Options for Astra Theme.
 *
 * @package     Astra
 * @author      Astra
 * @copyright   Copyright (c) 2018, Astra
 * @link        http://wpastra.com/
 * @since       Astra 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Header_Layout_Configs' ) ) {

	/**
	 * Register Header Layout Customizer Configurations.
	 */
	class Astra_Header_Layout_Configs extends Astra_Customizer_Config_Base {

		/**
		 * Register Header Layout Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$header_rt_sections = array(
				'none'      => __( 'None', 'astra' ),
				'search'    => __( 'Search', 'astra' ),
				'text-html' => __( 'Text / HTML', 'astra' ),
				'widget'    => __( 'Widget', 'astra' ),
			);

			$_configs = array(

				/**
				 * Option: Header Layout
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-layouts]',
					'default'  => astra_get_option( 'header-layouts' ),
					'section'  => 'section-header',
					'priority' => 5,
					'title'    => __( 'Header Layout', 'astra' ),
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'choices'  => array(
						'header-main-layout-1' => array(
							'label' => __( 'Logo Left', 'astra' ),
							'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-1-76x48.png',
						),
						'header-main-layout-2' => array(
							'label' => __( 'Logo Center', 'astra' ),
							'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-2-76x48.png',
						),
						'header-main-layout-3' => array(
							'label' => __( 'Logo Right', 'astra' ),
							'path'  => ASTRA_THEME_URI . '/assets/images/header-layout-3-76x48.png',
						),
					),
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
					'default'  => astra_get_option( 'disable-primary-nav' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'section'  => 'section-header',
					'title'    => __( 'Disable Menu', 'astra' ),
					'priority' => 5,
				),

				/**
				 * Option: Last Item in Menu
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
					'default'  => astra_get_option( 'header-main-rt-section' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'priority' => 5,
					'title'    => __( 'Last Item in Menu', 'astra' ),
					'choices'  => $header_rt_sections,
				),

				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-rt-section]',
					'default'  => astra_get_option( 'header-main-rt-section' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'priority' => 5,
					'title'    => __( 'Last Item in Menu', 'astra' ),
					'choices'  => apply_filters(
						'astra_header_section_elements',
						array(
							'none'      => __( 'None', 'astra' ),
							'search'    => __( 'Search', 'astra' ),
							'text-html' => __( 'Text / HTML', 'astra' ),
							'widget'    => __( 'Widget', 'astra' ),
						),
						'primary-header'
					),
				),

				/**
				 * Option: Right Section Text / HTML
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-rt-section-html]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-main-rt-section-html' ),
					'type'      => 'control',
					'control'   => 'textarea',
					'section'   => 'section-header',
					'required'  => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '===', 'text-html' ),
					'priority'  => 10,
					'partial'   => array(
						'selector'            => '.main-header-bar .ast-masthead-custom-menu-items .ast-custom-html',
						'container_inclusive' => false,
						'render_callback'     => array( 'Astra_Customizer_Partials', '_render_header_main_rt_section_html' ),
					),
					'title'     => __( 'Custom Menu Text / HTML', 'astra' ),
				),

				/**
				 * Option: Bottom Border Size
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[header-main-sep]',
					'transport'   => 'postMessage',
					'default'     => astra_get_option( 'header-main-sep' ),
					'type'        => 'control',
					'control'     => 'number',
					'section'     => 'section-header',
					'priority'    => 25,
					'title'       => __( 'Header Bottom Border Size', 'astra' ),
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
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-sep-color]',
					'transport' => 'postMessage',
					'default'   => '',
					'type'      => 'control',
					'required'  => array( ASTRA_THEME_SETTINGS . '[header-main-sep]', '>=', 1 ),
					'control'   => 'ast-color',
					'section'   => 'section-header',
					'priority'  => 30,
					'title'     => __( 'Header Bottom Border Color', 'astra' ),
				),

				/**
				 * Option: Header Width
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-layout-width]',
					'default'  => astra_get_option( 'header-main-layout-width' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'priority' => 30,
					'title'    => __( 'Header Width', 'astra' ),
					'choices'  => array(
						'full'    => __( 'Full Width', 'astra' ),
						'content' => __( 'Content Width', 'astra' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[primary-submenu-border-divider]',
					'type'     => 'control',
					'control'  => 'ast-divider',
					'section'  => 'section-header',
					'priority' => 30,
					'settings' => array(),
				),

				// Option: Primary Menu Border.
				array(
					'type'           => 'control',
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'name'           => ASTRA_THEME_SETTINGS . '[primary-submenu-border]',
					'section'        => 'section-header',
					'linked_choices' => true,
					'priority'       => 30,
					'default'        => astra_get_option( 'primary-submenu-border' ),
					'title'          => __( 'Submenu Container Border', 'astra' ),
					'choices'        => array(
						'top'    => __( 'Top', 'astra' ),
						'right'  => __( 'Right', 'astra' ),
						'bottom' => __( 'Bottom', 'astra' ),
						'left'   => __( 'Left', 'astra' ),
					),
				),

				// Option: Submenu Container Border Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-b-color]',
					'default'   => '',
					'title'     => __( 'Submenu Border Color', 'astra' ),
					'section'   => 'section-header',
					'priority'  => 30,
				),

				array(
					'type'      => 'control',
					'control'   => 'checkbox',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-item-border]',
					'section'   => 'section-header',
					'priority'  => 30,
					'default'   => astra_get_option( 'primary-submenu-item-border' ),
					'title'     => __( 'Submenu Item Border', 'astra' ),
				),

				// Option: Submenu item Border Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[primary-submenu-item-b-color]',
					'default'   => '',
					'title'     => __( 'Submenu Item Border Color', 'astra' ),
					'section'   => 'section-header',
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[primary-submenu-item-border]',
						'==',
						true,
					),
					'priority'  => 30,
				),

				/**
				 * Option: Submenu Container Animation
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-submenu-container-animation]',
					'default'  => astra_get_option( 'header-main-submenu-container-animation' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'required' => array(
						ASTRA_THEME_SETTINGS . '[disable-primary-nav]',
						'!=',
						true,
					),
					'priority' => 34,
					'title'    => __( 'Submenu Container Animation', 'astra' ),
					'choices'  => array(
						''           => __( 'Default', 'astra' ),
						'slide-down' => __( 'Slide Down', 'astra' ),
						'slide-up'   => __( 'Slide Up', 'astra' ),
						'fade'       => __( 'Fade', 'astra' ),
					),
				),

				/**
				 * Option: Mobile Menu Label Divider
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-menu-label-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-header',
					'priority' => 35,
					'title'    => __( 'Mobile Header', 'astra' ),
					'settings' => array(),
				),

				/**
				 * Option: Mobile Header Breakpoint
				 */
				array(
					'name'              => ASTRA_THEME_SETTINGS . '[mobile-header-breakpoint]',
					'default'           => '',
					'type'              => 'control',
					'control'           => 'ast-slider',
					'section'           => 'section-header',
					'priority'          => 40,
					'sanitize_callback' => array( 'Astra_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Menu Breakpoint', 'astra' ),
					'suffix'            => '',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 10,
						'max'  => 6000,
					),
				),

				/**
				 * Option: Toggle on click of button or link.
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-target]',
					'default'  => astra_get_option( 'mobile-header-toggle-target' ),
					'type'     => 'control',
					'control'  => 'select',
					'section'  => 'section-header',
					'priority' => 41,
					'title'    => __( 'Dropdown Target', 'astra' ),
					'suffix'   => '',
					'choices'  => array(
						'icon' => __( 'Icon', 'astra' ),
						'link' => __( 'Link', 'astra' ),
					),
				),

				/**
				 * Option: Mobile Menu Label
				 */
				array(
					'name'      => ASTRA_THEME_SETTINGS . '[header-main-menu-label]',
					'transport' => 'postMessage',
					'default'   => astra_get_option( 'header-main-menu-label' ),
					'section'   => 'section-header',
					'required'  => array(
						'conditions' => array(
							array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '!=', array( 'none' ) ),
							array( ASTRA_THEME_SETTINGS . '[disable-primary-nav]', '!=', array( '1' ) ),
						),
						'operator'   => 'OR',
					),
					'priority'  => 45,
					'title'     => __( 'Menu Label on Small Devices', 'astra' ),
					'type'      => 'control',
					'control'   => 'text',
				),

				/**
				 * Option: Hide Last item in Menu on mobile device
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]',
					'default'  => astra_get_option( 'hide-custom-menu-mobile' ),
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[header-main-rt-section]', '!=', 'none' ),
					'section'  => 'section-header',
					'title'    => __( 'Hide Last item in Menu on mobile', 'astra' ),
					'priority' => 45,
				),

				/**
				 * Option: Display outside menu
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-display-outside-menu]',
					'type'     => 'control',
					'control'  => 'checkbox',
					'required' => array( ASTRA_THEME_SETTINGS . '[hide-custom-menu-mobile]', '!=', '1' ),
					'default'  => astra_get_option( 'header-display-outside-menu' ),
					'section'  => 'section-header',
					'title'    => __( 'Take Last item in Menu outside menu', 'astra' ),
					'priority' => 45,
				),

				/**
				 * Option: Mobile Menu Alignment
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[header-main-menu-align]',
					'default'  => astra_get_option( 'header-main-menu-align' ),
					'type'     => 'control',
					'control'  => 'ast-radio-image',
					'choices'  => array(
						'inline' => array(
							'label' => __( 'Inline', 'astra' ),
							'path'  => ASTRA_THEME_URI . '/assets/images/mobile-inline-layout-76x48.png',
						),
						'stack'  => array(
							'label' => __( 'Stack', 'astra' ),
							'path'  => ASTRA_THEME_URI . '/assets/images/mobile-stack-layout-76x48.png',
						),
					),
					'section'  => 'section-header',
					'priority' => 50,
					'title'    => __( 'Mobile Header Alignment', 'astra' ),
				),

				/**
				 * Option: Toggle Button Style
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'default'  => astra_get_option( 'mobile-header-toggle-btn-style' ),
					'section'  => 'section-header',
					'title'    => __( 'Toggle Button Style', 'astra' ),
					'type'     => 'control',
					'control'  => 'select',
					'priority' => 42,
					'choices'  => array(
						'fill'    => __( 'Fill', 'astra' ),
						'outline' => __( 'Outline', 'astra' ),
						'minimal' => __( 'Minimal', 'astra' ),
					),
				),

				/**
				 * Option: Toggle Button Color
				 */
				array(
					'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style-color]',
					'default'  => astra_get_option( 'mobile-header-toggle-btn-style-color' ),
					'type'     => 'control',
					'control'  => 'ast-color',
					'title'    => __( 'Toggle Button Color', 'astra' ),
					'section'  => 'section-header',
					'priority' => 42,
				),
				/**
				 * Option: Border Radius
				 */
				array(
					'name'        => ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-border-radius]',
					'default'     => astra_get_option( 'mobile-header-toggle-btn-border-radius' ),
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-header',
					'title'       => __( 'Border Radius', 'astra' ),
					'required'    => array( ASTRA_THEME_SETTINGS . '[mobile-header-toggle-btn-style]', '!=', 'minimal' ),
					'priority'    => 42,
					'suffix'      => '',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if Astra Pro is not activated.
			if ( ! defined( 'ASTRA_EXT_VER' ) ) {

				$config = array(

					/**
					 * Option: Divider
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-more-feature-divider]',
						'type'     => 'control',
						'control'  => 'ast-divider',
						'section'  => 'section-header',
						'priority' => 999,
						'settings' => array(),
					),

					/**
					 * Option: Learn More about Mobile Header
					 */
					array(
						'name'     => ASTRA_THEME_SETTINGS . '[mobile-header-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-header',
						'priority' => 999,
						'title'    => '',
						'help'     => '<p>' . __( 'More Options Available for Mobile Header in Astra Pro!', 'astra' ) . '</p><a href="' . astra_get_pro_url( 'https://wpastra.com/docs/mobile-header-with-astra/', 'customizer', 'learn-more', 'upgrade-to-pro' ) . '" class="button button-primary"  target="_blank" rel="noopener">' . __( 'Learn More', 'astra' ) . '</a>',
						'settings' => array(),
					),
				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}


new Astra_Header_Layout_Configs();





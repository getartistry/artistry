<?php
/**
 * Colors Header Options for our theme.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
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
if ( ! class_exists( 'Astra_Customizer_Colors_Header' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Header extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$defaults = Astra_Theme_Options::defaults();

			$_configs = array(

				// Option: Site Title Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[header-color-site-title]',
					'title'     => __( 'Site Title Color', 'astra-addon' ),
					'section'   => 'section-colors-primary-menu',
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[display-site-title]',
						'==',
						true,
					),
				),

				// Option: Site Title Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[header-color-h-site-title]',
					'default'   => '',
					'title'     => __( 'Site Title Hover Color', 'astra-addon' ),
					'section'   => 'section-colors-primary-menu',
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[display-site-title]',
						'==',
						true,
					),
				),

				// Option: Site Tagline Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[header-color-site-tagline]',
					'default'   => '',
					'title'     => __( 'Site Tagline Color', 'astra-addon' ),
					'section'   => 'section-colors-primary-menu',
					'required'  => array(
						ASTRA_THEME_SETTINGS . '[display-site-tagline]',
						'==',
						true,
					),
				),

				array(
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[header-bg-obj-responsive]',
					'default'   => $defaults['header-bg-obj-responsive'],
					'title'     => __( 'Background', 'astra-addon' ),
					'section'   => 'section-colors-primary-menu',
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Colors_Header;

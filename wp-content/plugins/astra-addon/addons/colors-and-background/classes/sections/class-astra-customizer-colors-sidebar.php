<?php
/**
 * Colors Sidebar Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Colors_Sidebar' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Sidebar extends Astra_Customizer_Config_Base {

		/**
		 * Register General Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				// Option: Sidebar Background.
				array(
					'type'    => 'control',
					'control' => 'ast-background',
					'name'    => ASTRA_THEME_SETTINGS . '[sidebar-bg-obj]',
					'default' => astra_get_option( 'sidebar-bg-obj' ),
					'section' => 'section-colors-sidebar',
					'title'   => __( 'Background', 'astra-addon' ),
				),

				// Option: Widget Title Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sidebar-widget-title-color]',
					'title'     => __( 'Widget Title Color', 'astra-addon' ),
					'section'   => 'section-colors-sidebar',
				),

				// Option: Text Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sidebar-text-color]',
					'title'     => __( 'Text Color', 'astra-addon' ),
					'section'   => 'section-colors-sidebar',
				),

				// Option: Link Color.
				array(
					'type'    => 'control',
					'control' => 'ast-color',
					'default' => '',
					'name'    => ASTRA_THEME_SETTINGS . '[sidebar-link-color]',
					'title'   => __( 'Link Color', 'astra-addon' ),
					'section' => 'section-colors-sidebar',
				),

				// Option: Link Hover Color.
				array(
					'type'      => 'control',
					'control'   => 'ast-color',
					'default'   => '',
					'transport' => 'postMessage',
					'name'      => ASTRA_THEME_SETTINGS . '[sidebar-link-h-color]',
					'title'     => __( 'Link Hover Color', 'astra-addon' ),
					'section'   => 'section-colors-sidebar',
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Customizer_Colors_Sidebar;

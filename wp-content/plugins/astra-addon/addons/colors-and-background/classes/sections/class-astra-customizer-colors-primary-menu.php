<?php
/**
 * Colors Primary Menu Options for our theme.
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
if ( ! class_exists( 'Astra_Customizer_Colors_Primary_Menu' ) ) {

	/**
	 * Register General Customizer Configurations.
	 */
	class Astra_Customizer_Colors_Primary_Menu extends Astra_Customizer_Config_Base {

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

				// Option: Divider.
				array(
					'type'     => 'control',
					'control'  => 'ast-divider',
					'default'  => '',
					'name'     => ASTRA_THEME_SETTINGS . '[divider-primary-menu]',
					'title'    => __( 'Primary Menu', 'astra-addon' ),
					'section'  => 'section-colors-primary-menu',
					'settings' => array(),
				),

				// Option: Menu Background image, color.
				array(
					'type'    => 'control',
					'control' => 'ast-responsive-background',
					'default' => $defaults['primary-menu-bg-obj-responsive'],
					'name'    => ASTRA_THEME_SETTINGS . '[primary-menu-bg-obj-responsive]',
					'title'   => __( 'Background', 'astra-addon' ),
					'section' => 'section-colors-primary-menu',
				),

				// Option: Primary Menu Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-menu-color-responsive]',
					'default'    => $defaults['primary-menu-color-responsive'],
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Menu Hover Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-menu-h-color-responsive]',
					'default'    => $defaults['primary-menu-h-color-responsive'],
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Menu Hover Background Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-menu-h-bg-color-responsive]',
					'default'    => $defaults['primary-menu-h-bg-color-responsive'],
					'title'      => __( 'Hover Background Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Active Menu Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-menu-a-color-responsive]',
					'default'    => $defaults['primary-menu-a-color-responsive'],
					'title'      => __( 'Active Link Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Active Menu Background Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-menu-a-bg-color-responsive]',
					'default'    => $defaults['primary-menu-a-bg-color-responsive'],
					'title'      => __( 'Active Background Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Divider.
				array(
					'type'     => 'control',
					'control'  => 'ast-divider',
					'name'     => ASTRA_THEME_SETTINGS . '[divider-primary-sub-menu]',
					'title'    => __( 'Primary Submenu', 'astra-addon' ),
					'section'  => 'section-colors-primary-menu',
					'settings' => array(),
				),

				// Option: Submenu Background Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-submenu-bg-color-responsive]',
					'default'    => $defaults['primary-submenu-bg-color-responsive'],
					'title'      => __( 'Background Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Submenu Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-submenu-color-responsive]',
					'default'    => $defaults['primary-submenu-color-responsive'],
					'title'      => __( 'Link / Text Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Submenu Hover Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-submenu-h-color-responsive]',
					'default'    => $defaults['primary-submenu-h-color-responsive'],
					'title'      => __( 'Link Hover Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Submenu Hover Background Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-submenu-h-bg-color-responsive]',
					'default'    => $defaults['primary-submenu-h-bg-color-responsive'],
					'title'      => __( 'Hover Background Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Active Submenu Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-submenu-a-color-responsive]',
					'default'    => $defaults['primary-submenu-a-color-responsive'],
					'title'      => __( 'Active Link Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
					'responsive' => true,
					'rgba'       => true,
				),

				// Option: Active Submenu Background Color.
				array(
					'type'       => 'control',
					'control'    => 'ast-responsive-color',
					'transport'  => 'postMessage',
					'name'       => ASTRA_THEME_SETTINGS . '[primary-submenu-a-bg-color-responsive]',
					'default'    => $defaults['primary-submenu-a-bg-color-responsive'],
					'title'      => __( 'Active Background Color', 'astra-addon' ),
					'section'    => 'section-colors-primary-menu',
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
new Astra_Customizer_Colors_Primary_Menu;

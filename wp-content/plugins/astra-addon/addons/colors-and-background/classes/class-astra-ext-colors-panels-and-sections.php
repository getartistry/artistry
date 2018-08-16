<?php
/**
 * Astra Theme Customizer Configuration Base.
 *
 * @package     Astra Addon
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2018, Brainstorm Force
 * @link        http://www.brainstormforce.com
 * @since       1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */

if ( ! class_exists( 'Astra_Ext_Colors_Panels_And_Sections' ) ) {

	/**
	 * Register Blog Pro Panels and sections Customizer Configurations.
	 */
	class Astra_Ext_Colors_Panels_And_Sections extends Astra_Customizer_Config_Base {

		/**
		 * Register Blog Pro Panels and sections Customizer Configurations.
		 *
		 * @param Array                $configurations Astra Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array Astra Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Colors & Background - Panels & Sections
				 */
				array(
					'name'     => 'section-colors-single',
					'type'     => 'section',
					'title'    => __( 'Single Page/Post', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 45,
				),
				array(
					'name'     => 'section-colors-content',
					'type'     => 'section',
					'title'    => __( 'Content', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 35,
				),
				array(
					'name'     => 'section-colors-content',
					'type'     => 'section',
					'title'    => __( 'Content', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 35,
				),
				array(
					'name'     => 'section-colors-header-group',
					'type'     => 'section',
					'title'    => __( 'Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 20,
				),
				array(
					'name'     => 'section-colors-header',
					'type'     => 'section',
					'title'    => __( 'Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 20,
				),
				array(
					'name'     => 'section-colors-primary-menu',
					'type'     => 'section',
					'title'    => __( 'Primary Header', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-colors-header-group',
					'priority' => 15,
				),
				array(
					'name'     => 'section-colors-footer',
					'type'     => 'section',
					'title'    => __( 'Footer Bar', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 60,
				),
				array(
					'name'     => 'section-blog-color-group',
					'type'     => 'section',
					'title'    => __( 'Blog', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 40,
				),
				array(
					'name'     => 'section-colors-archive',
					'type'     => 'section',
					'priority' => 5,
					'title'    => __( 'Blog / Archive', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-blog-color-group',
				),
				array(
					'name'     => 'section-colors-single',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Single Post', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'section'  => 'section-blog-color-group',
				),
				array(
					'name'     => 'section-colors-sidebar',
					'type'     => 'section',
					'title'    => __( 'Sidebar', 'astra-addon' ),
					'panel'    => 'panel-colors-background',
					'priority' => 50,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new Astra_Ext_Colors_Panels_And_Sections;

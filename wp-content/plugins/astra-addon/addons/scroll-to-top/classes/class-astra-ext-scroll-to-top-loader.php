<?php
/**
 * Scroll to Top - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Scroll_To_Top_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Scroll_To_Top_Loader {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {

			add_filter( 'astra_theme_defaults', array( $this, 'theme_defaults' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			$defaults['scroll-to-top-icon-size']       = '15';
			$defaults['scroll-to-top-icon-position']   = 'right';
			$defaults['scroll-to-top-on-devices']      = 'both';
			$defaults['scroll-to-top-icon-radius']     = '';
			$defaults['scroll-to-top-icon-color']      = '';
			$defaults['scroll-to-top-icon-h-color']    = '';
			$defaults['scroll-to-top-icon-bg-color']   = '';
			$defaults['scroll-to-top-icon-h-bg-color'] = '';

			return $defaults;
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {

			/**
			 * Register Sections & Panels
			 */
			require_once ASTRA_EXT_SCROLL_TO_TOP_DIR . 'classes/customizer-panels-and-sections.php';

			/**
			 * Sections
			 */
			require_once ASTRA_EXT_SCROLL_TO_TOP_DIR . 'classes/sections/section-scroll-to-top.php';
		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-scroll-to-top-customize-preview-js', ASTRA_EXT_SCROLL_TO_TOP_URL . 'assets/js/unminified/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
			} else {
				wp_enqueue_script( 'astra-ext-scroll-to-top-customize-preview-js', ASTRA_EXT_SCROLL_TO_TOP_URL . 'assets/js/minified/customizer-preview.min.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
			}
		}

	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Scroll_To_Top_Loader::get_instance();

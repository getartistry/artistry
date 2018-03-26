<?php
/**
 * Sticky Header - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Transparent_Header_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Transparent_Header_Loader {

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
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ), 9 );
			add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ) );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			// Header - Transparent.
			$defaults['transparent-header-logo']            = '';
			$defaults['transparent-header-retina-logo']     = '';
			$defaults['transparent-header-logo-width']      = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);
			$defaults['transparent-header-enable']          = 0;
			$defaults['transparent-header-disable-archive'] = 1;
			$defaults['transparent-header-main-sep']        = 0;
			$defaults['transparent-header-main-sep-color']  = '';

			return $defaults;
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register( $wp_customize ) {

			/**
			 * Register Panel & Sections
			 */
			require_once ASTRA_EXT_TRANSPARENT_HEADER_DIR . 'classes/customizer-panels-and-sections.php';

			/**
			 * Sections
			 */
			require_once ASTRA_EXT_TRANSPARENT_HEADER_DIR . 'classes/sections/section-transparent-header.php';
		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {
			wp_enqueue_script( 'astra-transparent-header-customizer-preview-js', ASTRA_EXT_TRANSPARENT_HEADER_URI . 'assets/js/unminified/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
		}

		/**
		 * Customizer Controls
		 *
		 * @see 'ast-customizer-controls-js' panel in parent theme
		 */
		function controls_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-transparent-header-customizer-toggles', ASTRA_EXT_TRANSPARENT_HEADER_URI . 'assets/js/unminified/customizer-toggles.js', array( 'astra-customizer-controls-toggle-js' ), null, true );
			} else {
				wp_enqueue_script( 'astra-ext-transparent-header-customizer-toggles', ASTRA_EXT_TRANSPARENT_HEADER_URI . 'assets/js/minified/customizer-toggles.min.js', array( 'astra-customizer-controls-toggle-js' ), null, true );
			}
		}
	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Transparent_Header_Loader::get_instance();

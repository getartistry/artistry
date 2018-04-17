<?php
/**
 * Sticky Header - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Sticky_Header_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Sticky_Header_Loader {

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

			$defaults['header-main-shrink']        = 1;
			$defaults['header-main-stick']         = 0;
			$defaults['header-above-stick']        = 0;
			$defaults['header-below-stick']        = 0;
			$defaults['sticky-header-bg-opc']      = 1;
			$defaults['sticky-hide-on-scroll']     = 0;
			$defaults['sticky-header-on-devices']  = 'desktop';
			$defaults['sticky-header-style']       = 'none';
			$defaults['sticky-header-logo']        = '';
			$defaults['sticky-header-retina-logo'] = '';
			$defaults['sticky-header-logo-width']  = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

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
			require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/customizer-panels-and-sections.php';

			/**
			 * Sections
			 */
			require_once ASTRA_EXT_STICKY_HEADER_DIR . 'classes/sections/section-sticky-header.php';
		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {
			wp_enqueue_script( 'astra-sticky-header-customizer-preview-js', ASTRA_EXT_STICKY_HEADER_URI . 'assets/js/unminified/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_EXT_VER, true );
		}

		/**
		 * Customizer Controls
		 *
		 * @see 'ast-customizer-controls-js' panel in parent theme
		 */
		function controls_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-sticky-header-customizer-toggles', ASTRA_EXT_STICKY_HEADER_URI . 'assets/js/unminified/customizer-toggles.js', array( 'astra-customizer-controls-toggle-js' ), ASTRA_EXT_VER, true );
			} else {
				wp_enqueue_script( 'astra-ext-sticky-header-customizer-toggles', ASTRA_EXT_STICKY_HEADER_URI . 'assets/js/minified/customizer-toggles.min.js', array( 'astra-customizer-controls-toggle-js' ), ASTRA_EXT_VER, true );
			}

		}

	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Sticky_Header_Loader::get_instance();

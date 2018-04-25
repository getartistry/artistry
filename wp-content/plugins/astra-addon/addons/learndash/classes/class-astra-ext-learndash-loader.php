<?php
/**
 * LearnDash Loader
 *
 * @package Astra Addon
 */

if ( ! class_exists( 'Astra_Ext_LearnDash_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.3.0
	 */
	class Astra_Ext_LearnDash_Loader {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			add_filter( 'astra_theme_defaults', array( $this, 'theme_defaults' ) );
			add_action( 'customize_register', array( $this, 'customize_register' ), 12 );
			add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ), 9 );

			add_action( 'astra_get_fonts', array( $this, 'add_fonts' ), 1 );
		}

		/**
		 * Add Font Family Callback
		 *
		 * @return void
		 */
		public function add_fonts() {

			$font_family_table_heading = astra_get_option( 'font-family-learndash-table-heading' );
			$font_weight_table_heading = astra_get_option( 'font-weight-learndash-table-heading' );
			Astra_Fonts::add_font( $font_family_table_heading, $font_weight_table_heading );

			$font_family_table_content = astra_get_option( 'font-family-learndash-table-content' );
			$font_weight_table_content = astra_get_option( 'font-weight-learndash-table-content' );
			Astra_Fonts::add_font( $font_family_table_content, $font_weight_table_content );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			// Student View.
			$defaults['learndash-profile-link-enabled']      = false;
			$defaults['learndash-profile-link']              = '';
			$defaults['learndash-distraction-free-learning'] = false;

			$defaults['learndash-table-border-radius'] = 0;

			// Colors.
			$defaults['learndash-table-heading-color']         = '';
			$defaults['learndash-table-heading-bg-color']      = '';
			$defaults['learndash-table-title-color']           = '';
			$defaults['learndash-table-title-bg-color']        = '';
			$defaults['learndash-table-title-separator-color'] = '';
			$defaults['learndash-complete-icon-color']         = '';
			$defaults['learndash-incomplete-icon-color']       = '';

			// Table Heading.
			$defaults['font-family-learndash-table-heading']    = 'inherit';
			$defaults['font-weight-learndash-table-heading']    = 'inherit';
			$defaults['text-transform-learndash-table-heading'] = 'uppercase';
			$defaults['text-transform-learndash-table-heading'] = '';
			$defaults['font-size-learndash-table-heading']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);

			// Table Content.
			$defaults['font-family-learndash-table-content']    = 'inherit';
			$defaults['font-weight-learndash-table-content']    = 'inherit';
			$defaults['text-transform-learndash-table-content'] = '';
			$defaults['text-transform-learndash-table-content'] = '';
			$defaults['font-size-learndash-table-content']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
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
			 * Register Sections & Panels
			 */
			require_once ASTRA_EXT_LEARNDASH_DIR . 'classes/customizer-panels-and-sections.php';

			/**
			 * Sections
			 */
			require_once ASTRA_EXT_LEARNDASH_DIR . 'classes/sections/section-general.php';
			require_once ASTRA_EXT_LEARNDASH_DIR . 'classes/sections/section-colors.php';
			require_once ASTRA_EXT_LEARNDASH_DIR . 'classes/sections/section-typo.php';
		}

		/**
		 * Customizer Controls
		 *
		 * @see 'astra-customizer-preview-js' panel in parent theme
		 */
		function preview_scripts() {

			if ( SCRIPT_DEBUG ) {
				$js_path = 'assets/js/unminified/customizer-preview.js';
			} else {
				$js_path = 'assets/js/minified/customizer-preview.min.js';
			}

			wp_register_script( 'ast-learndash-customizer-preview', ASTRA_EXT_LEARNDASH_URI . $js_path, array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_EXT_VER, true );
			wp_enqueue_script( 'ast-learndash-customizer-preview' );
		}


		/**
		 * Customizer Controls
		 *
		 * @see 'astra-customizer-controls-js' panel in parent theme
		 */
		function controls_scripts() {

			if ( SCRIPT_DEBUG ) {
				$js_path = 'assets/js/unminified/customizer-toggles.js';
			} else {
				$js_path = 'assets/js/minified/customizer-toggles.min.js';
			}

			wp_enqueue_script( 'ast-learndash-customizer-toggles', ASTRA_EXT_LEARNDASH_URI . $js_path, array( 'astra-customizer-controls-toggle-js' ), ASTRA_EXT_VER, true );
		}
	}
}

/**
* Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_LearnDash_Loader::get_instance();

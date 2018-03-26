<?php
/**
 * Footer Widgets - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Adv_Footer_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Adv_Footer_Loader {

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

			$defaults['footer-adv']                            = 'disabled';
			$defaults['footer-adv-area-padding']               = array(
				'desktop' => array(
					'top'    => '70',
					'right'  => '',
					'bottom' => '70',
					'left'   => '',
				),
				'tablet'  => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'mobile'  => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
			);
			$defaults['footer-adv-layout-width']               = 'content';
			$defaults['footer-adv-bg-color']                   = '';
			$defaults['footer-adv-bg-color-opac']              = '0.8';
			$defaults['footer-adv-bg-img']                     = '';
			$defaults['footer-adv-bg-repeat']                  = 'no-repeat';
			$defaults['footer-adv-bg-pos']                     = 'center center';
			$defaults['footer-adv-bg-attac']                   = 'scroll';
			$defaults['footer-adv-bg-size']                    = 'cover';
			$defaults['footer-adv-text-color']                 = '';
			$defaults['footer-adv-link-color']                 = '';
			$defaults['footer-adv-link-h-color']               = '';
			$defaults['footer-adv-wgt-title-color']            = '';
			$defaults['footer-adv-wgt-title-font-family']      = 'inherit';
			$defaults['footer-adv-wgt-title-font-weight']      = 'inherit';
			$defaults['footer-adv-wgt-title-text-transform']   = '';
			$defaults['footer-adv-wgt-title-line-height']      = '';
			$defaults['footer-adv-wgt-title-font-size']        = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['footer-adv-wgt-content-font-family']    = 'inherit';
			$defaults['footer-adv-wgt-content-font-weight']    = 'inherit';
			$defaults['footer-adv-wgt-content-text-transform'] = '';
			$defaults['footer-adv-wgt-content-line-height']    = '';
			$defaults['footer-adv-wgt-content-font-size']      = array(
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
			require_once ASTRA_EXT_ADVANCED_FOOTER_DIR . 'classes/customizer-panels-and-sections.php';

			/**
			 * Sections
			 */
			require_once ASTRA_EXT_ADVANCED_FOOTER_DIR . 'classes/sections/section-footer-adv.php';
			require_once ASTRA_EXT_ADVANCED_FOOTER_DIR . 'classes/sections/section-footer-adv-color-bg.php';
			require_once ASTRA_EXT_ADVANCED_FOOTER_DIR . 'classes/sections/section-footer-adv-typo.php';
		}

		/**
		 * Customizer Controls
		 *
		 * @see 'astra-customizer-controls-js' panel in parent theme
		 */
		function controls_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-footer-adv-customizer-toggles', ASTRA_EXT_ADVANCED_FOOTER_URL . 'assets/js/unminified/customizer-toggles.js', array( 'astra-customizer-controls-toggle-js' ), null, true );
			} else {
				wp_enqueue_script( 'astra-ext-footer-adv-customizer-toggles', ASTRA_EXT_ADVANCED_FOOTER_URL . 'assets/js/minified/customizer-toggles.min.js', array( 'astra-customizer-controls-toggle-js' ), null, true );
			}

		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-footer-adv-customize-preview-js', ASTRA_EXT_ADVANCED_FOOTER_URL . 'assets/js/unminified/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
			} else {
				wp_enqueue_script( 'astra-ext-footer-adv-customize-preview-js', ASTRA_EXT_ADVANCED_FOOTER_URL . 'assets/js/minified/customizer-preview.min.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
			}
		}
	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Adv_Footer_Loader::get_instance();

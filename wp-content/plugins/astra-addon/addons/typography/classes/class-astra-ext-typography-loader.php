<?php
/**
 * Typography - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Typography_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Typography_Loader {

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

			add_action( 'init', array( $this, 'disale_qm_cap_checking' ) );

			add_filter( 'astra_theme_defaults', array( $this, 'theme_defaults' ) );
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ), 9 );
			add_action( 'customize_preview_init', array( $this, 'preview_scripts' ), 9 );
			add_action( 'customize_register', array( $this, 'customize_register' ) );
			add_action( 'customize_register', array( $this, 'customize_register_new' ), 2 );
			add_action( 'astra_get_fonts', array( $this, 'add_fonts' ), 1 );

			add_filter( 'uabb_theme_button_font_family', array( $this, 'button_font_family' ), 11 );
			add_filter( 'uabb_theme_button_font_size', array( $this, 'button_font_size' ), 11 );
			add_filter( 'uabb_theme_button_text_transform', array( $this, 'button_text_transform' ), 11 );

		}

		/**
		 * Add Font Family Callback
		 *
		 * @return void
		 */
		public function add_fonts() {

			$font_family_header_content = astra_get_option( 'font-family-header-content' );
			$font_weight_header_content = astra_get_option( 'font-weight-header-content' );
			Astra_Fonts::add_font( $font_family_header_content, $font_weight_header_content );

			$font_family_site_title = astra_get_option( 'font-family-site-title' );
			$font_weight_site_title = astra_get_option( 'font-weight-site-title' );
			Astra_Fonts::add_font( $font_family_site_title, $font_weight_site_title );

			$font_family_site_tagline = astra_get_option( 'font-family-site-tagline' );
			$font_weight_site_tagline = astra_get_option( 'font-weight-site-tagline' );
			Astra_Fonts::add_font( $font_family_site_tagline, $font_weight_site_tagline );

			$font_family_primary_menu = astra_get_option( 'font-family-primary-menu' );
			$font_weight_primary_menu = astra_get_option( 'font-weight-primary-menu' );
			Astra_Fonts::add_font( $font_family_primary_menu, $font_weight_primary_menu );

			$font_family_primary_dropdown_menu = astra_get_option( 'font-family-primary-dropdown-menu' );
			$font_weight_primary_dropdown_menu = astra_get_option( 'font-weight-primary-dropdown-menu' );
			Astra_Fonts::add_font( $font_family_primary_dropdown_menu, $font_weight_primary_dropdown_menu );

			$font_family_single_entry_title = astra_get_option( 'font-family-entry-title' );
			$font_weight_single_entry_title = astra_get_option( 'font-weight-entry-title' );
			Astra_Fonts::add_font( $font_family_single_entry_title, $font_weight_single_entry_title );

			$font_family_archive_summary_title = astra_get_option( 'font-family-archive-summary-title' );
			$font_weight_archive_summary_title = astra_get_option( 'font-weight-archive-summary-title' );
			Astra_Fonts::add_font( $font_family_archive_summary_title, $font_weight_archive_summary_title );

			$font_family_archive_page_title = astra_get_option( 'font-family-page-title' );
			$font_weight_archive_page_title = astra_get_option( 'font-weight-page-title' );
			Astra_Fonts::add_font( $font_family_archive_page_title, $font_weight_archive_page_title );

			$font_family_post_meta = astra_get_option( 'font-family-post-meta' );
			$font_weight_post_meta = astra_get_option( 'font-weight-post-meta' );
			Astra_Fonts::add_font( $font_family_post_meta, $font_weight_post_meta );

			$font_family_widget_title = astra_get_option( 'font-family-widget-title' );
			$font_weight_widget_title = astra_get_option( 'font-weight-widget-title' );
			Astra_Fonts::add_font( $font_family_widget_title, $font_weight_widget_title );

			$font_family_widget_content = astra_get_option( 'font-family-widget-content' );
			$font_weight_widget_content = astra_get_option( 'font-weight-widget-content' );
			Astra_Fonts::add_font( $font_family_widget_content, $font_weight_widget_content );

			$font_family_footer_content = astra_get_option( 'font-family-footer-content' );
			$font_weight_footer_content = astra_get_option( 'font-weight-footer-content' );
			Astra_Fonts::add_font( $font_family_footer_content, $font_weight_footer_content );

			$font_family_h1 = astra_get_option( 'font-family-h1' );
			$font_weight_h1 = astra_get_option( 'font-weight-h1' );
			Astra_Fonts::add_font( $font_family_h1, $font_weight_h1 );

			$font_family_h2 = astra_get_option( 'font-family-h2' );
			$font_weight_h2 = astra_get_option( 'font-weight-h2' );
			Astra_Fonts::add_font( $font_family_h2, $font_weight_h2 );

			$font_family_h3 = astra_get_option( 'font-family-h3' );
			$font_weight_h3 = astra_get_option( 'font-weight-h3' );
			Astra_Fonts::add_font( $font_family_h3, $font_weight_h3 );

			$font_family_h4 = astra_get_option( 'font-family-h4' );
			$font_weight_h4 = astra_get_option( 'font-weight-h4' );
			Astra_Fonts::add_font( $font_family_h4, $font_weight_h4 );

			$font_family_h5 = astra_get_option( 'font-family-h5' );
			$font_weight_h5 = astra_get_option( 'font-weight-h5' );
			Astra_Fonts::add_font( $font_family_h5, $font_weight_h5 );

			$font_family_h6 = astra_get_option( 'font-family-h6' );
			$font_weight_h6 = astra_get_option( 'font-weight-h6' );
			Astra_Fonts::add_font( $font_family_h6, $font_weight_h6 );

			$font_family_button = astra_get_option( 'font-family-button' );
			$font_weight_button = astra_get_option( 'font-weight-button' );
			Astra_Fonts::add_font( $font_family_button, $font_weight_button );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			// Header.
			$defaults['font-family-site-title']    = 'inherit';
			$defaults['font-weight-site-title']    = 'inherit';
			$defaults['text-transform-site-title'] = '';
			$defaults['line-height-site-title']    = '';

			$defaults['font-family-site-tagline']    = 'inherit';
			$defaults['font-weight-site-tagline']    = 'inherit';
			$defaults['text-transform-site-tagline'] = '';
			$defaults['line-height-site-tagline']    = '';

			// Primary Menu.
			$defaults['font-size-primary-menu']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-weight-primary-menu']    = 'inherit';
			$defaults['font-family-primary-menu']    = 'inherit';
			$defaults['text-transform-primary-menu'] = '';
			$defaults['line-height-primary-menu']    = '';

			// Primary Dropdown Menu.
			$defaults['font-size-primary-dropdown-menu']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-family-primary-dropdown-menu']    = 'inherit';
			$defaults['font-weight-primary-dropdown-menu']    = 'inherit';
			$defaults['text-transform-primary-dropdown-menu'] = '';
			$defaults['line-height-primary-dropdown-menu']    = '';

			// Archive Summary Box.
			$defaults['font-family-archive-summary-title']    = 'inherit';
			$defaults['font-weight-archive-summary-title']    = 'inherit';
			$defaults['text-transform-archive-summary-title'] = '';
			$defaults['line-height-archive-summary-title']    = '';

			// Archive.
			$defaults['font-family-page-title']    = 'inherit';
			$defaults['font-weight-page-title']    = 'inherit';
			$defaults['text-transform-page-title'] = '';
			$defaults['line-height-page-title']    = '';

			$defaults['font-size-post-meta']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-family-post-meta']    = 'inherit';
			$defaults['font-weight-post-meta']    = 'inherit';
			$defaults['text-transform-post-meta'] = '';
			$defaults['line-height-post-meta']    = '';

			$defaults['font-size-post-pagination']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['text-transform-post-pagination'] = '';

			// Single.
			$defaults['font-family-entry-title']    = 'inherit';
			$defaults['font-weight-entry-title']    = 'inherit';
			$defaults['text-transform-entry-title'] = '';
			$defaults['line-height-entry-title']    = '';

			// Button.
			$defaults['font-size-button']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-weight-button']    = 'inherit';
			$defaults['font-family-button']    = 'inherit';
			$defaults['text-transform-button'] = '';

			// Sidebar.
			$defaults['font-size-widget-title']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-family-widget-title']    = 'inherit';
			$defaults['font-weight-widget-title']    = 'inherit';
			$defaults['text-transform-widget-title'] = '';
			$defaults['line-height-widget-title']    = '';

			$defaults['font-size-widget-content']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-family-widget-content']    = 'inherit';
			$defaults['font-weight-widget-content']    = 'inherit';
			$defaults['text-transform-widget-content'] = '';
			$defaults['line-height-widget-content']    = '';

			// Footer.
			$defaults['font-size-footer-content']      = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['font-family-footer-content']    = 'inherit';
			$defaults['font-weight-footer-content']    = 'inherit';
			$defaults['text-transform-footer-content'] = '';
			$defaults['line-height-footer-content']    = '';

			// Header <H1>.
			$defaults['font-family-h1']    = 'inherit';
			$defaults['font-weight-h1']    = 'inherit';
			$defaults['text-transform-h1'] = '';
			$defaults['line-height-h1']    = '';

			// Header <H2>.
			$defaults['font-family-h2']    = 'inherit';
			$defaults['font-weight-h2']    = 'inherit';
			$defaults['text-transform-h2'] = '';
			$defaults['line-height-h2']    = '';

			// Header <H3>.
			$defaults['font-family-h3']    = 'inherit';
			$defaults['font-weight-h3']    = 'inherit';
			$defaults['text-transform-h3'] = '';
			$defaults['line-height-h3']    = '';

			// Header <H4>.
			$defaults['font-family-h4']    = 'inherit';
			$defaults['font-weight-h4']    = 'inherit';
			$defaults['text-transform-h4'] = '';
			$defaults['line-height-h4']    = '';

			// Header <H5>.
			$defaults['font-family-h5']    = 'inherit';
			$defaults['font-weight-h5']    = 'inherit';
			$defaults['text-transform-h5'] = '';
			$defaults['line-height-h5']    = '';

			// Header <H6>.
			$defaults['font-family-h6']    = 'inherit';
			$defaults['font-weight-h6']    = 'inherit';
			$defaults['text-transform-h6'] = '';
			$defaults['line-height-h6']    = '';

			// Outside Menu Item.
			$defaults['outside-menu-font-size']   = array(
				'desktop'      => '',
				'tablet'       => '',
				'mobile'       => '',
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['outside-menu-line-height'] = '';

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
			if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {

				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/customizer-panels-and-sections.php';

				/**
				 * Sections
				 */
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-content-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-archive-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-header-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-button-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-primary-menu-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-sidebar-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-footer-typo.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/section-single-typo.php';

			}
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function customize_register_new( $wp_customize ) {

			/**
			 * Register Sections & Panels
			 */
			if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/class-astra-typo-panel-section-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-archive-advanced-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-button-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-content-advanced-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-footer-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-site-header-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-primary-menu-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-sidebar-typo-configs.php';
				require_once ASTRA_EXT_TYPOGRAPHY_DIR . 'classes/sections/class-astra-single-advanced-typo-configs.php';
			}
		}

		/**
		 * Customizer Controls
		 *
		 * @see 'astra-customizer-controls-js' panel in parent theme
		 */
		function controls_scripts() {

			if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {

				if ( SCRIPT_DEBUG ) {
					wp_enqueue_script( 'astra-ext-typography-customizer-toggles', ASTRA_EXT_TYPOGRAPHY_URI . 'assets/js/unminified/customizer-toggles.js', array( 'astra-customizer-controls-toggle-js' ), ASTRA_EXT_VER, true );
				} else {
					wp_enqueue_script( 'astra-ext-typography-customizer-toggles', ASTRA_EXT_TYPOGRAPHY_URI . 'assets/js/minified/customizer-toggles.min.js', array( 'astra-customizer-controls-toggle-js' ), ASTRA_EXT_VER, true );
				}
			}
		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-typography-customize-preview-js', ASTRA_EXT_TYPOGRAPHY_URI . 'assets/js/unminified/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_EXT_VER, true );
			} else {
				wp_enqueue_script( 'astra-ext-typography-customize-preview-js', ASTRA_EXT_TYPOGRAPHY_URI . 'assets/js/minified/customizer-preview.min.js', array( 'customize-preview', 'astra-customizer-preview-js' ), ASTRA_EXT_VER, true );
			}

		}

		/**
		 * Button Font Family
		 */
		function button_font_family() {
			return array(
				'family' => astra_get_option( 'font-family-button' ),
				'weight' => astra_get_option( 'font-weight-button' ),
			);
		}

		/**
		 * Button Font Size
		 */
		function button_font_size() {
			$font_size        = astra_get_option( 'font-size-button' );
			$font_size_number = $font_size['desktop'];
			$font_size_unit   = $font_size['desktop-unit'];

			return $font_size_number . $font_size_unit;
		}

		/**
		 * Button Text Transform
		 */
		function button_text_transform() {
			return astra_get_option( 'text-transform-button' );
		}

		/**
		 * Typography Param loads all the available Google fonts from the Json.
		 * The Google Fonts list is generated usig a Grunt Command so this list will keep on increasing in future.
		 *
		 * When Typography param and Query Monitor is active, Both of these cause a lot of memory consumption. Particularly `QM_Collector_Caps->filter_user_has_cap`
		 * Hence we are disaling this method only when inside the Customizer Preview. This is not affected anywhere else on the site, Front End or Admin.
		 *
		 * @since 1.1.0
		 */
		public function disale_qm_cap_checking() {

			if ( class_exists( 'QM_Collector_Caps' ) && is_customize_preview() ) {
				$qm_caps = QM_Collectors::get( 'caps' );
				remove_filter( 'user_has_cap', array( $qm_caps, 'filter_user_has_cap' ), 9999, 3 );
			}
		}

	}
}

/**
* Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Typography_Loader::get_instance();

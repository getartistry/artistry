<?php
/**
 * Colors & Background - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Colors_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Colors_Loader {

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
			add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );
			add_action( 'customize_register', array( $this, 'new_customize_register' ), 2 );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			/**
			* Body
			*/
			$defaults['content-bg-obj'] = array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
			);

			/**
			* Heading Tags <h1> to <h6>
			*/
			$defaults['h1-color'] = '';
			$defaults['h2-color'] = '';
			$defaults['h3-color'] = '';
			$defaults['h4-color'] = '';
			$defaults['h5-color'] = '';
			$defaults['h6-color'] = '';

			/**
			* Header
			*/
			$defaults['header-bg-obj']             = array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
			);
			$defaults['header-bg-obj-responsive']  = array(
				'desktop' => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'tablet'  => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'mobile'  => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
			);
			$defaults['header-color-site-title']   = '';
			$defaults['header-color-h-site-title'] = '';
			$defaults['header-color-site-tagline'] = '';

			/**
			* Primary Menu
			*/
			$defaults['primary-menu-bg-color']   = '';
			$defaults['primary-menu-color']      = '';
			$defaults['primary-menu-h-bg-color'] = '';
			$defaults['primary-menu-h-color']    = '';
			$defaults['primary-menu-a-bg-color'] = '';
			$defaults['primary-menu-a-color']    = '';

			$defaults['primary-menu-bg-obj-responsive'] = array(
				'desktop' => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'tablet'  => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
				'mobile'  => array(
					'background-color'      => '',
					'background-image'      => '',
					'background-repeat'     => 'repeat',
					'background-position'   => 'center center',
					'background-size'       => 'auto',
					'background-attachment' => 'scroll',
				),
			);

			$defaults['primary-menu-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-menu-h-bg-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-menu-h-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-menu-a-bg-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-menu-a-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			/**
			* Primary Submenu
			*/
			$defaults['primary-submenu-bg-color']   = '';
			$defaults['primary-submenu-color']      = '';
			$defaults['primary-submenu-h-bg-color'] = '';
			$defaults['primary-submenu-h-color']    = '';
			$defaults['primary-submenu-a-bg-color'] = '';
			$defaults['primary-submenu-a-color']    = '';

			$defaults['primary-submenu-bg-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-submenu-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-submenu-h-bg-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-submenu-h-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-submenu-a-bg-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			$defaults['primary-submenu-a-color-responsive'] = array(
				'desktop' => '',
				'tablet'  => '',
				'mobile'  => '',
			);

			/**
			* Single Post / Page Title
			*/
			$defaults['entry-title-color'] = '';

			/**
			* Sidebar
			*/
			$defaults['sidebar-bg-obj']             = array(
				'background-color'      => '',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'auto',
				'background-attachment' => 'scroll',
			);
			$defaults['sidebar-widget-title-color'] = '';
			$defaults['sidebar-text-color']         = '';
			$defaults['sidebar-link-color']         = '';
			$defaults['sidebar-link-h-color']       = '';

			/**
			* Blog / Archive
			*/
			$defaults['archive-summary-box-bg-color']    = '';
			$defaults['archive-summary-box-title-color'] = '';
			$defaults['archive-summary-box-text-color']  = '';
			$defaults['page-title-color']                = '';
			$defaults['post-meta-color']                 = '';
			$defaults['post-meta-link-color']            = '';
			$defaults['post-meta-link-h-color']          = '';

			/**
			* Footer
			*/
			$defaults['footer-bg-color']     = '';
			$defaults['footer-bg-color-opc'] = '0.8';
			$defaults['footer-color']        = '';
			$defaults['footer-link-color']   = '';
			$defaults['footer-link-h-color'] = '';

			return $defaults;
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function new_customize_register( $wp_customize ) {

			// Register Sections & Panels.
			require_once ASTRA_EXT_COLORS_DIR . 'classes/class-astra-ext-colors-panels-and-sections.php';

			// Sections.
			require_once ASTRA_EXT_COLORS_DIR . 'classes/sections/class-astra-customizer-colors-archive.php';
			require_once ASTRA_EXT_COLORS_DIR . 'classes/sections/class-astra-customizer-colors-content.php';
			require_once ASTRA_EXT_COLORS_DIR . 'classes/sections/class-astra-customizer-colors-header.php';
			require_once ASTRA_EXT_COLORS_DIR . 'classes/sections/class-astra-customizer-colors-primary-menu.php';
			require_once ASTRA_EXT_COLORS_DIR . 'classes/sections/class-astra-customizer-colors-sidebar.php';
			require_once ASTRA_EXT_COLORS_DIR . 'classes/sections/class-astra-customizer-colors-single.php';

		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {

			if ( SCRIPT_DEBUG ) {
				$js_path = 'assets/js/unminified/customizer-preview.js';
			} else {
				$js_path = 'assets/js/minified/customizer-preview.min.js';
			}

			wp_enqueue_script( 'astra-ext-colors-customize-preview-js', ASTRA_EXT_COLORS_URI . $js_path, array( 'customize-preview', 'astra-customizer-preview-js', 'astra-addon-customizer-preview-js' ), ASTRA_EXT_VER, true );

		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Colors_Loader::get_instance();

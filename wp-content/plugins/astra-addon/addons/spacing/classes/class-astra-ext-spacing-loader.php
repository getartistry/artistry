<?php
/**
 * Spacing - Customizer.
 *
 * @package Astra
 * @since 1.2.0
 */

if ( ! class_exists( 'Astra_Ext_Spacing_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.2.0
	 */
	class Astra_Ext_Spacing_Loader {

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
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ), 9 );
			add_action( 'customize_preview_init', array( $this, 'preview_scripts' ) );

			add_action( 'body_class', array( $this, 'add_body_class' ) );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			$defaults['site-identity-spacing']        = array(
				'desktop'      => array(
					'top'    => '',
					'bottom' => '',
				),
				'tablet'       => array(
					'top'    => '',
					'bottom' => '',
				),
				'mobile'       => array(
					'top'    => '',
					'bottom' => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['container-outside-spacing']    = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '1.5',
					'right'  => '0',
					'bottom' => '1.5',
					'left'   => '0',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
				'mobile-unit'  => 'px',
			);
			$defaults['container-inside-spacing']     = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '1.5',
					'right'  => '2.14',
					'bottom' => '1.5',
					'left'   => '2.14',
				),
				'mobile'       => array(
					'top'    => '1.5',
					'right'  => '1',
					'bottom' => '1.5',
					'left'   => '1',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
				'mobile-unit'  => 'em',
			);
			$defaults['sidebar-outside-spacing']      = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '1.5',
					'right'  => '1',
					'bottom' => '1.5',
					'left'   => '1',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
				'mobile-unit'  => 'px',
			);
			$defaults['sidebar-inside-spacing']       = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['blog-post-outside-spacing']    = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['blog-post-inside-spacing']     = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['blog-post-pagination-spacing'] = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['header-spacing']               = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '1.5',
					'right'  => '',
					'bottom' => '1.5',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '1',
					'right'  => '',
					'bottom' => '1',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
				'mobile-unit'  => 'em',
			);

			$defaults['sticky-header-spacing'] = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);

			$defaults['footer-sml-spacing']      = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '2',
					'right'  => '',
					'bottom' => '2',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
				'mobile-unit'  => 'px',
			);
			$defaults['primary-menu-spacing']    = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '20',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['primary-submenu-spacing'] = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '30',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);

			$defaults['above-header-spacing']         = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '',
					'bottom' => '0',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['above-header-menu-spacing']    = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '20',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['above-header-submenu-spacing'] = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '20',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);

			$defaults['below-header-spacing']         = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '1',
					'right'  => '',
					'bottom' => '1',
					'left'   => '',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
				'mobile-unit'  => 'px',
			);
			$defaults['below-header-menu-spacing']    = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '20',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['below-header-submenu-spacing'] = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '20',
					'bottom' => '0',
					'left'   => '20',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'px',
				'mobile-unit'  => 'px',
			);
			$defaults['footer-menu-spacing']          = array(
				'desktop'      => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'tablet'       => array(
					'top'    => '0',
					'right'  => '.5',
					'bottom' => '0',
					'left'   => '.5',
				),
				'mobile'       => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'desktop-unit' => 'px',
				'tablet-unit'  => 'em',
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
			 * Sections
			 */
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-site-identity.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-container-layout.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-header.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-above-header.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-below-header.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-sidebars.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-blog.php';
			require_once ASTRA_EXT_SPACING_DIR . 'classes/sections/section-footer-small.php';
		}


		/**
		 * Customizer Controls
		 *
		 * @see 'ast-customizer-controls-js' panel in parent theme
		 */
		function controls_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-spacing-customizer-toggles', ASTRA_EXT_SPACING_URL . 'assets/js/unminified/customizer-toggles.js', array( 'astra-customizer-controls-toggle-js' ), null, true );
			} else {
				wp_enqueue_script( 'astra-ext-spacing-customizer-toggles', ASTRA_EXT_SPACING_URL . 'assets/js/minified/customizer-toggles.min.js', array( 'astra-customizer-controls-toggle-js' ), null, true );
			}
		}

		/**
		 * Customizer Preview
		 */
		function preview_scripts() {

			if ( SCRIPT_DEBUG ) {
				wp_enqueue_script( 'astra-ext-spacing-customize-preview-js', ASTRA_EXT_SPACING_URL . 'assets/js/unminified/customizer-preview.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
			} else {
				wp_enqueue_script( 'astra-ext-spacing-customize-preview-js', ASTRA_EXT_SPACING_URL . 'assets/js/minified/customizer-preview.min.js', array( 'customize-preview', 'astra-customizer-preview-js' ), null, true );
			}

			$localize_array = array(
				'blog_pro_enabled' => Astra_Ext_Extension::is_active( 'blog-pro' ),
			);
			wp_localize_script( 'astra-ext-spacing-customize-preview-js', 'ast_preview', $localize_array );

		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		function add_body_class( $classes ) {

			/**
			 * Add class for header width
			 */
			$header_content_layout = astra_get_option( 'header-main-layout-width' );

			if ( 'full' == $header_content_layout ) {
				$classes[] = 'ast-full-width-header';
			}

			return $classes;
		}

	}
}

/**
*  Kicking this off by calling 'get_instance()' method
*/
Astra_Ext_Spacing_Loader::get_instance();

<?php
/**
 * Blog Pro - Customizer.
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Ext_Blog_Pro_Loader' ) ) {

	/**
	 * Customizer Initialization
	 *
	 * @since 1.0.0
	 */
	class Astra_Ext_Blog_Pro_Loader {

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
			add_action( 'customize_register', array( $this, 'old_customize_register' ) );
			add_action( 'customize_register', array( $this, 'new_customize_register' ), 2 );

		}

		/**
		 * Set Options Default Values
		 *
		 * @param  array $defaults  Astra options default value array.
		 * @return array
		 */
		function theme_defaults( $defaults ) {

			// Blog / Archive.
			$defaults['blog-masonry']               = false;
			$defaults['blog-date-box']              = false;
			$defaults['blog-date-box-style']        = 'square';
			$defaults['first-post-full-width']      = false;
			$defaults['blog-space-bet-posts']       = false;
			$defaults['blog-grid']                  = 1;
			$defaults['blog-grid-layout']           = 1;
			$defaults['blog-layout']                = 'blog-layout-1';
			$defaults['blog-pagination']            = 'number';
			$defaults['blog-pagination-style']      = 'default';
			$defaults['blog-infinite-scroll-event'] = 'scroll';

			$defaults['blog-excerpt-count']          = 55;
			$defaults['blog-read-more-text']         = __( 'Read More »', 'astra-addon' );
			$defaults['blog-read-more-as-button']    = false;
			$defaults['blog-load-more-text']         = __( 'Load More', 'astra-addon' );
			$defaults['blog-featured-image-padding'] = false;

			// Single.
			$defaults['ast-author-info']               = false;
			$defaults['ast-single-post-navigation']    = false;
			$defaults['ast-auto-prev-post']            = false;
			$defaults['single-featured-image-padding'] = false;

			return $defaults;
		}

		/**
		 * Register panel, section and controls
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function new_customize_register( $wp_customize ) {

			/**
			 * Customizer Configurations.
			 */
			if ( class_exists( 'Astra_Customizer_Config_Base' ) ) {

				/**
				 * Register Sections & Panels
				 */
				require_once ASTRA_EXT_BLOG_PRO_DIR . 'classes/class-astra-customizer-blog-pro-panels-and-sections.php';

				/**
				 * Sections
				 */
				require_once ASTRA_EXT_BLOG_PRO_DIR . 'classes/sections/class-astra-customizer-blog-pro-configs.php';
				require_once ASTRA_EXT_BLOG_PRO_DIR . 'classes/sections/class-astra-customizer-blog-pro-single-configs.php';
			}

		}

		/**
		 * Register panel, section and controls
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		function old_customize_register( $wp_customize ) {

			/**
			 * Customizer Configurations.
			 */
			if ( ! class_exists( 'Astra_Customizer_Config_Base' ) ) {

				/**
				 * Register Sections & Panels
				 */
				require_once ASTRA_EXT_BLOG_PRO_DIR . 'classes/customizer-panels-and-sections.php';

				/**
				 * Sections
				 */
				require_once ASTRA_EXT_BLOG_PRO_DIR . 'classes/sections/section-blog.php';
				require_once ASTRA_EXT_BLOG_PRO_DIR . 'classes/sections/section-blog-single.php';
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
					$js_path = 'assets/js/unminified/customizer-toggles.js';
				} else {
					$js_path = 'assets/js/minified/customizer-toggles.min.js';
				}

				wp_enqueue_script( 'astra-ext-blog-pro-customizer-toggles', ASTRA_EXT_BLOG_PRO_URI . $js_path, array( 'astra-customizer-controls-toggle-js' ), ASTRA_EXT_VER, true );
			}
		}
	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
Astra_Ext_Blog_Pro_Loader::get_instance();

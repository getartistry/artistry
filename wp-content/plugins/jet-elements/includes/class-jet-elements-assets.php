<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Elements_Assets' ) ) {

	/**
	 * Define Jet_Elements_Assets class
	 */
	class Jet_Elements_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/frontend/before_register_scripts', array( $this, 'register_scripts' ) );
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_enqueue_styles' ) );
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'jet-elements',
				jet_elements()->plugin_url( 'assets/css/jet-elements.css' ),
				false,
				jet_elements()->get_version()
			);

			if ( is_rtl() ) {
				wp_enqueue_style(
					'jet-elements-rtl',
					jet_elements()->plugin_url( 'assets/css/jet-elements-rtl.css' ),
					false,
					jet_elements()->get_version()
				);
			}

			$default_theme_enabled = apply_filters( 'jet-elements/assets/css/default-theme-enabled', true );

			if ( ! $default_theme_enabled ) {
				return;
			}

			wp_enqueue_style(
				'jet-elements-skin',
				jet_elements()->plugin_url( 'assets/css/jet-elements-skin.css' ),
				false,
				jet_elements()->get_version()
			);

			// Register vendor slider-pro.css styles (https://github.com/bqworks/slider-pro)
			wp_enqueue_style(
				'jet-slider-pro-css',
				jet_elements()->plugin_url( 'assets/css/lib/slider-pro/slider-pro.min.css' ),
				false,
				'1.3.0'
			);

			// Register vendor juxtapose-css styles
			wp_enqueue_style(
				'jet-juxtapose-css',
				jet_elements()->plugin_url( 'assets/css/lib/juxtapose/juxtapose.css' ),
				false,
				'1.3.0'
			);

			// Register vendor masonry.pkgd.min.js script
			wp_register_script(
				'jet-masonry-js',
				jet_elements()->plugin_url( 'assets/js/lib/masonry-js/masonry.pkgd.min.js' ),
				array(),
				'4.2.1',
				true
			);

			if ( jet_elements_integration()->in_elementor() ) {
				// Enqueue mediaelement css only in the editor.
				wp_enqueue_style( 'mediaelement' );
			}
		}

		/**
		 * Register plugin scripts
		 *
		 * @return void
		 */
		public function register_scripts() {

			$api_disabled = jet_elements_settings()->get( 'disable_api_js', array() );
			$key          = jet_elements_settings()->get( 'api_key' );

			if ( ! empty( $key ) && ( empty( $api_disabled ) || 'true' !== $api_disabled['disable'] ) ) {

				wp_register_script(
					'google-maps-api',
					add_query_arg(
						array( 'key' => jet_elements_settings()->get( 'api_key' ), ),
						'https://maps.googleapis.com/maps/api/js'
					),
					false,
					false,
					true
				);
			}

			// Register vendor anime.js script (https://github.com/juliangarnier/anime)
			wp_register_script(
				'jet-anime-js',
				jet_elements()->plugin_url( 'assets/js/lib/anime-js/anime.min.js' ),
				array(),
				'2.0.2',
				true
			);

			// Register vendor salvattore.js script (https://github.com/rnmp/salvattore)
			wp_register_script(
				'jet-salvattore',
				jet_elements()->plugin_url( 'assets/js/lib/salvattore/salvattore.min.js' ),
				array(),
				'1.0.9',
				true
			);

			// Register vendor slider-pro.js script (https://github.com/bqworks/slider-pro)
			wp_register_script(
				'jet-slider-pro',
				jet_elements()->plugin_url( 'assets/js/lib/slider-pro/jquery.sliderPro.min.js' ),
				array(),
				'1.3.0',
				true
			);

			// Register vendor juxtapose.js script
			wp_register_script(
				'jet-juxtapose',
				jet_elements()->plugin_url( 'assets/js/lib/juxtapose/juxtapose.min.js' ),
				array(),
				'1.3.0',
				true
			);

			// Register vendor tablesorter.js script (https://github.com/Mottie/tablesorter)
			wp_register_script(
				'jquery-tablesorter',
				jet_elements()->plugin_url( 'assets/js/lib/tablesorter/jquery.tablesorter.min.js' ),
				array( 'jquery' ),
				'2.30.7',
				true
			);
		}

		/**
		 * Enqueue admin styles
		 *
		 * @return void
		 */
		public function admin_enqueue_styles() {
			$screen = get_current_screen();

			// Jet setting page check
			if ( 'elementor_page_jet-elements-settings' === $screen->base ) {
				wp_enqueue_style(
					'jet-elements-admin-css',
					jet_elements()->plugin_url( 'assets/css/jet-elements-admin.css' ),
					false,
					jet_elements()->get_version()
				);
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Elements_Assets
 *
 * @return object
 */
function jet_elements_assets() {
	return Jet_Elements_Assets::get_instance();
}

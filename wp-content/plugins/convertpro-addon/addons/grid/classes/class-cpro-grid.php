<?php
/**
 * Convert Pro Grid.
 *
 * @package Convert Pro Addon
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

if ( ! class_exists( 'CPRO_Grid' ) ) {

	/**
	 * Responsible for setting up constants, classes and includes.
	 *
	 * @since 1.0.0
	 */
	final class CPRO_Grid {

		/**
		 * The unique instance of the plugin.
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Gets an instance of our plugin.
		 */
		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {

			add_action( 'cp_after_setting_panel_options', array( $this, 'grid_option' ) );
			add_action( 'cp_get_grid_svg', array( $this, 'get_grid' ) );
			add_action( 'admin_footer', array( $this, 'load_script' ), 999 );
		}

		/**
		 * Adds a grid option is customizer settings.
		 */
		public function grid_option() {

			$grid = '<a href="#" class="cp-section cp-active-style cp-grid-setting" data-section-id="grid"><span class="cp-grid-nav-title"><span class="dashicons dashicons-yes cp-hide-icons"></span> ' . __( 'Grid', 'convertpro-addon' ) . '</span></a>';

			echo $grid;
		}

		/**
		 * Adds a grid html is customizer panel.
		 */
		public function get_grid() {
			$grid = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" id="Layer_1" class="cp-grid-panel cp-disable-grid">
					<defs>
						<pattern id="small-grid" patternUnits="userSpaceOnUse" x="-0.5" y="-0.5" width="18" height="18" viewBox="0 0 18 18">
							<rect x="-0.5" y="-0.5" width="18" height="18" class="stroke"/>
						</pattern>
						<pattern id="big-grid" patternUnits="userSpaceOnUse" x="-0.5" y="-0.5" width="72" height="72" viewBox="0 0 72 72">
							<rect x="-0.5" y="-0.5" width="72" height="72" class="square" fill="url(#small-grid)"/>
						</pattern>
					</defs>
					<rect x="-0.5" y="-0.5" width="1008" height="630" fill="url(#big-grid)"/>
				</svg>';

			echo $grid;
		}

		/**
		 * Loads grid related javascript in footer.
		 */
		public function load_script() {
			echo '<script>		
			jQuery( ".cp-grid-setting" ).click(function(event) {		
				jQuery( ".cp-grid-panel" ).toggleClass( "cp-disable-grid" );
				jQuery( this ).toggleClass( "cp-active-link-color" );
			});</script>';
		}
	}

	$cp_pro_grid = CPRO_Grid::get_instance();
}

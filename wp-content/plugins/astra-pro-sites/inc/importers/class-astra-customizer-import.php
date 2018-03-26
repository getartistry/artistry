<?php
/**
 * Customizer Data importer class.
 *
 * @since  1.0.0
 * @package Astra Addon
 */

defined( 'ABSPATH' ) or exit;

/**
 * Customizer Data importer class.
 *
 * @since  1.0.0
 */
class Astra_Customizer_Import {

	/**
	 * Instance of Astra_Customizer_Import
	 *
	 * @since  1.0.0
	 * @var Astra_Customizer_Import
	 */
	private static $_instance = null;

	/**
	 * Instantiate Astra_Customizer_Import
	 *
	 * @since  1.0.0
	 * @return (Object) Astra_Customizer_Import
	 */
	public static function instance() {

		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	/**
	 * Import customizer options.
	 *
	 * @since  1.0.0
	 *
	 * @param  (Array) $options customizer options from the demo.
	 */
	public function import( $options ) {

		// Update Astra Theme customizer settings.
		if ( isset( $options['astra-settings'] ) ) {
			self::_import_settings( $options['astra-settings'] );
		}

		// Add Custom CSS.
		if ( isset( $options['custom-css'] ) ) {
			wp_update_custom_css_post( $options['custom-css'] );
		}

	}

	/**
	 * Import Astra Setting's
	 *
	 * Download & Import images from Astra Customizer Settings.
	 *
	 * @since 1.0.10
	 *
	 * @param  array $options Astra Customizer setting array.
	 * @return void
	 */
	static public function _import_settings( $options = array() ) {
		foreach ( $options as $key => $val ) {

			if ( Astra_Sites_Helper::_is_image_url( $val ) ) {

				$data = Astra_Sites_Helper::_sideload_image( $val );

				if ( ! is_wp_error( $data ) ) {
					$options[ $key ] = $data->url;
				}
			}
		}

		// Updated settings.
		update_option( 'astra-settings', $options );
	}
}

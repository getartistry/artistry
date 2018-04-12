<?php
namespace ElementorExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Extensions_Manager {

	const PORTFOLIO_PARALLAX 	= 'portfolio-parallax';
	const STICKY_ELEMENTS 		= 'sticky-elements';
	const PARALLAX_ELELENTS		= 'parallax-elements';
	const PARALLAX_BACKGROUND 	= 'parallax-background';
	const TOOLTIP 				= 'tooltip';

	private $_extensions = null;

	public $available_extensions = [
		self::PORTFOLIO_PARALLAX,
		self::STICKY_ELEMENTS,
		self::PARALLAX_ELELENTS,
		self::PARALLAX_BACKGROUND,
		self::TOOLTIP,
	];

	/**
	 * Loops though available extensions and registers them
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 * @return void
	 */
	public function register_extensions() {

		$this->_extensions = [];

		$available_extensions = $this->available_extensions;

		foreach ( $available_extensions as $index => $extension_id ) {
			$extension_filename = str_replace( '_', '-', $extension_id );
			$extension_name = str_replace( '-', '_', $extension_id );

			$extension_filename = ELEMENTOR_EXTRAS_PATH . "extensions/{$extension_filename}.php";

			require( $extension_filename );

			$class_name = str_replace( '-', '_', $extension_id );

			$class_name = 'ElementorExtras\Extensions\Extension_' . ucwords( $class_name );

			if ( ! $this->is_available( $extension_name ) )
				unset( $this->available_extensions[ $index ] );

			// Skip extension if it's disabled in admin settings or is dependant on non-exisiting Elementor Pro plugin
			if ( $this->is_disabled( $extension_name ) ) {
				continue;
			}

			$this->register_extension( $extension_id, new $class_name() );
		}

		do_action( 'elementor_extras/extensions/extensions_registered', $this );
	}

	/**
	 * Check if extension is disabled through admin settings
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 * @return bool
	 */
	public function is_disabled( $extension_name ) {
		if ( ! $extension_name )
			return false;

		$option_name 	= 'enable_' . $extension_name;
		$section 		= 'elementor_extras_extensions';
		$option 		= \ElementorExtras\ElementorExtrasPlugin::instance()->settings->get_option( $option_name, $section, false );

		if ( 'off' === $option ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if extension is available at all
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 * @return bool
	 */
	public function is_available( $extension_name ) {
		if ( ! $extension_name )
			return false;

		$class_name = str_replace( '-', '_', $extension_name );
		$class_name = 'ElementorExtras\Extensions\Extension_' . ucwords( $class_name );

		if ( $class_name::requires_elementor_pro() && ! is_elementor_pro_active() )
			return false;

		return true;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @param Extension_Base $extension_instance
	 */
	public function register_extension( $extension_id, Base\Extension_Base $extension_instance ) {
		$this->_extensions[ $extension_id ] = $extension_instance;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @return bool
	 */
	public function unregister_extension( $extension_id ) {
		if ( ! isset( $this->_extensions[ $extension_id ] ) ) {
			return false;
		}

		unset( $this->_extensions[ $extension_id ] );

		return true;
	}

	/**
	 * @since 0.1.0
	 *
	 * @return Extension_Base[]
	 */
	public function get_extensions() {
		if ( null === $this->_extensions ) {
			$this->register_extensions();
		}

		return $this->_extensions;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @return bool|\ElementorExtras\Extension_Base
	 */
	public function get_extension( $extension_id ) {
		$extensions = $this->get_extensions();

		return isset( $extensions[ $extension_id ] ) ? $extensions[ $extension_id ] : false;
	}

	private function require_files() {
		require( ELEMENTOR_EXTRAS_PATH . 'base/extension.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_extensions();
	}
}

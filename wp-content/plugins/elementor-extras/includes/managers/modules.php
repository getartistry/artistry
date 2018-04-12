<?php
namespace ElementorExtras;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Modules_Manager {

	private $_modules = [];

	/**
	 * @since 0.1.0
	 */
	public function register_modules() {

		$available_modules = [
			'buttons',
			'breadcrumbs',
			'circle-progress',
			'switcher',
			'devices',
			'gallery',
			'heading',
			'hotspots',
			'image-comparison',
			'posts',
			'table',
			'unfold',
			'video',
			'svg',
		];

		foreach ( $available_modules as $module_name ) {

			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			if ( $class_name::requires_elementor_pro() && ! is_elementor_pro_active() ) {
				continue;
			} else {
				$this->_modules[ $module_name ] = $class_name::instance();
			}
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name = null ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}
			return null;
		}

		return $this->_modules;
	}

	private function require_files() {
		require( ELEMENTOR_EXTRAS_PATH . 'base/module.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_modules();
	}
}

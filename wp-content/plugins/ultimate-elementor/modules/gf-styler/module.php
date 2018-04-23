<?php
/**
 * UAEL GfStyler Module.
 *
 * @package UAEL
 */

namespace UltimateElementor\Modules\GfStyler;

use UltimateElementor\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Module.
 */
class Module extends Module_Base {

	/**
	 * Module should load or not.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_enable() {
		if ( class_exists( 'GFForms' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'uael-gf-styler';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'GfStyler',
		];
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
	}
}

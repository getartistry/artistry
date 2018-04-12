<?php
namespace ElementorExtras\Modules\Switcher;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'switcher';
	}

	public function get_widgets() {
		return [
			'Switcher',
		];
	}
}

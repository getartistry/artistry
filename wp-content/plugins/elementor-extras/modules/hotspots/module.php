<?php
namespace ElementorExtras\Modules\Hotspots;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'hotspots';
	}

	public function get_widgets() {
		return [
			'Hotspots',
		];
	}
}

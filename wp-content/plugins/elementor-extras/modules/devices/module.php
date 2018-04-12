<?php
namespace ElementorExtras\Modules\Devices;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'devices';
	}

	public function get_widgets() {
		return [
			'Devices',
		];
	}
}

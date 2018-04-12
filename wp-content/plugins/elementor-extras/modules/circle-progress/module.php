<?php
namespace ElementorExtras\Modules\CircleProgress;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'circle-progress';
	}

	public function get_widgets() {
		return [
			'Circle_Progress',
		];
	}
}

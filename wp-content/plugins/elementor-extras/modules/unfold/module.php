<?php
namespace ElementorExtras\Modules\Unfold;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'unfold';
	}

	public function get_widgets() {
		return [
			'Unfold',
		];
	}
}

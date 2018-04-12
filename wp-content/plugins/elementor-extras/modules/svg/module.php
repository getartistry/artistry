<?php
namespace ElementorExtras\Modules\Svg;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'svg';
	}

	public function get_widgets() {
		return [
			'Inline_Svg',
		];
	}
}

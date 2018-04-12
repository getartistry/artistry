<?php
namespace ElementorExtras\Modules\Buttons;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'buttons';
	}

	public function get_widgets() {
		return [
			'Button_Group',
		];
	}
}

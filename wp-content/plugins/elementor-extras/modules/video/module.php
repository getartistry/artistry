<?php
namespace ElementorExtras\Modules\Video;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'video';
	}

	public function get_widgets() {
		return [
			'HTML5_Video',
		];
	}
}

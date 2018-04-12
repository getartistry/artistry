<?php
namespace ElementorExtras\Modules\ImageComparison;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'image-comparison';
	}

	public function get_widgets() {
		return [
			'Image_Comparison',
		];
	}
}

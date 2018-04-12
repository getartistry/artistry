<?php
namespace ElementorExtras\Modules\Breadcrumbs;

use ElementorExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'breadcrumbs';
	}

	public function get_widgets() {
		return [
			'Breadcrumbs',
		];
	}
}

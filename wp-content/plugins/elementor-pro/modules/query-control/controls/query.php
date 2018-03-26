<?php
namespace ElementorPro\Modules\QueryControl\Controls;

use Elementor\Control_Select2;
use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Query extends Control_Select2 {
	const CONTROL_ID = 'query';

	public function get_type() {
		return self::CONTROL_ID;
	}
}

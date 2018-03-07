<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Date
 */
class Date extends Text {

	function __construct() {
		parent::__construct();

		$this->title = __( 'Date', 'automatewoo' );
		$this->name = 'date';
		$this->set_placeholder('YYYY-MM-DD');

		$this->add_classes( 'automatewoo-date-picker date-picker' );
	}
}

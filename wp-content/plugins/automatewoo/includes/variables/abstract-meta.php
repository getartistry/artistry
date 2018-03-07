<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Abstract_Meta
 */
abstract class Variable_Abstract_Meta extends Variable {

	function load_admin_details() {
		$this->add_parameter_text_field( 'key', __( "The meta_key of the field you would like to display.", 'automatewoo'), true );
	}
}

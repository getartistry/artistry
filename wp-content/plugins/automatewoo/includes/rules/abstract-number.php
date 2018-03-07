<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Abstract_Number
 */
abstract class Abstract_Number extends Rule {

	public $type = 'number';

	public $support_floats = true;


	function __construct() {

		if ( $this->support_floats ) {
			$this->compare_types = $this->get_float_compare_types();
		}
		else {
			$this->compare_types = $this->get_integer_compare_types();
		}

		parent::__construct();
	}

}
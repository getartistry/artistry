<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Abstract_Meta
 */
abstract class Abstract_Meta extends Rule {

	public $type = 'meta';

	public $has_multiple_value_fields = true;

	function __construct() {
		$this->compare_types = $this->get_string_compare_types() + $this->get_integer_compare_types();
		parent::__construct();
	}


	/**
	 * @param $actual_value
	 * @param $compare_type
	 * @param $expected_value
	 * @return bool
	 */
	function validate_meta( $actual_value, $compare_type, $expected_value ) {

		// meta compares are a mix of string and number comparisons
		if ( $this->is_string_compare_type( $compare_type ) ) {
			return $this->validate_string( $actual_value, $compare_type, $expected_value );
		}
		else {
			return $this->validate_number( $actual_value, $compare_type, $expected_value );
		}
	}


	/**
	 * @param $value
	 * @return array|false
	 */
	function prepare_value_data( $value ) {

		if ( ! is_array( $value ) ) {
			return false;
		}

		return [
			'key' => $value[0],
			'value' => isset( $value[1] ) ? $value[1] : false
		];
	}

}
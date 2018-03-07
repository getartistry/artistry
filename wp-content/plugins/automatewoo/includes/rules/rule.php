<?php

namespace AutomateWoo\Rules;

/**
 * @class Rule
 */
abstract class Rule {

	/** @var string */
	public $name;

	/** @var string */
	public $title;

	/** @var string */
	public $group;

	/** @var string string|number|object|select  */
	public $type;

	/** @var array */
	public $data_item;

	/** @var array  */
	public $compare_types = [];

	/** @var \AutomateWoo\Workflow */
	private $workflow;

	/** @var bool - e.g meta rules have 2 value fields so their value data is an stored as an array */
	public $has_multiple_value_fields = false;


	/**
	 * Constructor
	 */
	function __construct() {
		$this->group = __( 'Other', 'automatewoo' );
		$this->init();
	}


	/**
	 * Set up the condition
	 */
	abstract function init();


	/**
	 * Validates the rule based on options set by a workflow
	 * The $data_item passed will already be validated
	 * @param $data_item
	 * @param $compare
	 * @param $expected_value
	 * @return bool
	 */
	abstract function validate( $data_item, $compare, $expected_value );


	/**
	 * @param $workflow
	 */
	function set_workflow( $workflow ) {
		$this->workflow = $workflow;
	}


	/**
	 * @return \AutomateWoo\Workflow
	 */
	function get_workflow() {
		return $this->workflow;
	}


	/**
	 * @return array
	 */
	function get_string_compare_types() {
		return [
			'contains' => __( 'contains', 'automatewoo' ),
			'not_contains' => __( 'does not contain', 'automatewoo' ),
			'is' => __( 'is', 'automatewoo' ),
			'is_not' => __( 'is not', 'automatewoo' ),
			'starts_with' => __( 'starts with', 'automatewoo' ),
			'ends_with' => __( 'ends with', 'automatewoo' ),
			'blank' => __( 'is blank', 'automatewoo' ),
			'not_blank' => __( 'is not blank', 'automatewoo' ),
		];
	}


	/**
	 * @return array
	 */
	function get_multi_string_compare_types() {
		return [
			'contains' => __( 'any contains', 'automatewoo' ),
			'is' => __( 'any matches exactly', 'automatewoo' ),
			'starts_with' => __( 'any starts with', 'automatewoo' ),
			'ends_with' => __( 'any ends with', 'automatewoo' ),
		];
	}


	/**
	 * @return array
	 */
	function get_float_compare_types() {
		return [
			'is' => __( 'is', 'automatewoo' ),
			'is_not' => __( 'is not', 'automatewoo' ),
			'greater_than' => __( 'is greater than', 'automatewoo' ),
			'less_than' => __( 'is less than', 'automatewoo' ),
		];
	}


	/**
	 * @return array
	 */
	function get_integer_compare_types() {
		return $this->get_float_compare_types() + [
			'multiple_of' => __( 'is a multiple of', 'automatewoo' ),
			'not_multiple_of' => __( 'is not a multiple of', 'automatewoo' )
		];
	}


	/**
	 * @param $compare_type
	 * @return bool
	 */
	function is_string_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_string_compare_types() );
	}


	/**
	 * @param $compare_type
	 * @return bool
	 */
	function is_integer_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_integer_compare_types() );
	}


	/**
	 * @param $compare_type
	 * @return bool
	 */
	function is_float_compare_type( $compare_type ) {
		return array_key_exists( $compare_type, $this->get_float_compare_types() );
	}


	/**
	 * @param $actual_value
	 * @param $compare_type
	 * @param $expected_value
	 * @return bool
	 */
	function validate_string( $actual_value, $compare_type, $expected_value ) {

		// case insensitive
		$actual_value = strtolower( (string) $actual_value );
		$expected_value = strtolower( (string) $expected_value );

		switch ( $compare_type ) {

			case 'is':
				return $actual_value == $expected_value;
				break;

			case 'is_not':
				return $actual_value != $expected_value;
				break;

			case 'contains':
				return strstr( $actual_value, $expected_value ) !== false;
				break;

			case 'not_contains':
				return strstr( $actual_value, $expected_value ) === false;
				break;

			case 'starts_with':
				$length = strlen( $expected_value );
				return substr( $actual_value, 0, $length ) === $expected_value;
				break;

			case 'ends_with':
				$length = strlen( $expected_value );

				if ( $length == 0 )
					return true;

				return substr( $actual_value, -$length ) === $expected_value;
				break;

			case 'blank':
				return empty( $actual_value );
				break;

			case 'not_blank':
				return ! empty( $actual_value );
				break;
		}

		return false;
	}


	/**
	 * Only supports 'contains', 'is', 'starts_with', 'ends_with'
	 *
	 * @param array $actual_values
	 * @param string $compare_type
	 * @param string $expected_value
	 * @return bool
	 */
	function validate_string_multi( $actual_values, $compare_type, $expected_value ) {

		if ( empty( $expected_value ) ) {
			return false;
		}

		// look for at least one item that validates the text match
		foreach ( $actual_values as $coupon_code ) {
			if ( $this->validate_string( $coupon_code, $compare_type, $expected_value ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * @param $actual_value
	 * @param $compare_type
	 * @param $expected_value
	 * @return bool
	 */
	function validate_number( $actual_value, $compare_type, $expected_value ) {

		$actual_value = (float) $actual_value;
		$expected_value = (float) $expected_value;

		switch ( $compare_type ) {

			case 'is':
				return $actual_value == $expected_value;
				break;

			case 'is_not':
				return $actual_value != $expected_value;
				break;

			case 'greater_than':
				return $actual_value > $expected_value;
				break;

			case 'less_than':
				return $actual_value < $expected_value;
				break;

		}


		// validate 'multiple of' compares, only accept integers
		if ( ! $this->is_whole_number( $actual_value ) || ! $this->is_whole_number( $expected_value ) ) {
			return false;
		}

		$actual_value = (int) $actual_value;
		$expected_value = (int) $expected_value;

		switch ( $compare_type ) {

			case 'multiple_of':
				return $actual_value % $expected_value == 0;
				break;

			case 'not_multiple_of':
				return $actual_value % $expected_value != 0;
				break;
		}

		return false;
	}


	/**
	 * @param $number
	 * @return bool
	 */
	function is_whole_number( $number ) {
		$number = (float) $number;
		return floor( $number ) == $number;
	}


}

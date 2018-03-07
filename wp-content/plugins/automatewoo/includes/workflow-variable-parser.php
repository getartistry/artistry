<?php

namespace AutomateWoo;

/**
 * Class to parse a variable string into separate usable parts
 *
 * @class Workflow_Variable_Parser
 * @since 3.6
 */
class Workflow_Variable_Parser {

	/** @var string */
	public $name;

	/** @var string */
	public $type;

	/** @var string */
	public $field;

	/** @var array */
	public $parameters;

	/** @var string */
	public $parameter_string;


	/**
	 * Returns true on successful parsing
	 *
	 * @param $variable_string
	 * @return bool
	 */
	function parse( $variable_string ) {

		$matches = [];
		$parameters = [];

		preg_match('/([a-z._])+/', $variable_string, $matches, PREG_OFFSET_CAPTURE );

		if ( ! $matches ) {
			return false;
		}

		$name = $matches[0][0];

		if ( ! strstr( $name, '.' ) ) {
			return false;
		}

		list( $type, $field ) = explode( '.', $name, 2 );

		$parameter_string = trim( substr( $variable_string, $matches[1][1] + 1 ) );
		$parameter_string = trim( aw_str_replace_first_match( $parameter_string, '|' ) ); // remove pipe

		$parameters_split = preg_split('/(,)(?=(?:[^\']|\'[^\']*\')*$)/', $parameter_string );

		foreach ( $parameters_split as $parameter ) {
			if ( ! strstr( $parameter, ':' ) ) {
				continue;
			}

			list( $key, $value ) = explode( ':', $parameter, 2 );

			$key = sanitize_title( $key );
			$value = sanitize_text_field( $this->unquote( $value ) );

			$parameters[ $key ] = $value;
		}

		$this->name = $name;
		$this->type = sanitize_title( $type );
		$this->field = sanitize_title( $field );
		$this->parameters = $parameters;
		$this->parameter_string = $parameter_string;

		return true;

	}


	/**
	 * Remove single quotes from start and end of a string
	 * @param $string
	 * @return string
	 */
	private function unquote( $string ) {
		return trim( trim( $string ), "'" );
	}

}

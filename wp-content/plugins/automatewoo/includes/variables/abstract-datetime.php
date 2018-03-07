<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Abstract_Datetime
 */
class Variable_Abstract_Datetime extends Variable {

	public $_desc_format_tip;

	function load_admin_details() {
		$this->_desc_format_tip = sprintf(
			__( "To modify how dates appear refer to the %sPHP date format documentation%s.", 'automatewoo'),
			'<a href="http://php.net/manual/en/function.date.php#refsect1-function.date-parameters" target="_blank">', '</a>'
		);

		$this->add_parameter_text_field( 'format',
			__( "Optional parameter to modify the display of the datetime. Default is MySQL format (Y-m-d H:i:s)", 'automatewoo' ),
			false, Format::MYSQL );

		$this->add_parameter_text_field( 'modify',
			__( "Optional parameter to modify the value of the datetime. Uses the PHP strtotime() function.", 'automatewoo' ), false,
			__( "e.g. +2 months, -1 day, +6 hours", 'automatewoo' )
		);
	}


	/**
	 * If given in UTC, will be converted to local time
	 *
	 * @param \DateTime|string $input
	 * @param array $parameters [modify, format]
	 * @param bool $is_gmt
	 * @return string|false
	 */
	function format_datetime( $input, $parameters, $is_gmt = false ) {

		if ( ! $input ) {
			return false;
		}

		if ( is_a( $input, 'DateTime' ) ) {
			$date = $input;
		}
		else {
			if ( is_numeric( $input ) ) {
				$date = new \DateTime();
				$date->setTimestamp( $input );
			}
			else {
				$date = new \DateTime( $input );
			}
		}

		if ( $is_gmt ) {
			Time_Helper::convert_from_gmt( $date );
		}

		$format = ! empty( $parameters['format'] ) ? $parameters['format']: Format::MYSQL;

		if ( ! empty( $parameters['modify'] ) ) {
			$date->modify( $parameters['modify'] );
		}

		return $date->format( $format );
	}
}

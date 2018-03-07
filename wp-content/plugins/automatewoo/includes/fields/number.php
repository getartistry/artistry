<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Number
 */
class Number extends Text {

	protected $name = 'number_input';

	protected $type = 'number';


	function __construct() {
		parent::__construct();
		$this->title = __( 'Number', 'automatewoo' );
	}


	/**
	 * @param $min string
	 * @return $this
	 */
	function set_min( $min ) {
		$this->add_extra_attr( 'min', $min );
		return $this;
	}


	/**
	 * @param $max string
	 * @return $this
	 */
	function set_max( $max ) {
		$this->add_extra_attr( 'max', $max );
		return $this;
	}

}

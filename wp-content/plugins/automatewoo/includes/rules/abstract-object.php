<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Abstract_Object
 */
abstract class Abstract_Object extends Rule {

	/** @var string  */
	public $type = 'object';

	/** @var bool  */
	public $is_multi = false;

	/** @var string */
	public $ajax_action;

	/** @var string  */
	public $class = 'automatewoo-json-search';

	/** @var string */
	public $placeholder;


	abstract function get_object_display_value( $value );


	function __construct() {

		$this->placeholder = __( 'Search...', 'automatewoo' );

		parent::__construct();
	}

}
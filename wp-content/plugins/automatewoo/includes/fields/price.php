<?php

namespace AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Price
 */
class Price extends Text {

	protected $name = 'price';

	protected $type = 'text';


	function __construct() {
		parent::__construct();

		$this->set_title( __( 'Price', 'automatewoo' ) );
		$this->classes[] = 'automatewoo-field--type-price';
	}

}

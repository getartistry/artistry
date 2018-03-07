<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @deprecated
 */
class AW_Field_Shipping_Methods extends AutomateWoo\Fields\Select {

	protected $name = 'shipping_methods';

	public $multiple = true;


	/**
	 * @param bool $show_placeholder
	 */
	function __construct( $show_placeholder = true ) {
		parent::__construct( $show_placeholder );

		$this->set_title( __( 'Shipping Methods', 'automatewoo' ) );

		foreach ( WC()->shipping()->get_shipping_methods() as $method_id => $method ) {
			// method added in WC 2.6
			$this->options[$method_id] = method_exists( $method, 'get_method_title' ) ? $method->get_method_title() : $method->get_title();
		}
	}

}

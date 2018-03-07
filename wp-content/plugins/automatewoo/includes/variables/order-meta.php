<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Meta
 */
class Variable_Order_Meta extends Variable_Abstract_Meta {


	function load_admin_details() {
		parent::load_admin_details();
		$this->description = __( "Displays an orders's meta field.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string|bool
	 */
	function get_value( $order, $parameters ) {
		if ( $parameters['key'] ) {
			return Compat\Order::get_meta( $order, $parameters['key'] );
		}
		return false;
	}
}

return new Variable_Order_Meta();

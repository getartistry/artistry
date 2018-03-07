<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Payment_Method
 */
class Variable_Order_Payment_Method extends Variable {


	function load_admin_details() {
		$this->add_parameter_select_field('format', __( "Choose whether to display the title or the ID of the payment method.", 'automatewoo'), [
			'' => __( "Title", 'automatewoo' ),
			'id' => __( "ID", 'automatewoo' )
		], false );

		$this->description = __( "Displays the payment method for the order.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {

		$display = isset( $parameters['format'] ) ? $parameters['format'] : 'title';

		switch ( $display ) {
			case 'id':
				return Compat\Order::get_payment_method( $order );
				break;
			case 'title':
				return Compat\Order::get_payment_method_title( $order );
				break;
		}
	}
}

return new Variable_Order_Payment_Method();
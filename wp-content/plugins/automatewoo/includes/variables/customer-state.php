<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Customer_State
 */
class Variable_Customer_State extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays the customer's billing state.", 'automatewoo');

		$this->add_parameter_select_field('format', __( "Choose whether to display the abbreviation or full name of the state.", 'automatewoo' ), [
			'' => __( "Full", 'automatewoo' ),
			'abbreviation' => __( "Abbreviation", 'automatewoo' ),
		], false );

	}


	/**
	 * @param $customer Customer
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return string
	 */
	function get_value( $customer, $parameters, $workflow ) {
		$format = isset( $parameters['format'] ) ? $parameters['format'] : 'full';

		// order name takes precedence
		if ( $order = $workflow->data_layer()->get_order() ) {
			$state = Compat\Order::get_billing_state( $order );
		}
		else {
			$state = $customer->get_billing_state();
		}


		switch ( $format ) {
			case 'full':
				return aw_get_state_name( $customer->get_billing_country(), $state );
				break;
			case 'abbreviation':
				return $state;
				break;
		}
	}

}

return new Variable_Customer_State();

<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Item_Meta
 */
class Variable_Order_Item_Meta extends Variable {


	function load_admin_details() {
		$this->description = __( "Can be used to display the value of an order item meta field.", 'automatewoo');
		$this->add_parameter_text_field( 'key', __( "The key of the order item meta field.", 'automatewoo'), true );
	}


	/**
	 * @param array|\WC_Order_Item_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $item, $parameters ) {

		if ( empty( $parameters['key'] ) ) {
			return false;
		}

		return wc_get_order_item_meta( Compat\Order_Item::get_id( $item ), $parameters['key'] );
	}
}

return new Variable_Order_Item_Meta();

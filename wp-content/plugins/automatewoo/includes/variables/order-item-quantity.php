<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Item_Quantity
 */
class Variable_Order_Item_Quantity extends Variable {


	function load_admin_details() {
		$this->description = __( "Can be used to display the value of an order item meta field.", 'automatewoo');
	}


	/**
	 * @param array|\WC_Order_Item_Product $item
	 * @param $parameters
	 * @return string
	 */
	function get_value( $item, $parameters ) {
		return Compat\Order_Item::get_quantity( $item );
	}
}

return new Variable_Order_Item_Quantity();

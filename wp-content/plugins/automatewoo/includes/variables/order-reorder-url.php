<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Order_Reorder_Url
 * @since 2.8.6
 */
class Variable_Order_Reorder_Url extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays a reorder URL for the order. When clicked all items from the order will be added to the user's cart.", 'automatewoo');
	}


	/**
	 * @param $order \WC_Order
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $order, $parameters ) {
		return add_query_arg([
			'aw-action' => 'reorder',
			'aw-order-key' => Compat\Order::get_order_key( $order )
		], wc_get_page_permalink('cart') );
	}
}

return new Variable_Order_Reorder_Url();

<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Item_Quantity
 */
class Order_Item_Quantity extends Abstract_Number {

	public $data_item = 'order_item';

	public $support_floats = false;


	function init() {
		$this->title = __( 'Order Line Item Quantity', 'automatewoo' );
		$this->group = __( 'Order Line Item', 'automatewoo' );
	}


	/**
	 * @param $order_item array|\WC_Order_Item_Product
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order_item, $compare, $value ) {
		return $this->validate_number( Compat\Order_Item::get_quantity( $order_item ), $compare, $value );
	}


}

return new Order_Item_Quantity();

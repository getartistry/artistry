<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Items_Text_Match
 */
class Order_Items_Text_Match extends Abstract_String {

	public $data_item = 'order';


	function init() {
		$this->title = __( 'Order Item Names - Text Match', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
		$this->compare_types = $this->get_multi_string_compare_types();
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {
		$names = [];

		foreach ( $order->get_items() as $item ) {
			$names[] = Compat\Order_Item::get_name( $item );
		}

		return $this->validate_string_multi( $names, $compare, $value );
	}
}

return new Order_Items_Text_Match();

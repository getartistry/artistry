<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Meta
 */
class Order_Meta extends Abstract_Meta {

	public $data_item = 'order';


	function init() {
		$this->title = __( 'Order Meta', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare_type
	 * @param $value_data
	 * @return bool
	 */
	function validate( $order, $compare_type, $value_data ) {

		$value_data = $this->prepare_value_data( $value_data );

		if ( ! is_array( $value_data ) ) {
			return false;
		}

		return $this->validate_meta( Compat\Order::get_meta( $order, $value_data['key'] ), $compare_type, $value_data['value'] );
	}
}

return new Order_Meta();

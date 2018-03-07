<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Total
 */
class AW_Rule_Order_Total extends AutomateWoo\Rules\Abstract_Number {

	public $data_item = 'order';

	public $support_floats = true;


	function init() {
		$this->title = __( 'Order Total', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @param $order WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {
		return $this->validate_number( $order->get_total(), $compare, $value );
	}

}

return new AW_Rule_Order_Total();

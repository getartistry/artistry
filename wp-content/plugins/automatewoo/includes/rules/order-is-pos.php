<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Is_POS
 */
class AW_Rule_Order_Is_POS extends AutomateWoo\Rules\Abstract_Bool {

	public $data_item = 'order';


	function init() {
		$this->title = __( "Is POS Order?", 'automatewoo' );
		$this->group = __( 'POS', 'automatewoo' );
	}


	/**
	 * @param $order WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		$is_pos = (bool) AutomateWoo\Compat\Order::get_meta( $order, '_pos' );

		switch ( $value ) {
			case 'yes':
				return $is_pos;
				break;

			case 'no':
				return ! $is_pos;
				break;
		}
	}

}

return new AW_Rule_Order_Is_POS();

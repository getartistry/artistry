<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Order_Has_Cross_Sells
 */
class AW_Rule_Order_Has_Cross_Sells extends AutomateWoo\Rules\Abstract_Bool {

	public $data_item = 'order';


	function init() {
		$this->title = __( 'Order Has Cross-Sells Available', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @param $order WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		$cross_sells = aw_get_order_cross_sells( $order );

		switch ( $value ) {
			case 'yes':
				return ! empty( $cross_sells );
				break;

			case 'no':
				return empty( $cross_sells );
				break;
		}
	}

}

return new AW_Rule_Order_Has_Cross_Sells();

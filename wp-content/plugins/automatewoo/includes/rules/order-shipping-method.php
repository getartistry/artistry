<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Order_Shipping_Method
 */
class Order_Shipping_Method extends Abstract_Select {

	public $data_item = 'order';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Order Shipping Method', 'automatewoo' );
		$this->group = __( 'Order', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		$choices = [];

		foreach ( WC()->shipping()->get_shipping_methods() as $method_id => $method ) {
			// get_method_title() added in WC 2.6
			$choices[$method_id] = is_callable( [ $method, 'get_method_title' ] ) ? $method->get_method_title() : $method->get_title();
		}

		return $choices;
	}


	/**
	 * @param $order \WC_Order
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $order, $compare, $value ) {

		$methods = [];

		foreach( $order->get_shipping_methods() as $shipping_line_item ) {
			$methods[] = Compat\Order_Item::get_shipping_method_id( $shipping_line_item, true );
		}

		return $this->validate_select( $methods, $compare, $value );
	}

}

return new Order_Shipping_Method();

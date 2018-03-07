<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Cart_Link
 */
class Variable_Cart_Link extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays a unique link to the cart page that will also restore items to the customer's cart.", 'automatewoo');
		$this->add_parameter_select_field( 'page', __( "Sets which page the link will direct the customer to when clicked.", 'automatewoo'), [
			'' => __( 'Cart', 'automatewoo' ),
			'checkout' => __( 'Checkout', 'automatewoo' )
		]);
	}


	/**
	 * @param $cart Cart
	 * @param $parameters array
	 * @return string
	 */
	function get_value( $cart, $parameters ) {
		$page = empty( $parameters['page'] ) ? 'cart' : $parameters['page'];

		return add_query_arg([
			'aw-action' => 'restore-cart',
			'token' => $cart->get_token(),
			'redirect' => $page
		], wc_get_page_permalink( 'cart' ) );
	}
}

return new Variable_Cart_Link();

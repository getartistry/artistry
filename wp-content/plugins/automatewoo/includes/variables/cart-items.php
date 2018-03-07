<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Cart_Items
 */
class Variable_Cart_Items extends Variable_Abstract_Product_Display {

	public $supports_cart_table = true;


	function load_admin_details() {
		parent::load_admin_details();
		$this->description = __( "Display a product listing of the items in the cart.", 'automatewoo');
	}


	/**
	 * @param $cart Cart
	 * @param $parameters array
	 * @param $workflow Workflow
	 * @return mixed
	 */
	function get_value( $cart, $parameters, $workflow ) {

		$cart_items = $cart->get_items();
		$template = isset( $parameters['template'] ) ? $parameters['template'] : false;

		$products = [];
		$product_ids = [];

		if ( empty( $cart_items ) ) {
			return false;
		}

		foreach ( $cart_items as $item ) {
			if ( $variation_id = $item->get_variation_id() ) {
				$product_ids[] = $variation_id;
			}
			elseif ( $product_id = $item->get_product_id() ) {
				$product_ids[] = $product_id;
			}
		}

		$product_ids = array_unique( $product_ids );

		foreach ( $product_ids as $product_id ) {
			$products[] = wc_get_product( $product_id );
		}

		$args = array_merge( $this->get_default_product_template_args( $workflow, $parameters ), [
			'products' => $products,
			'cart_items' => $cart->get_items_raw(), // legacy
			'cart' => $cart
		]);

		return $this->get_product_display_html( $template, $args );
	}
}

return new Variable_Cart_Items();

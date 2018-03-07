<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Variable_Product_Add_To_Cart_Url
 */
class Variable_Product_Add_To_Cart_Url extends Variable {


	function load_admin_details() {
		$this->description = __( "Displays a link to the product that will also add the product to the users cart when clicked.", 'automatewoo');
	}


	/**
	 * @param $product \WC_Product
	 * @param $parameters
	 * @return string
	 */
	function get_value( $product, $parameters ) {
		// TODO what about variable products
		return add_query_arg( 'add-to-cart', Compat\Product::get_id( $product ), $product->get_permalink() );
	}

}

return new Variable_Product_Add_To_Cart_Url();
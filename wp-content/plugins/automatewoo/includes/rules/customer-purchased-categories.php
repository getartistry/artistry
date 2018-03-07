<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Fields_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Purchased_Categories
 */
class Customer_Purchased_Categories extends Abstract_Select {

	public $data_item = 'customer';

	public $is_multi = true;


	function init() {
		$this->title = __( "Customer's Purchased Categories - All Time", 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_categories_list();
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $expected
	 * @return bool
	 */
	function validate( $customer, $compare, $expected ) {
		if ( empty( $expected ) ) {
			return false;
		}

		$category_ids = [];

		foreach ( $customer->get_purchased_products() as $id ) {
			$terms = wp_get_object_terms( $id, 'product_cat', [ 'fields' => 'ids' ] );
			$category_ids = array_merge( $category_ids, $terms );
		}

		$category_ids = array_filter( $category_ids );

		return $this->validate_select( $category_ids, $compare, $expected );
	}
}

return new Customer_Purchased_Categories();

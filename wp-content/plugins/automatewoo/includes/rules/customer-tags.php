<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Fields_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Tags
 */
class Customer_Tags extends Abstract_Select {

	public $data_item = 'customer';

	public $is_multi = true;


	function init() {
		$this->title = __( 'Customer User Tags', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Fields_Helper::get_user_tags_list();
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {

		if ( $customer->is_registered() ) {
			$tags = wp_get_object_terms( $customer->get_user_id(), 'user_tag', [
				'fields' => 'ids'
			]);
		}
		else {
			$tags = [];
		}

		return $this->validate_select( $tags, $compare, $value );
	}

}

return new Customer_Tags();

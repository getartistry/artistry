<?php

namespace AutomateWoo\Rules;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Role
 */
class Customer_Role extends Abstract_Select {

	public $data_item = 'customer';


	function init() {
		$this->title = __( 'Customer User Role', 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		global $wp_roles;
		$choices = [];

		foreach( $wp_roles->roles as $key => $role ) {
			$choices[$key] = $role['name'];
		}

		$choices['guest'] = __( 'Guest', 'automatewoo' );

		return $choices;
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		return $this->validate_select( $customer->get_role(), $compare, $value );
	}

}

return new Customer_Role();

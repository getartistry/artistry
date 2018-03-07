<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Memberships_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Customer_Active_Membership_Plans
 */
class Customer_Active_Membership_Plans extends Abstract_Select {

	public $data_item = 'customer';

	public $is_multi = true;


	function init() {
		$this->title = __( "Customer's Active Memberships Plans", 'automatewoo' );
		$this->group = __( 'Customer', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function load_select_choices() {
		return Memberships_Helper::get_membership_plans();
	}


	/**
	 * @param $customer \AutomateWoo\Customer
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $customer, $compare, $value ) {
		$active_plans = [];

		if ( $customer->is_registered() ) {
			foreach( wc_memberships_get_user_active_memberships( $customer->get_user_id() ) as $membership ) {
				$active_plans[] = $membership->get_plan_id();
			}
		}

		return $this->validate_select( $active_plans, $compare, $value );
	}

}

return new Customer_Active_Membership_Plans();

<?php

namespace AutomateWoo\Rules;

use AutomateWoo\Compat;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Subscription_Meta
 */
class Subscription_Meta extends Abstract_Meta {

	public $data_item = 'subscription';


	function init() {
		$this->title = __( 'Subscription Meta', 'automatewoo' );
		$this->group = __( 'Subscription', 'automatewoo' );
	}


	/**
	 * @param $order \WC_Subscription
	 * @param $compare_type
	 * @param $value_data
	 * @return bool
	 */
	function validate( $order, $compare_type, $value_data ) {

		$value_data = $this->prepare_value_data( $value_data );

		if ( ! is_array( $value_data ) ) {
			return false;
		}

		return $this->validate_meta( Compat\Subscription::get_meta( $order, $value_data['key'] ), $compare_type, $value_data['value'] );
	}
}

return new Subscription_Meta();

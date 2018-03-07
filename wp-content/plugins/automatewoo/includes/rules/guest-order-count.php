<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class AW_Rule_Guest_Order_Count
 */
class AW_Rule_Guest_Order_Count extends AutomateWoo\Rules\Abstract_Number {

	public $data_item = 'guest';

	public $support_floats = false;


	/**
	 * Init
	 */
	function init() {
		$this->title = __( 'Guest Order Count', 'automatewoo' );
		$this->group = __( 'Guest', 'automatewoo' );
	}


	/**
	 * @param $guest AutomateWoo\Guest
	 * @param $compare
	 * @param $value
	 * @return bool
	 */
	function validate( $guest, $compare, $value ) {
		return $this->validate_number( aw_get_order_count_by_email( $guest->get_email() ), $compare, $value );
	}

}

return new AW_Rule_Guest_Order_Count();

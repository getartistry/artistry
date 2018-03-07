<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Cart_Factory;
use AutomateWoo\Clean;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor that marks carts as abandoned
 */
class Abandoned_Carts extends Base {

	/** @var string  */
	public $action = 'abandoned_carts';


	/**
	 * @param int $cart_id
	 * @return bool
	 */
	protected function task( $cart_id ) {

		if ( $cart = Cart_Factory::get( Clean::id( $cart_id ) ) ) {
			$cart->update_status( 'abandoned' );
		}

		return false;
	}

}

return new Abandoned_Carts();

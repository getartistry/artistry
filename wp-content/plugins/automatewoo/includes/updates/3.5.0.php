<?php
/**
 * Update to 3.5.0
 * - change unsubscribe data to use customer ids
 */

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

class Database_Update_3_5_0 extends Database_Update {

	public $version = '3.5.0';


	/**
	 * @return bool
	 */
	protected function process() {

		$query = new Unsubscribe_Query();
		$query->where( 'customer_id', '0' );
		$query->set_limit( 20 );
		$results = $query->get_results();

		if ( empty( $results ) ) {
			// no more items to process return complete...
			return true;
		}

		foreach ( $results as $unsubscribe ) {

			if ( $user_id = $unsubscribe->get_prop( 'user_id' ) ) {
				$customer = Customer_Factory::get_by_user_id( $user_id );
			}
			else {
				$customer = Customer_Factory::get_by_email( $unsubscribe->get_prop( 'email' ) );
			}

			if ( $customer ) {
				$unsubscribe->set_customer_id( $customer->get_id() );
				$unsubscribe->save();
			}
			else {
				// user might have been deleted
				$unsubscribe->delete();
				continue;
			}

			$this->items_processed++;
		}

		return false;
	}

}

return new Database_Update_3_5_0();

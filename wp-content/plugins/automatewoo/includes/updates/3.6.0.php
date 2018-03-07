<?php
/**
 * Update to 3.6.0
 *
 * Migrates single workflow unsubscribe data to unsubscribe to all workflows system.
 */

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

class Database_Update_3_6_0 extends Database_Update {

	public $version = '3.6.0';


	/**
	 * @return bool
	 */
	protected function process() {

		$query = new Unsubscribe_Query();
		$query->set_limit( 15 );
		$results = $query->get_results();

		if ( empty( $results ) ) {
			return true; // no more items to process, return complete
		}

		foreach ( $results as $unsubscribe ) {

			$customer = $unsubscribe->get_customer();

			if ( $customer ) { // customer might have been deleted

				if ( ! $customer->is_unsubscribed() ) {
					// set new unsub prop if not already set
					$customer->set_is_unsubscribed( true );
					$customer->set_date_unsubscribed( $unsubscribe->get_date() );
					$customer->save();
				}

			}

			$unsubscribe->delete();

			$this->items_processed++;
		}

		return false;
	}

}

return new Database_Update_3_6_0();

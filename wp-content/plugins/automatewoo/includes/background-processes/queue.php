<?php

namespace AutomateWoo\Background_Processes;

use AutomateWoo\Queued_Event_Factory;
use AutomateWoo\Clean;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Background processor for the queue
 */
class Queue extends Base {

	/** @var string  */
	public $action = 'queue';


	/**
	 * @param int $queued_event_id
	 * @return bool
	 */
	protected function task( $queued_event_id ) {

		if ( ! $queued_event = Queued_Event_Factory::get( Clean::id( $queued_event_id ) ) ) {
			return false;
		}

		// IMPORTANT - since we are running this async, check if the event is failed.
		// This ensures the event has not already begun to run in a different process
		// since we are preemptively marking events as failed when they begin to run
		if ( $queued_event->is_failed() ) {
			return false;
		}

		$queued_event->run();

		return false;
	}

}

return new Queue();

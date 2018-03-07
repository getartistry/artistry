<?php

namespace AutomateWoo;

/**
 * @class Events
 * @since 3.4.0
 */
class Events {


	/**
	 * @return int
	 */
	static function get_batch_size() {
		return (int) apply_filters( 'automatewoo/events/batch_size', 50 );
	}


	/**
	 * Check for events due to be run
	 */
	static function run_due_events() {

		/** @var Background_Processes\Event_Runner $process */
		$process = Background_Processes::get('events');

		// don't start a new process until the previous is finished
		if ( $process->has_queued_items() ) {
			$process->maybe_schedule_health_check();
			return;
		}

		$query = ( new Event_Query() )
			->set_limit( self::get_batch_size() )
			->set_ordering( 'date_scheduled', 'ASC' )
			->where( 'date_scheduled', new \DateTime(), '<' )
			->where( 'status', 'pending' )
			->set_return( 'ids' );


		if ( ! $events = $query->get_results() ) {
			return;
		}

		$process->data( $events )->start();
	}


	/**
	 * @param string $hook
	 * @param array $args
	 * @param int $delay - in seconds
	 */
	static function schedule_async_event( $hook, $args = [], $delay = 15 ) {

		$date = new \DateTime();
		$date->setTimestamp( time() + $delay );

		Events::schedule_event( $date, $hook, $args );

		if ( AUTOMATEWOO_LOG_ASYNC_EVENTS ) {
			$logger = new \WC_Logger();
			$logger->add( 'automatewoo-async-event', $hook. ': ' . print_r( $args, true ) );
		}
	}


	/**
	 * @param \DateTime $date
	 * @param string $hook
	 * @param array $args
	 */
	static function schedule_event( $date, $hook, $args = [] ) {
		$event = new Event();
		$event->set_status( 'pending' );
		$event->set_hook( $hook );
		$event->set_args( $args );
		$event->set_date_scheduled( $date );
		$event->save();
	}


	/**
	 * Unschedules all events attached to the hook with the specified arguments.
	 *
	 * @param string $hook
	 * @param array $args optional
	 */
	static function clear_scheduled_hook( $hook, $args = [] ) {

		$query = new Event_Query();
		$query->where('hook', $hook );

		if ( $args ) {
			$query->where('args_hash', md5(serialize(Clean::recursive($args))) );
		}

		$events = $query->get_results();

		foreach( $events as $event ) {
			$event->delete();
		}
	}


}

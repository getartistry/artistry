<?php

class ITSEC_Scheduler_Page_Load extends ITSEC_Scheduler {

	const OPTION = 'itsec_scheduler_page_load';

	public function schedule( $schedule, $id, $data = array(), $opts = array() ) {

		if ( ! $this->scheduling_lock() ) {
			return false;
		}

		if ( $this->is_recurring_scheduled( $id ) ) {
			$this->scheduling_unlock();

			return false;
		}

		if ( isset( $opts['fire_at'] ) ) {
			$last_fired = $opts['fire_at'] - $this->get_schedule_interval( $schedule );
		} else {
			// Prevent an event stampede
			$last_fired = ITSEC_Core::get_current_time_gmt() + 60 * mt_rand( 1, 30 );
		}

		$options = $this->get_options();

		$options['recurring'][ $id ] = array(
			'schedule'   => $schedule,
			'last_fired' => $last_fired,
			'data'       => $data,
		);

		$set = $this->set_options( $options );
		$this->scheduling_unlock();

		return $set;
	}

	public function schedule_once( $at, $id, $data = array() ) {

		if ( ! $this->scheduling_lock() ) {
			return false;
		}

		if ( $this->is_single_scheduled( $id, $data ) ) {
			$this->scheduling_unlock();

			return false;
		}

		$hash    = $this->hash_data( $data );
		$options = $this->get_options();

		if ( ! isset( $options['single'][ $id ] ) ) {
			$options['single'][ $id ] = array();
		}

		$options['single'][ $id ][ $hash ] = array(
			'data'    => $data,
			'fire_at' => $at,
		);

		$set = $this->set_options( $options );
		$this->scheduling_unlock();

		return $set;
	}

	public function is_recurring_scheduled( $id ) {
		$options = $this->get_options();

		return ! empty( $options['schedule'][ $id ] );
	}

	public function is_single_scheduled( $id, $data = array() ) {

		$hash    = $this->hash_data( $data );
		$options = $this->get_options();

		if ( empty( $options['single'][ $id ] ) ) {
			return false;
		}

		if ( empty( $options['single'][ $id ][ $hash ] ) ) {
			return false;
		}

		return true;
	}

	public function unschedule( $id ) {

		$options = $this->get_options();

		if ( isset( $options['recurring'][ $id ] ) ) {
			unset( $options['recurring'][ $id ] );

			return $this->set_options( $options );
		}

		return false;
	}

	public function unschedule_single( $id, $data = array() ) {

		$options = $this->get_options();
		$hash    = $this->hash_data( $data );

		if ( isset( $options['single'][ $id ][ $hash ] ) ) {
			unset( $options['single'][ $id ][ $hash ] );

			return $this->set_options( $options );
		}

		return false;
	}

	public function get_recurring_events() {
		$options = $this->get_options();
		$events  = array();

		foreach ( $options['recurring'] as $id => $event ) {
			$events[] = array(
				'id'       => $id,
				'data'     => $event['data'],
				'schedule' => $event['schedule'],
				'fire_at'  => $event['last_fired'] + $this->get_schedule_interval( $event['schedule'] ),
			);
		}

		return $events;
	}

	public function get_single_events() {

		$options = $this->get_options();
		$events  = array();

		foreach ( $options['single'] as $id => $hashes ) {
			foreach ( $hashes as $hash => $event ) {
				$events[] = array(
					'id'      => $id,
					'data'    => $event['data'],
					'fire_at' => $event['fire_at'],
				);
			}
		}

		return $events;
	}

	public function run() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {

		if ( ITSEC_Core::is_api_request() ) {
			return;
		}

		$now     = ITSEC_Core::get_current_time_gmt();
		$options = $this->get_options();

		/** @var ITSEC_Job[] $process */
		$process = array();

		foreach ( $options['single'] as $id => $hashes ) {
			foreach ( $hashes as $hash => $event ) {
				if ( $event['fire_at'] < $now ) {
					$process[] = $this->make_job( $id, $event['data'], array( 'single' => true ) );
				}
			}
		}

		foreach ( $options['recurring'] as $id => $event ) {
			if ( $this->is_time_to_send( $event['schedule'], $event['last_fired'] ) ) {
				$job = $this->make_job( $id, $event['data'] );

				if ( ! $job->is_retry() && $this->is_retry_scheduled( $id, $event['data'] ) ) {
					continue;
				}

				$process[] = $job;
			}
		}

		if ( ! $process ) {
			return;
		}

		if ( ! ITSEC_Lib::get_lock( 'scheduler', 120 ) ) {
			return;
		}

		$raw = ITSEC_Lib::get_uncached_option( self::OPTION );

		foreach ( $process as $job ) {
			if ( $job->is_single() ) {
				$event = $raw['single'][ $job->get_id() ][ $this->hash_data( $job->get_data() ) ];

				if ( $event['time'] < $now ) {
					$this->call_action( $job );
				}
			} else {
				$event = $raw['recurring'][ $job->get_id() ];

				if ( $this->is_time_to_send( $event['schedule'], $event['last_fired'] ) ) {
					$this->call_action( $job );
				}
			}
		}

		ITSEC_Lib::release_lock( 'scheduler' );
	}

	/**
	 * Is a retry of the given job scheduled.
	 *
	 * @param string $id
	 * @param array  $data
	 *
	 * @return bool
	 */
	private function is_retry_scheduled( $id, $data ) {
		$options = $this->get_options();

		if ( ! isset( $options['single'][ $id ] ) ) {
			return false;
		}

		foreach ( $options['single'][ $id ] as $hash => $event ) {
			if ( ! isset( $event['retry_count'] ) ) {
				continue;
			}

			unset( $event['retry_count'] );

			if ( $this->hash_data( $event ) === $this->hash_data( $data ) ) {
				return true;
			}
		}

		return false;
	}

	private function is_time_to_send( $schedule, $last_sent ) {

		if ( ! $last_sent ) {
			return true;
		}

		$period = $this->get_schedule_interval( $schedule );

		if ( ! $period ) {
			return false;
		}

		return ( $last_sent + $period ) < ITSEC_Core::get_current_time_gmt();
	}

	private function get_options() {
		return wp_parse_args( get_site_option( self::OPTION, array() ), array(
			'single'    => array(),
			'recurring' => array(),
		) );
	}

	private function set_options( $options ) {
		return update_site_option( self::OPTION, $options );
	}

	public function uninstall() {
		delete_site_option( self::OPTION );
	}
}
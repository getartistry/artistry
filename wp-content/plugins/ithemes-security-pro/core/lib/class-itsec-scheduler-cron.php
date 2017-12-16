<?php

class ITSEC_Scheduler_Cron extends ITSEC_Scheduler {

	const HOOK = 'itsec_cron';
	const OPTION = 'itsec_cron';

	public function run() {
		add_action( self::HOOK, array( $this, 'process' ), 10, 2 );
		add_filter( 'cron_schedules', array( $this, 'register_cron_schedules' ) );
	}

	public function register_cron_schedules( $schedules ) {

		$schedules[ 'itsec-' . self::S_FOUR_DAILY ] = array(
			'display'  => esc_html__( 'Four Times per Day', 'it-l10n-ithemes-security-pro' ),
			'interval' => DAY_IN_SECONDS / 4,
		);
		$schedules[ 'itsec-' . self::S_WEEKLY ]     = array(
			'display'  => esc_html__( 'Weekly', 'it-l10n-ithemes-security-pro' ),
			'interval' => WEEK_IN_SECONDS,
		);
		$schedules[ 'itsec-' . self::S_MONTHLY ]    = array(
			'display'  => esc_html__( 'Monthly', 'it-l10n-ithemes-security-pro' ),
			'interval' => MONTH_IN_SECONDS,
		);

		foreach ( $this->custom_schedules as $schedule => $interval ) {
			$schedules[ 'itsec-' . $schedule ] = array(
				'display'  => ucfirst( $schedule ),
				'interval' => $interval,
			);
		}

		return $schedules;
	}

	public function process( $id, $hash = null ) {

		$storage = $this->get_options();
		$opts    = array();

		if ( $hash ) {
			$opts['single'] = true;

			$data = $storage['single'][ $id ][ $hash ]['data'];
		} else {
			$data = $storage['recurring'][ $id ]['data'];
		}

		$job = $this->make_job( $id, $data, $opts );

		if ( ! $job->is_retry() && $this->is_retry_scheduled( $id, $data ) ) {
			return;
		}

		$this->call_action( $job );
	}

	public function is_recurring_scheduled( $id ) {
		return (bool) wp_next_scheduled( self::HOOK, array( $id ) );
	}

	public function is_single_scheduled( $id, $data = array() ) {
		return (bool) wp_next_scheduled( self::HOOK, array( $id, md5( serialize( $data ) ) ) );
	}

	public function schedule( $schedule, $id, $data = array(), $opts = array() ) {

		if ( ! $this->scheduling_lock() ) {
			return false;
		}

		if ( $this->is_recurring_scheduled( $id ) ) {
			$this->scheduling_unlock();

			return false;
		}

		$options = $this->get_options();

		$options['recurring'][ $id ] = array( 'data' => $data );
		$this->set_options( $options );

		$args = array( $id );

		// Prevent a flood of cron events all occurring at the same time.
		$time      = isset( $opts['fire_at'] ) ? $opts['fire_at'] : ITSEC_Core::get_current_time_gmt() + 60 * mt_rand( 1, 30 );
		$scheduled = wp_schedule_event( $time, $this->cron_name_for_schedule( $schedule ), self::HOOK, $args );
		$this->scheduling_unlock();

		if ( false === $scheduled ) {
			return false;
		}

		return true;
	}

	public function schedule_once( $at, $id, $data = array() ) {

		if ( ! $this->scheduling_lock() ) {
			return false;
		}

		if ( $this->is_single_scheduled( $id, $data ) ) {
			$this->scheduling_unlock();

			return false;
		}

		$hash = $this->hash_data( $data );
		$args = array( $id, $hash );

		$options                           = $this->get_options();
		$options['single'][ $id ][ $hash ] = array( 'data' => $data );
		$this->set_options( $options );

		$scheduled = wp_schedule_single_event( $at, self::HOOK, $args );
		$this->scheduling_unlock();

		if ( false === $scheduled ) {
			return false;
		}

		return true;
	}

	public function unschedule( $id ) {

		$hash = md5( serialize( array( $id ) ) );

		return $this->unschedule_by_hash( $hash );
	}

	public function unschedule_single( $id, $data = array() ) {
		$hash = md5( serialize( array( $id, $this->hash_data( $data ) ) ) );

		return $this->unschedule_by_hash( $hash );
	}

	private function unschedule_by_hash( $hash ) {

		$crons = _get_cron_array();
		$found = false;

		foreach ( $crons as $timestamp => $hooks ) {
			if ( isset( $hooks[ self::HOOK ][ $hash ] ) ) {
				$found = true;
				unset( $crons[ $timestamp ][ self::HOOK ][ $hash ] );
				break;
			}
		}

		if ( $found ) {
			_set_cron_array( $crons );
		}

		return $found;
	}

	public function get_recurring_events() {

		$crons   = _get_cron_array();
		$options = $this->get_options();
		$events  = array();

		foreach ( $crons as $timestamp => $hooks ) {

			if ( ! isset( $hooks[ self::HOOK ] ) ) {
				continue;
			}

			foreach ( $hooks[ self::HOOK ] as $key => $cron_event ) {

				list( $id ) = $cron_event['args'];

				if ( ! isset( $options['recurring'][ $id ] ) ) {
					continue;
				}

				$events[] = array(
					'id'       => $id,
					'data'     => $options['recurring'][ $id ]['data'],
					'fire_at'  => $timestamp,
					'schedule' => $this->get_api_schedule_from_cron_schedule( $cron_event['schedule'] ),
				);
			}
		}

		return $events;
	}

	public function get_single_events() {

		$crons   = _get_cron_array();
		$options = $this->get_options();
		$events  = array();

		foreach ( $crons as $timestamp => $hooks ) {

			if ( ! isset( $hooks[ self::HOOK ] ) ) {
				continue;
			}

			foreach ( $hooks[ self::HOOK ] as $key => $cron_event ) {

				$id = $cron_event['args'][0];

				if ( ! isset( $options['single'][ $id ] ) ) {
					continue;
				}

				$hash = $cron_event['args'][1];

				if ( ! isset( $options['single'][ $id ][ $hash ] ) ) {
					continue; // Sanity check
				}

				$events[] = array(
					'id'      => $id,
					'data'    => $options['single'][ $id ][ $hash ]['data'],
					'fire_at' => $timestamp,
				);
			}
		}

		return $events;
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
			$maybe_data = $event['data'];

			if ( ! isset( $maybe_data['retry_count'] ) ) {
				continue;
			}

			unset( $maybe_data['retry_count'] );

			if ( $this->hash_data( $maybe_data ) === $this->hash_data( $data ) ) {
				return true;
			}
		}

		return false;
	}

	private function cron_name_for_schedule( $schedule ) {
		switch ( $schedule ) {
			case self::S_HOURLY:
			case self::S_DAILY:
				return $schedule;
			case self::S_TWICE_DAILY:
				return 'twicedaily';
			default:
				return "itsec-{$schedule}";
		}
	}

	private function get_api_schedule_from_cron_schedule( $cron_schedule ) {
		return str_replace( 'itsec-', '', $cron_schedule );
	}

	private function get_options() {
		return wp_parse_args( get_site_option( self::OPTION, array() ), array(
			'single'    => array(),
			'recurring' => array(),
		) );
	}

	private function set_options( $options ) {
		update_site_option( self::OPTION, $options );
	}

	public function uninstall() {

		$crons = _get_cron_array();

		foreach ( $crons as $timestamp => $args ) {
			unset( $crons[ $timestamp ][ self::HOOK ] );

			if ( empty( $crons[ $timestamp ] ) ) {
				unset( $crons[ $timestamp ] );
			}
		}

		_set_cron_array( $crons );

		delete_site_option( self::OPTION );
	}
}
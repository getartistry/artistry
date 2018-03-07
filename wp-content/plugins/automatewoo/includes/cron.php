<?php

namespace AutomateWoo;

/**
 * Cron manager
 * @class Cron
 */
class Cron {

	/** @var array : worker => schedule */
	static $workers = [
		'events' => 'automatewoo_one_minute',
		'two_minute' => 'automatewoo_two_minutes',
		'five_minute' => 'automatewoo_five_minutes',
		'fifteen_minute' => 'automatewoo_fifteen_minutes',
		'thirty_minute' => 'automatewoo_thirty_minutes',
		'hourly' => 'hourly',
		'four_hourly' => 'automatewoo_four_hours',
		'daily' => 'daily',
		'two_days' => 'automatewoo_two_days',
		'weekly' => 'automatewoo_weekly'
	];

	static $strict_workers = [
		'daily' => 'daily'
	];


	/**
	 * Init cron
	 */
	static function init() {

		add_filter( 'cron_schedules', [ __CLASS__, 'add_schedules' ], 100 ); // set a high priority to fix issue from #141

		foreach ( self::$workers as $worker => $schedule ) {
			add_action( 'automatewoo_' . $worker . '_worker', [ __CLASS__, 'before_worker' ], 1 );
		}

		foreach ( self::$strict_workers as $worker => $schedule ) {
			add_action( 'automatewoo_' . $worker . '_worker_strict', [ __CLASS__, 'before_worker' ], 1 );
		}

		add_action( 'admin_init', [ __CLASS__, 'add_events' ] );
	}


	/**
	 * Prevents workers from working if they have done so in the past 4 minutes
	 */
	static function before_worker() {

		$action = current_action();

		if ( self::is_worker_locked( $action ) ) {
			remove_all_actions( $action ); // prevent actions from running
			return;
		}

		@set_time_limit(300); // 5 min

		self::update_last_run( $action );
	}



	/**
	 * @param $action
	 * @return bool
	 */
	static function is_strict( $action ) {
		return (bool) strstr( $action, '_strict' );
	}


	/**
	 * @param $action
	 * @return \DateTime|bool
	 */
	static function get_last_run( $action ) {
		$last_runs = get_option('aw_workers_last_run');
		if ( is_array( $last_runs ) && isset( $last_runs[$action] ) ) {
			$date = new \DateTime();
			$date->setTimestamp( $last_runs[$action] );
			return $date;
		}
		else {
			return false;
		}
	}


	/**
	 * @param $action
	 */
	static function update_last_run( $action ) {
		$last_runs = get_option('aw_workers_last_run');

		if ( ! $last_runs ) $last_runs = [];

		$last_runs[$action] = time();

		update_option( 'aw_workers_last_run', $last_runs, false );
	}


	/**
	 * @param $action
	 * @return int|false
	 */
	static function get_worker_interval( $action ) {
		$schedules = wp_get_schedules();
		$schedule = wp_get_schedule( $action );

		if ( isset( $schedules[$schedule] ) ) {
			return $schedules[$schedule]['interval'];
		}

		return false;
	}



	/**
	 * Checks if workers started running less than 30 seconds
	 *
	 * @param $action
	 * @return bool
	 */
	static function is_worker_locked( $action ) {

		if ( ! $time_last_run = self::get_last_run( $action ) ) {
			return false;
		}

		$time_unlocked = clone $time_last_run;
		$time_unlocked->modify( '+30 seconds' );

		if ( $time_unlocked->getTimestamp() > time() ) {
			return true;
		}

		// additional logic for strict workers
		if ( self::is_strict( $action ) ) {
			return self::is_strict_worker_locked( $action );
		}

		return false;
	}


	/**
	 * @param $action
	 * @return bool
	 */
	static function is_strict_worker_locked( $action ) {

		if ( ! $time_last_run = self::get_last_run( $action ) ) {
			return false;
		}

		if ( $action == 'automatewoo_daily_worker_strict' ) {
			$time_unlocked = clone $time_last_run;
			$time_unlocked->modify( '+23 hours' ); // IMPORTANT allow 1 hour to account for delayed cron jobs
			return $time_unlocked->getTimestamp() > time();
		}

		return false;
	}


	/**
	 * Add cron workers
	 */
	static function add_events() {

		foreach ( self::$workers as $worker => $schedule ) {
			$hook = 'automatewoo_' . $worker . '_worker';

			if ( ! wp_next_scheduled( $hook ) ) {
				wp_schedule_event( time(), $schedule, $hook );
			}
		}

		foreach ( self::$strict_workers as $worker => $schedule ) {
			$strict_hook = 'automatewoo_' . $worker . '_worker_strict';

			if ( ! wp_next_scheduled( $strict_hook ) ) {
				wp_schedule_event( time(), $schedule, $strict_hook );
			}
		}
	}


	/**
	 * @param $schedules
	 * @return mixed
	 */
	static function add_schedules( $schedules ) {

		$schedules['automatewoo_one_minute'] = [
			'interval' => 60,
			'display' => __( 'One minute', 'automatewoo' )
		];

		$schedules['automatewoo_two_minutes'] = [
			'interval' => 120,
			'display' => __( 'Two minutes', 'automatewoo' )
		];

		$schedules['automatewoo_five_minutes'] = [
			'interval' => 300,
			'display' => __( 'Five minutes', 'automatewoo' )
		];

		$schedules['automatewoo_fifteen_minutes'] = [
			'interval' => 900,
			'display' => __( 'Fifteen minutes', 'automatewoo' )
		];

		$schedules['automatewoo_thirty_minutes'] = [
			'interval' => 1800,
			'display' => __( 'Thirty minutes', 'automatewoo' )
		];

		$schedules['automatewoo_two_days'] = [
			'interval' => 172800,
			'display' => __( 'Two days', 'automatewoo' )
		];

		$schedules['automatewoo_four_hours'] = [
			'interval' => 14400,
			'display' => __( 'Four hours', 'automatewoo' )
		];

		$schedules['automatewoo_weekly'] = [
			'interval' => 604800,
			'display' => __('Once weekly', 'automatewoo' )
		];

		return $schedules;
	}

}

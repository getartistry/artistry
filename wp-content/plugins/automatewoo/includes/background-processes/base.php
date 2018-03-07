<?php

namespace AutomateWoo\Background_Processes;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'WP_Async_Request', false ) ) {
	include_once( AW()->lib_path( '/wp-async-request.php' ) );
}

if ( ! class_exists( 'WP_Background_Process', false ) ) {
	include_once( AW()->lib_path( '/wp-background-process.php' ) );
}

/**
 * Base background process class
 */
abstract class Base extends \WP_Background_Process {

	/** @var string */
	public $action;


	public function __construct() {
		$this->prefix = is_multisite() ? 'aw_' . get_current_blog_id() : 'aw';
		parent::__construct();
	}


	/**
	 * @return boolean
	 */
	public function has_queued_items() {
		return false === $this->is_queue_empty();
	}


	/**
	 * Use this instead of dispatch to start process
	 * @return bool|\WP_Error
	 */
	public function start() {

		if ( empty( $this->data ) ) {
			$this->log( 'Started process but there were no items.' );
			return false;
		}

		if ( ! aw_check_instance_integrity() ) {
			$this->log( 'Error code: 6' );
			return false;
		}

		$count = count( $this->data );
		$this->save();
		$dispatched = $this->dispatch();

		if ( is_wp_error( $dispatched ) ) {
			$this->log( sprintf( 'Unable to start process: %s', $dispatched->get_error_message() ) );
		}
		else {
			$this->log( sprintf( 'Process started for %s items.', $count ) );
		}

		return $dispatched;
	}


	/**
	 * Process completed
	 */
	protected function complete() {
		$this->log( 'Process completed.' );
		parent::complete();
	}


	/**
	 * Reduce time limit to 10s
	 * @return bool
	 */
	protected function time_exceeded() {
		$finish = $this->start_time + apply_filters( 'automatewoo/background_process/time_limit', 10 ); // 10 seconds
		$return = false;

		if ( time() >= $finish ) {
			$return = true;
		}

		return $return;
	}


	/**
	 * Reduce memory limit
	 * @return bool
	 */
	protected function memory_exceeded() {

		// use only 40% of max memory
		$memory_limit_percentage = apply_filters( 'automatewoo/background_process/memory_limit_percentage', 0.4 );

		$memory_limit = $this->get_memory_limit() * $memory_limit_percentage;
		$current_memory = memory_get_usage( true );
		$return = false;

		if ( $current_memory >= $memory_limit ) {
			$return = true;
		}

		return $return;
	}


	/**
	 * Handle
	 *
	 * Pass each queue item to the task handler, while remaining
	 * within server memory and time limit constraints.
	 */
	protected function handle() {
		$this->lock_process();

		do {
			$batch = $this->get_batch();

			foreach ( $batch->data as $key => $value ) {
				$task = $this->task( $value );

				if ( false !== $task ) {
					$batch->data[ $key ] = $task;
				} else {
					unset( $batch->data[ $key ] );
				}

				if ( $this->time_exceeded() || $this->memory_exceeded() ) {
					// Batch limits reached.
					break;
				}
			}

			// Update or delete current batch.
			if ( ! empty( $batch->data ) ) {
				$this->update( $batch->key, $batch->data );
			} else {
				$this->delete( $batch->key );
			}
		} while ( ! $this->time_exceeded() && ! $this->memory_exceeded() && ! $this->is_queue_empty() );

		// throttle process here with sleep to try and prevent crashing mysql
		sleep( 5 );

		$this->unlock_process();

		// Start next batch or complete process.
		if ( ! $this->is_queue_empty() ) {
			$this->dispatch();
		} else {
			$this->complete();
		}
	}


	/**
	 * @param $message
	 */
	public function log( $message ) {
		$logger = new \WC_Logger();
		$logger->add( 'automatewoo-background-process', $this->action. ': ' . $message );
	}


	/**
	 * over-ridden due to issue https://github.com/A5hleyRich/wp-background-processing/issues/7
	 *
	 * this method actually creates a new batch rather it doesn't replace existing queued items
	 *
	 * @return $this
	 */
	public function save() {
		$key = $this->generate_key();

		if ( ! empty( $this->data ) ) {
			update_site_option( $key, $this->data );
		}

		$this->data = [];
		return $this;
	}


	/**
	 * Schedule health check if not scheduled
	 */
	public function maybe_schedule_health_check() {
		$this->schedule_event();
	}





	/**
	 * OVERRIDE EWWW IMAGE OPTIMIZER CHANGES
	 */



	/**
	 * Generate key
	 *
	 * Generates a unique key based on microtime. Queue items are
	 * given a unique key so that they can be merged upon save.
	 *
	 * @param int $length Length.
	 *
	 * @return string
	 */
	protected function generate_key( $length = 64 ) {
		$unique  = md5( microtime() . rand() );
		$prepend = $this->identifier . '_batch_';

		return substr( $prepend . $unique, 0, $length );
	}


	/**
	 * Update queue
	 *
	 * @param string $key Key.
	 * @param array  $data Data.
	 *
	 * @return $this
	 */
	public function update( $key, $data ) {
		if ( ! empty( $data ) ) {
			update_site_option( $key, $data );
		}

		return $this;
	}


	/**
	 * Is queue empty
	 *
	 * @return bool
	 */
	protected function is_queue_empty() {
		global $wpdb;

		$table  = $wpdb->options;
		$column = 'option_name';

		if ( is_multisite() ) {
			$table  = $wpdb->sitemeta;
			$column = 'meta_key';
		}

		$key = $this->identifier . '_batch_%';

		$count = $wpdb->get_var( $wpdb->prepare( "
		SELECT COUNT(*)
		FROM {$table}
		WHERE {$column} LIKE %s
	", $key ) );

		return ( $count > 0 ) ? false : true;
	}


	/**
	 * Is process running
	 *
	 * Check whether the current process is already running
	 * in a background process.
	 */
	protected function is_process_running() {
		if ( get_site_transient( $this->identifier . '_process_lock' ) ) {
			// Process already running.
			return true;
		}

		return false;
	}

	/**
	 * Lock process
	 *
	 * Lock the process so that multiple instances can't run simultaneously.
	 * Override if applicable, but the duration should be greater than that
	 * defined in the time_exceeded() method.
	 */
	protected function lock_process() {
		$this->start_time = time(); // Set start time of current process.

		$lock_duration = ( property_exists( $this, 'queue_lock_time' ) ) ? $this->queue_lock_time : 60; // 1 minute
		$lock_duration = apply_filters( $this->identifier . '_queue_lock_time', $lock_duration );

		set_site_transient( $this->identifier . '_process_lock', microtime(), $lock_duration );
	}

	/**
	 * Unlock process
	 *
	 * Unlock the process so that other instances can spawn.
	 *
	 * @return $this
	 */
	protected function unlock_process() {
		delete_site_transient( $this->identifier . '_process_lock' );

		return $this;
	}

	/**
	 * Get batch
	 *
	 * @return \stdClass Return the first batch from the queue
	 */
	protected function get_batch() {
		global $wpdb;

		$table        = $wpdb->options;
		$column       = 'option_name';
		$key_column   = 'option_id';
		$value_column = 'option_value';

		if ( is_multisite() ) {
			$table        = $wpdb->sitemeta;
			$column       = 'meta_key';
			$key_column   = 'meta_id';
			$value_column = 'meta_value';
		}

		$key = $this->identifier . '_batch_%';

		$query = $wpdb->get_row( $wpdb->prepare( "
		SELECT *
		FROM {$table}
		WHERE {$column} LIKE %s
		ORDER BY {$key_column} ASC
		LIMIT 1
		", $key ) );

		$batch       = new \stdClass();
		$batch->key  = $query->$column;
		$batch->data = maybe_unserialize( $query->$value_column );

		return $batch;
	}


	/**
	 * Get memory limit
	 *
	 * @return int
	 */
	protected function get_memory_limit() {
		if ( function_exists( 'ini_get' ) ) {
			$memory_limit = ini_get( 'memory_limit' );
		} else {
			// Sensible default.
			$memory_limit = '128M';
		}

		if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {
			// Unlimited, set to 32GB.
			$memory_limit = '32000M';
		}

		return intval( $memory_limit ) * 1024 * 1024;
	}


	/**
	 * Schedule cron healthcheck
	 *
	 * @access public
	 * @param mixed $schedules Schedules.
	 * @return mixed
	 */
	public function schedule_cron_healthcheck( $schedules ) {
		$interval = apply_filters( $this->identifier . '_cron_interval', 5 );

		if ( property_exists( $this, 'cron_interval' ) ) {
			$interval = apply_filters( $this->identifier . '_cron_interval', $this->cron_interval_identifier );
		}

		// Adds every 5 minutes to the existing schedules.
		$schedules[ $this->identifier . '_cron_interval' ] = array(
			'interval' => MINUTE_IN_SECONDS * $interval,
			'display'  => sprintf( 'Every %d minutes', $interval ),
		);

		return $schedules;
	}

	/**
	 * Handle cron healthcheck
	 *
	 * Restart the background process if not already running
	 * and data exists in the queue.
	 */
	public function handle_cron_healthcheck() {
		if ( $this->is_process_running() ) {
			// Background process already running.
			exit;
		}

		if ( $this->is_queue_empty() ) {
			// No data to process.
			$this->clear_scheduled_event();
			exit;
		}

		$this->handle();

		exit;
	}

	/**
	 * Schedule event
	 */
	protected function schedule_event() {
		if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
			wp_schedule_event( time(), $this->cron_interval_identifier, $this->cron_hook_identifier );
		}
	}

	/**
	 * Clear scheduled event
	 */
	protected function clear_scheduled_event() {
		$timestamp = wp_next_scheduled( $this->cron_hook_identifier );

		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, $this->cron_hook_identifier );
		}
	}

	/**
	 * Cancel Process
	 *
	 * Stop processing queue items, clear cronjob and delete batch.
	 *
	 */
	public function cancel_process() {
		if ( ! $this->is_queue_empty() ) {
			$batch = $this->get_batch();

			$this->delete( $batch->key );

			wp_clear_scheduled_hook( $this->cron_hook_identifier );
		}

	}

}

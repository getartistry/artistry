<?php

/**
 * File Change Detection Execution and Processing
 *
 * Handles all file change detection execution once the feature has been
 * enabled by the user.
 *
 * @since   4.0.0
 *
 * @package iThemes_Security
 */
class ITSEC_File_Change {

	/**
	 * Setup the module's functionality
	 *
	 * Loads the file change detection module's unpriviledged functionality including
	 * performing the scans themselves
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	function run() {

		add_action( 'itsec_execute_file_check_cron', array( $this, 'run_scan' ) ); //Action to execute during a cron run.

		add_filter( 'itsec_logger_displays', array( $this, 'itsec_logger_displays' ) ); //adds logs metaboxes
		add_filter( 'itsec_logger_modules', array( $this, 'itsec_logger_modules' ) );
		add_action( 'ithemes_sync_register_verbs', array( $this, 'register_sync_verbs' ) );
		add_filter( 'itsec_notifications', array( $this, 'register_notification' ) );
		add_filter( 'itsec_file-change_notification_strings', array( $this, 'register_notification_strings' ) );

		add_action( 'itsec_scheduler_register_events', array( $this, 'register_event' ) );
		add_action( 'itsec_scheduled_file-change', array( $this, 'run_scan' ) );
	}

	public function run_scan() {
		require_once( dirname( __FILE__ ) . '/scanner.php' );

		return ITSEC_File_Change_Scanner::run_scan();
	}

	/**
	 * Register the file change scan event.
	 *
	 * @param ITSEC_Scheduler $scheduler
	 */
	public function register_event( $scheduler ) {

		// If we're splitting the file check run it every 6 hours.
		$split    = ITSEC_Modules::get_setting( 'file-change', 'split', false );
		$interval = $split ? ITSEC_Scheduler::S_FOUR_DAILY : ITSEC_Scheduler::S_DAILY;

		$scheduler->schedule( $interval, 'file-change' );
	}

	/**
	 * Register file change detection for logger
	 *
	 * Registers the file change detection module with the core logger functionality.
	 *
	 * @since 4.0.0
	 *
	 * @param  array $logger_modules array of logger modules
	 *
	 * @return array array of logger modules
	 */
	public function itsec_logger_modules( $logger_modules ) {

		$logger_modules['file_change'] = array(
			'type'     => 'file_change',
			'function' => __( 'File Changes Detected', 'it-l10n-ithemes-security-pro' ),
		);

		return $logger_modules;

	}

	/**
	 * Array of displays for the logs screen
	 *
	 * Registers the custom log page with the core plugin to allow for access from the log page's
	 * dropdown menu.
	 *
	 * @since 4.0.0
	 *
	 * @param array $displays metabox array
	 *
	 * @return array metabox array
	 */
	public function itsec_logger_displays( $displays ) {

		$displays[] = array(
			'module'   => 'file_change',
			'title'    => __( 'File Change History', 'it-l10n-ithemes-security-pro' ),
			'callback' => array( $this, 'logs_metabox_content' )
		);

		return $displays;

	}

	/**
	 * Render the file change log metabox
	 *
	 * Displays a metabox on the logs page, when filtered, showing all file change items.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function logs_metabox_content() {

		if ( ! class_exists( 'ITSEC_File_Change_Log' ) ) {
			require( dirname( __FILE__ ) . '/class-itsec-file-change-log.php' );
		}


		$settings = ITSEC_Modules::get_settings( 'file-change' );


		// If we're splitting the file check run it every 6 hours. Else daily.
		if ( isset( $settings['split'] ) && true === $settings['split'] ) {

			$interval = 12342;

		} else {

			$interval = 86400;

		}

		$next_run_raw = $settings['last_run'] + $interval;

		if ( date( 'j', $next_run_raw ) == date( 'j', ITSEC_Core::get_current_time() ) ) {
			$next_run_day = __( 'Today', 'it-l10n-ithemes-security-pro' );
		} else {
			$next_run_day = __( 'Tomorrow', 'it-l10n-ithemes-security-pro' );
		}

		$next_run = $next_run_day . ' at ' . date( 'g:i a', $next_run_raw );

		echo '<p>' . __( 'Next automatic scan at: ', 'it-l10n-ithemes-security-pro' ) . '<strong>' . $next_run . '*</strong></p>';
		echo '<p><em>*' . __( 'Automatic file change scanning is triggered by a user visiting your page and may not happen exactly at the time listed.', 'it-l10n-ithemes-security-pro' ) . '</em>';

		$log_display = new ITSEC_File_Change_Log();

		$log_display->prepare_items();
		$log_display->display();

	}

	/**
	 * Register verbs for Sync.
	 *
	 * @since 3.6.0
	 *
	 * @param Ithemes_Sync_API $api Sync API object.
	 */
	public function register_sync_verbs( $api ) {
		$api->register( 'itsec-perform-file-scan', 'Ithemes_Sync_Verb_ITSEC_Perform_File_Scan', dirname( __FILE__ ) . '/sync-verbs/itsec-perform-file-scan.php' );
	}

	/**
	 * Register the file change notification.
	 *
	 * @param array $notifications
	 *
	 * @return array
	 */
	public function register_notification( $notifications ) {
		$notifications['file-change'] = array(
			'recipient'        => ITSEC_Notification_Center::R_USER_LIST_ADMIN_UPGRADE,
			'schedule'         => ITSEC_Notification_Center::S_NONE,
			'subject_editable' => true,
			'optional'         => true,
			'module'           => 'file-change',
		);

		return $notifications;
	}

	/**
	 * Register the file change notification strings.
	 *
	 * @return array
	 */
	public function register_notification_strings() {
		return array(
			'label'       => esc_html__( 'File Change', 'it-l10n-ithemes-security-pro' ),
			'description' => sprintf( esc_html__( 'The %1$sFile Change Detection%2$s module will email a file scan report after changes have been detected.', 'it-l10n-ithemes-security-pro' ), '<a href="#" data-module-link="file-change">', '</a>' ),
			'subject'     => esc_html__( 'File Change Warning', 'it-l10n-ithemes-security-pro' ),
		);
	}
}

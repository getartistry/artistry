<?php

namespace AutomateWoo;

/**
 * System check management class
 *
 * @class System_Checks
 */
class System_Checks {

	/** @var array */
	static $system_checks;

	/**
	 * @return Base_System_Check[]
	 */
	static function get_all() {

		if ( ! isset( self::$system_checks ) ) {

			$path = AW()->path( '/includes/system-checks/' );

			$includes = apply_filters( 'automatewoo/system_checks', [
				'cron_running' => $path . 'cron-running.php',
				'database_tables_exist' => $path . 'database-tables-exist.php',
			]);

			include_once $path . 'base.php';

			foreach ( $includes as $system_check_id => $include ) {
				$class = include_once $include;
				self::$system_checks[ $system_check_id ] = $class;
			}
		}

		return self::$system_checks;
	}


	/**
	 * Maybe background check for high priority issues
	 */
	static function maybe_schedule_check() {

		if ( did_action( 'automatewoo_installed' ) ) {
			return; // bail if just installed
		}

		if ( ! AW()->options()->enable_background_system_check || get_transient('automatewoo_background_system_check') ) {
			return;
		}

		Events::schedule_async_event( 'automatewoo/system_check', [], 300  );

		set_transient( 'automatewoo_background_system_check', true, DAY_IN_SECONDS * 4 );
	}


	static function run_system_check() {

		foreach( self::get_all() as $check ) {

			if ( ! $check->high_priority )
				continue;

			$response = $check->run();

			if ( $response['success'] == false ) {
				set_transient( 'automatewoo_background_system_check_errors', true, DAY_IN_SECONDS );
				continue;
			}
		}

	}


	static function maybe_display_notices() {
		if ( ! get_transient('automatewoo_background_system_check_errors') || ! current_user_can('manage_woocommerce') ) {
			return;
		}

		$strong = __( 'AutomateWoo status check has found issues.', 'automatewoo' );
		$more = sprintf( __( '<a href="%s">View details</a>', 'automatewoo' ), Admin::page_url( 'status' ) );

		Admin::notice('error is-dismissible', $strong, $more, 'aw-notice-system-error' );
	}


}

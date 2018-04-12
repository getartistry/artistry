<?php
/**
 * Convert Pro Cron job file.
 *
 * @package Convert Pro Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register cron on activation of plugin
 */
function cpro_addon_activate() {

	if ( ! wp_next_scheduled( 'cpro_ab_test_check_event' ) ) {
		wp_schedule_event( time(), 'hourly', 'cpro_ab_test_check_event' );
	}

	if ( ! wp_next_scheduled( 'cpro_google_analytics_resync' ) ) {
		wp_schedule_event( time(), 'hourly', 'cpro_google_analytics_resync' );
	}
}

/**
 * Remove cron on deactivation of plugin
 */
function cpro_addon_deactivate() {
	if ( wp_next_scheduled( 'cpro_ab_test_check_event' ) ) {
		wp_clear_scheduled_hook( 'cpro_ab_test_check_event' );
	}

	if ( wp_next_scheduled( 'cpro_google_analytics_resync' ) ) {
		wp_clear_scheduled_hook( 'cpro_google_analytics_resync' );
	}
}

/**
 * Deactivate expired A/B Tests
 */
function cpro_check_ab_test_expiry() {

	if ( class_exists( 'CP_V2_AB_Test' ) ) {

		$ab_test_inst = CP_V2_AB_Test::get_instance();
		$ab_test_inst->inactive_expired_tests();

	}
}

add_action( 'cpro_ab_test_check_event', 'cpro_check_ab_test_expiry' );

/**
 * Sync Analytics data for designs
 */
function cpro_ga_resync() {

	if ( class_exists( 'CP_V2_GA' ) ) {

		$ga_option = get_option( 'cp_ga_analytics_data' );

		if ( ! ( ! is_array( $ga_option ) && 'false' != $ga_option ) ) {
			$obj = new CP_V2_GA();
			$obj->resync_ga_data_cron();
		}
	}
}

add_action( 'cpro_google_analytics_resync', 'cpro_ga_resync' );

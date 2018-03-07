<?php

namespace AutomateWoo;

/**
 * @class Hooks
 * @since 2.6.7
 */
class Hooks {

	/**
	 * Add 'init' actions here means we can load less files at 'init'
	 */
	static function init() {
		$self = 'AutomateWoo\Hooks'; /** @var $self Hooks (for IDE) */

		// addons
		add_action( 'automatewoo/addons/activate', [ $self , 'activate_addon' ] );

		// workflows
		add_action( 'delete_post', [ $self, 'maybe_cleanup_workflow_data' ] );

		// frontend action endpoints
		add_action( 'wp_loaded', [ $self, 'check_for_action_endpoint' ] );

		// events
		add_action( 'automatewoo_events_worker', [ 'AutomateWoo\Events', 'run_due_events' ] );

		// email
		add_filter( 'automatewoo_email_content', 'wptexturize' );
		add_filter( 'automatewoo_email_content', 'convert_smilies');
		add_filter( 'automatewoo_email_content', 'wpautop' );

		// pre-submit
		if ( AW()->options()->abandoned_cart_enabled ) {
			add_action( 'wp_footer', [ $self, 'maybe_print_presubmit_js' ] );
			add_action( 'automatewoo/ajax/capture_email', [ 'AutomateWoo\PreSubmit', 'ajax_capture_email' ] );
			add_action( 'automatewoo/ajax/capture_checkout_field', [ 'AutomateWoo\PreSubmit', 'ajax_capture_checkout_field' ] );
		}

		// conversions
		add_action( 'woocommerce_checkout_order_processed', [ 'AutomateWoo\Conversions', 'check_order_for_conversion' ], 20 );

		// tools
		add_action( 'automatewoo/tools/background_process', [ 'AutomateWoo\Tools', 'handle_background_process' ], 10, 2 );

		// queue
		add_action( 'automatewoo_five_minute_worker', [ 'AutomateWoo\Queue_Manager', 'check_for_queued_events' ] );
		add_action( 'automatewoo_four_hourly_worker', [ 'AutomateWoo\Queue_Manager', 'check_for_failed_queued_events' ] );

		// coupons
		add_action( 'automatewoo_four_hourly_worker', [ 'AutomateWoo\Coupons', 'schedule_clean_expired' ] );
		add_action( 'automatewoo/coupons/clean_expired', [ 'AutomateWoo\Coupons', 'clean_expired' ] );

		add_action( 'get_header', [ 'AutomateWoo\Language', 'make_language_persistent' ] );

		// object caching
		add_action( 'automatewoo/object/load', [ 'AutomateWoo\Factories', 'update_object_cache' ] );
		// clean cache on object create, as a black cache value is used for carts, for example
		add_action( 'automatewoo/object/create', [ 'AutomateWoo\Factories', 'clean_object_cache' ] );
		add_action( 'automatewoo/object/update', [ 'AutomateWoo\Factories', 'clean_object_cache' ] );
		add_action( 'automatewoo/object/delete', [ 'AutomateWoo\Factories', 'clean_object_cache' ] );

		// license
		add_action( 'admin_init', [ 'AutomateWoo\Licenses', 'maybe_check_status' ] );
		add_action( 'automatewoo_license_reset_status_check_timer', [ 'AutomateWoo\Licenses', 'reset_status_check_timer' ] );

		// system check
		add_action( 'admin_init', [ 'AutomateWoo\System_Checks', 'maybe_schedule_check' ], 20 );
		add_action( 'admin_notices', [ 'AutomateWoo\System_Checks', 'maybe_display_notices' ] );
		add_action( 'automatewoo/system_check', [ 'AutomateWoo\System_Checks', 'run_system_check' ] );

		add_action( 'automatewoo_updated_async', 'flush_rewrite_rules' );
	}


	/**
	 * @param $addon_id
	 */
	static function activate_addon( $addon_id ) {
		if ( $addon = Addons::get( $addon_id ) ) {
			$addon->activate();
		}
	}


	/**
	 * @param $id
	 */
	static function maybe_cleanup_workflow_data( $id ) {
		if ( get_post_type( $id ) !== 'aw_workflow' ) return;
		Workflow_Manager::delete_related_data( $id );
	}


	/**
	 * Action endpoints
	 */
	static function check_for_action_endpoint() {
		if ( empty( $_GET[ 'aw-action' ] ) || is_ajax() || is_admin() ) {
			return;
		}

		Frontend_Endpoints::handle();
	}


	/**
	 * Maybe print pre-submit js
	 */
	static function maybe_print_presubmit_js() {

		if ( ! AW()->options()->session_tracking_enabled || is_user_logged_in() ) {
			return;
		}

		switch( AW()->options()->guest_email_capture_scope ) {
			case 'none':
				return;
				break;
			case 'checkout':
				if ( ! is_checkout() ) return;
				break;
		}

		PreSubmit::print_js();
	}

}

<?php
/**
 * Convert Pro Addon AJAX file
 *
 * @package Convert Pro Addon
 * @author Brainstorm Force
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Responsible for setting AJAX actions.
 *
 * @since 1.0.0
 */
final class CPRO_Ajax {

	/**
	 * An array of registered action data.
	 *
	 * @since 1.0.0
	 * @var array $actions
	 */
	static private $actions = array();

	/**
	 * Initializes hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function init() {

		add_action( 'wp_ajax_cp_v2_add_subscriber', 'ConvertPlugServices::add_subscriber' );
		add_action( 'wp_ajax_nopriv_cp_v2_add_subscriber', 'ConvertPlugServices::add_subscriber' );
		add_action( 'admin_init', __CLASS__ . '::run', 10 );
	}

	/**
	 * Runs AJAX.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function run() {
		self::add_actions();
		self::call_action();
	}

	/**
	 * Adds a callable AJAX action.
	 *
	 * @since 1.0.0
	 * @param string $action The action name.
	 * @param string $method The method to call.
	 * @param array  $args An array of method arg names that are present in the post data.
	 * @return void
	 */
	static public function add_action( $action, $method, $args = array() ) {

		self::$actions[ $action ] = array(
			'action' => $action,
			'method' => $method,
			'args'   => $args,
		);
	}

	/**
	 * Removes an AJAX action.
	 *
	 * @since 1.0.0
	 * @param string $action The action to remove.
	 * @return void
	 */
	static public function remove_action( $action ) {
		if ( isset( self::$actions[ $action ] ) ) {
			unset( self::$actions[ $action ] );
		}
	}

	/**
	 * Adds all callable AJAX actions.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	static private function add_actions() {

		if ( ! current_user_can( 'access_cp_pro' ) ) {
			return;
		}

		self::add_action( 'cppro_get_assets_data', 'ConvertPlugServices::get_assets_data' );

		self::add_action( 'cppro_render_service_settings', 'ConvertPlugServices::render_settings' );
		self::add_action( 'cppro_save_service_settings', 'ConvertPlugServices::save_settings' );
		self::add_action( 'cppro_render_service_fields', 'ConvertPlugServices::render_fields' );
		self::add_action( 'cppro_connect_service', 'ConvertPlugServices::connect_service' );
		self::add_action( 'cppro_delete_service_account', 'ConvertPlugServices::delete_account' );
		self::add_action( 'cppro_render_service_accounts', 'ConvertPlugServices::render_service_accounts' );
		self::add_action( 'cppro_save_meta_settings', 'ConvertPlugServices::save_meta' );

	}

	/**
	 * Runs the current AJAX action.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
	static private function call_action() {

		// Only run for logged in users.
		if ( ! is_user_logged_in() ) {
			return;
		}

		// Get the action.
		if ( ! empty( $_REQUEST['action'] ) ) {
			$action = $_REQUEST['action'];
		} elseif ( ! empty( $post_data['action'] ) ) {
			$action = $post_data['action'];
		} else {
			return;
		}

		// Allow developers to modify actions before they are called.
		do_action( 'cp_v2_ajax_before_call_action', $action );

		// Make sure the action exists.
		if ( ! isset( self::$actions[ $action ] ) ) {
			return;
		}

		// Get the action data.
		$action    = self::$actions[ $action ];
		$args      = array();
		$keys_args = array();

		// Build the args array.
		foreach ( $action['args'] as $arg ) {
			$keys_args[ $arg ] = isset( $post_data[ $arg ] ) ? $post_data[ $arg ] : null;
			$args[]            = $keys_args[ $arg ];
		}

		// Tell WordPress this is an AJAX request.
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		// Allow developers to hook before the action runs.
		do_action( 'cp_v2_ajax_before_' . $action['action'], $keys_args );

		// Call the action and allow developers to filter the result.
		$result = apply_filters( 'cp_v2_ajax_' . $action['action'], call_user_func_array( $action['method'], $args ), $keys_args );

		// Allow developers to hook after the action runs.
		do_action( 'cp_v2_ajax_after_' . $action['action'], $keys_args );

		// JSON encode the result.
		echo json_encode( $result );

		// Complete the request.
		die();
	}

}

CPRO_Ajax::init();

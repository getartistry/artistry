<?php

namespace AutomateWoo;

/**
 * @deprecated
 * @class Admin_Controller_Abstract
 */
abstract class Admin_Controller_Abstract {

	/** @var array */
	static $messages = [];

	/** @var array  */
	static $errors = [];

	/** @var string */
	static $default_route = 'list';

	/** @var string  */
	protected static $nonce_action = 'automatewoo-action';


	/**
	 *
	 */
	static function output_messages() {

		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo self::format_notice( $error, 'error' );
			}
		}
		elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo self::format_notice( $message, 'success' );
			}
		}
	}


	/**
	 * @param $notice_data
	 * @param $type
	 * @return string
	 */
	static function format_notice( $notice_data, $type ) {

		$class = "notice notice-$type automatewoo-notice";

		if ( is_array( $notice_data ) ) {
			$main_text = $notice_data['main'];
			$extra_text = isset($notice_data['extra']) ? $notice_data['extra'] : '';
			$class .= ' ' . $notice_data['class'];
		}
		else {
			$main_text = $notice_data;
			$extra_text = '';
		}

		return '<div class="' . $class .'"><p><strong>'.esc_html($main_text).'</strong> '.$extra_text.'</p></div>';
	}


	/**
	 * @since 2.7.8
	 * @return string
	 */
	static function get_messages() {
		ob_start();
		self::output_messages();
		return ob_get_clean();
	}


	static function get_current_route() {
		if ( $action = Clean::string( aw_request( 'action' ) ) ) {
			return $action;
		}

		return self::$default_route;
	}


	/**
	 * @return string
	 */
	static function get_current_action() {

		if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
			return Clean::string( $_REQUEST['action'] );

		if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
			return Clean::string( $_REQUEST['action2'] );

		return self::$default_route;
	}


	/**
	 * @return string
	 */
	static function get_nonce_action() {
		return static::$nonce_action;
	}


	/**
	 * Verify nonce
	 */
	protected static function verify_nonce_action() {
		$nonce = Clean::string( aw_request( '_wpnonce' ) );
		if ( ! wp_verify_nonce( $nonce, static::$nonce_action ) ) {
			wp_die( 'Security check failed.' );
		}
	}


	/**
	 * @param string $main_text
	 * @param string $extra_text
	 * @param string $extra_classes
	 */
	static function add_message( $main_text, $extra_text = '', $extra_classes = '' ) {
		static::$messages[] = [
			'main' => $main_text,
			'extra' => $extra_text,
			'class' => $extra_classes
		];
	}


	/**
	 * @param string $main_text
	 * @param string $extra_text
	 * @param string $extra_classes
	 */
	static function add_error( $main_text, $extra_text = '', $extra_classes = '' ) {
		static::$errors[] = [
			'main' => $main_text,
			'extra' => $extra_text,
			'class' => $extra_classes
		];
	}


	/**
	 * @return string
	 */
	static function get_responses_option_name() {
		return '_automatewoo_admin_temp_messages_' . get_current_user_id();
	}


	static function store_responses() {
		update_option( static::get_responses_option_name(), [
			'errors' => static::$errors,
			'messages' => static::$messages
		], false );
	}


	static function load_stored_responses() {
		if ( $store = get_option( static::get_responses_option_name() ) ) {
			static::$messages = $store['messages'];
			static::$errors = $store['errors'];
		}
		self::clear_stored_responses();
	}


	static function clear_stored_responses() {
		delete_option( static::get_responses_option_name() );
	}

}

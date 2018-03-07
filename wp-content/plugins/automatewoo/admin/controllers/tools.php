<?php

namespace AutomateWoo\Admin\Controllers;

use AutomateWoo\Clean;
use AutomateWoo\Tool;
use AutomateWoo\Tools;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Tools_Controller
 */
class Tools_Controller extends Base {


	function handle() {

		$tool_id = Clean::string( aw_request( 'tool_id' ) );

		switch ( $this->get_current_action() ) {

			case 'view':
				$this->output_view_form( $tool_id );
				break;

			case 'validate':
				if ( $this->validate_process( $tool_id ) ) {
					$this->output_view_confirm( $tool_id );
				}
				else {
					$this->output_view_form( $tool_id );
				}

				break;

			case 'confirm':
				$this->confirm_process( $tool_id );
				$this->output_view_listing();
				break;

			default:
				$this->output_view_listing();
		}

		wp_enqueue_script( 'automatewoo-tools' );
	}


	private function output_view_listing() {
		$this->output_view( 'page-tools-list', [
			'tools' => Tools::get_tools()
		]);
	}


	/**
	 * @param $tool_id
	 */
	private function output_view_form( $tool_id ) {
		$tool = Tools::get_tool( $tool_id );

		$this->output_view( 'page-tools-form', [
			'tool' => $tool
		] );
	}


	/**
	 * @param $tool_id
	 */
	private function output_view_confirm( $tool_id ) {

		$tool = Tools::get_tool( $tool_id );

		$this->output_view( 'page-tools-form-confirm', [
			'tool' => $tool,
			'args' => aw_request( 'args' )
		]);
	}


	/**
	 * Return true if init was successful
	 *
	 * @param $tool_id string
	 * @return bool
	 */
	private function validate_process( $tool_id ) {

		$tool = Tools::get_tool( $tool_id );
		$args = aw_request('args');

		if ( ! $tool ) {
			wp_die( __( 'Invalid tool.', 'automatewoo' ) );
		}

		$valid = $tool->validate_process( $args );

		if ( $valid === false ) {
			$this->add_error( __( 'Failed to init process.', 'automatewoo' ) );
			return false;
		}
		elseif ( is_wp_error( $valid ) ) {
			$this->add_error( $valid->get_error_message() );
			return false;
		}
		elseif ( $valid === true ) {
			return true;
		}
		return false;
	}


	/**
	 * @param $tool_id
	 */
	private function confirm_process( $tool_id ) {

		$nonce = Clean::string( aw_request('_wpnonce') );

		if ( ! wp_verify_nonce( $nonce, $tool_id ) ) {
			wp_die( __( 'Security check failed.', 'automatewoo' ) );
		}

		// Process should be valid at this point but just in case
		if ( ! $this->validate_process( $tool_id ) ) {
			wp_die( __( 'Process could not be validated.', 'automatewoo' ) );
		}

		$tool = Tools::get_tool( $tool_id );
		$args = aw_request( 'args' );

		$processed = $tool->process( $args );

		if ( $processed === false ) {
			$this->add_error( __( 'Process failed.', 'automatewoo' ) );
		}
		elseif ( is_wp_error( $processed ) ) {
			$this->add_error( $processed->get_error_message() );
		}
		elseif ( $processed === true ) {
			$this->add_message( __( 'Success - Items may be still be processing in the background.', 'automatewoo' ) );
		}
	}


	/**
	 * @param string|bool $route
	 * @param Tool|bool $tool
	 * @return string
	 */
	function get_route_url( $route = false, $tool = false ) {

		$base_url = admin_url( 'admin.php?page=automatewoo-tools' );

		if ( ! $route ) {
			return $base_url;
		}

		return add_query_arg([
			'action' => $route,
			'tool_id' => $tool->get_id()
		], $base_url );
	}

}

return new Tools_Controller();

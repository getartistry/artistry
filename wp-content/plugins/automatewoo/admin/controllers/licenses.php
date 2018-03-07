<?php

namespace AutomateWoo\Admin\Controllers;

use AutomateWoo\Clean;
use AutomateWoo\Addons;
use AutomateWoo\Licenses;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Licenses_Controller
 */
class Licenses_Controller extends Base {


	function handle() {

		$action = $this->get_current_action();

		switch ( $action ) {

			case 'activate':
				$this->action_activate();
				break;

			case 'deactivate':
				$this->action_deactivate();
				break;

			case 'activated':
			case 'deactivated':
				$this->load_stored_responses();
				$this->output_page();
				break;

			default:
				$this->output_page();
				break;
		}
	}


	function output_page() {

		Licenses::check_for_domain_mismatch();
		Licenses::maybe_check_status( true );

		$this->output_view( 'page-licenses', [
			'dev_check' => Licenses::is_valid_dev_domain()
		]);
	}


	function action_activate() {

		$this->verify_nonce_action();

		$license_keys = Clean::recursive( aw_request( 'license_keys' ) );
		$core_activated = false;

		foreach ( $license_keys as $product_id => $license_key ) {

			if ( empty( $license_key ) ) {
				continue;
			}

			$activate = Licenses::remote_activate( $product_id, $license_key );

			if ( Licenses::is_primary( $product_id ) ) {

				if ( is_wp_error( $activate ) ) {
					$this->add_error( __( 'AutomateWoo', 'automatewoo' ) . ' - ' . $activate->get_error_message() );
				}
				else {
					$core_activated = true;
				}
			}
			else {
				$addon = Addons::get( $product_id );
				$notice_extra = '';

				if ( $start_url = $addon->get_getting_started_url() ) {
					$notice_extra .= ' <a href="' . esc_url( $start_url ) . '" target="_blank">'. __( 'View getting started guide', 'automatewoo' ) .'</a>';
				}

				if ( is_wp_error( $activate ) ) {
					$this->add_error( $addon->name . ' - ' . $activate->get_error_message() );
				}
				else {
					$this->add_message( sprintf( __( '%s activated successfully.', 'automatewoo' ), $addon->name ), $notice_extra );
				}

				if ( isset( $addon ) ) {
					// activate the addon on the next request because addon is not initiated right now
					wp_schedule_single_event( time(), 'automatewoo/addons/activate', [ $addon->id ] );
				}
			}
		}

		$this->redirect_after_action( 'activated', [
			'core_activated' => $core_activated
		]);
	}


	function action_deactivate() {
		$this->verify_nonce_action();

		$product_id = Clean::string( $_GET[ 'product' ] );

		if ( Licenses::is_active( $product_id ) ) {
			Licenses::remote_deactivate( $product_id );
			$this->add_message( __( 'Your license was successfully deactivated.', 'automatewoo' ) );
		}

		$this->redirect_after_action( 'deactivated' );
	}


}

return new Licenses_Controller();

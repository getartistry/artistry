<?php
/* This file contains code from the Software Licensing addon by Easy Digital Downloads - GPLv2.0 or higher */
if (!defined('ABSPATH')) exit;

function DS_PBE_activate_license() {
	// retrieve the license from the database
	$license = trim( get_option( 'DS_PBE_license_key' ) );

	// data to send in our API request
	$api_params = array(
		'edd_action' => 'activate_license',
		'license'    => $license,
		'item_name'  => urlencode( DS_PBE_ITEM_NAME ), // the name of our product in EDD
		'url'        => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( DS_PBE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$message = __( 'An error occurred, please try again.', 'aspengrove-updater' );
		}

	} else {

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( false === $license_data->success ) {

			switch( $license_data->error ) {

				case 'expired' :

					$message = sprintf(
						__( 'Your license key expired on %s.', 'aspengrove-updater' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					break;

				case 'revoked' :

					$message = __( 'Your license key has been disabled.', 'aspengrove-updater' );
					break;

				case 'missing' :

					$message = __( 'Invalid license key.', 'aspengrove-updater' );
					break;

				case 'invalid' :
				case 'site_inactive' :

					$message = __( 'Your license key is not active for this URL.', 'aspengrove-updater' );
					break;

				case 'item_name_mismatch' :

					$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'aspengrove-updater' ), DS_PBE_ITEM_NAME );
					break;

				case 'no_activations_left':

					$message = __( 'Your license key has reached its activation limit. Please deactivate the key on one of your other sites before activating it on this site.', 'aspengrove-updater' );
					break;

				default :

					$message = __( 'An error occurred, please try again.', 'aspengrove-updater' );
					break;
			}

		}

	}

	// Check if anything passed on a message constituting a failure
	if ( ! empty( $message ) ) {
		delete_option('DS_PBE_license_key');
	
		$base_url = admin_url( DS_PBE_PLUGIN_PAGE );
		$redirect = add_query_arg( array( 'sl_activation' => 'false', 'sl_message' => urlencode( $message ), 'license_key' => $license ), $base_url );
		
		wp_redirect( $redirect );
		exit();
	}

	// $license_data->license will be either "valid" or "invalid"

	update_option( 'DS_PBE_license_status', $license_data->license );
	wp_redirect( admin_url( DS_PBE_PLUGIN_PAGE ) );
	exit();
}

function DS_PBE_deactivate_license() {

	// run a quick security check
	if( ! check_admin_referer( 'DS_PBE_license_key_deactivate', 'DS_PBE_license_key_deactivate' ) )
		return; // get out if we didn't click the Activate button

	// retrieve the license from the database
	$license = trim( get_option( 'DS_PBE_license_key' ) );

	// data to send in our API request
	$api_params = array(
		'edd_action' => 'deactivate_license',
		'license'    => $license,
		'item_name'  => urlencode( DS_PBE_ITEM_NAME ), // the name of our product in EDD
		'url'        => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( DS_PBE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$message = __( 'An error occurred, please try again.', 'aspengrove-updater' );
		}

		$base_url = admin_url( DS_PBE_PLUGIN_PAGE );
		$redirect = add_query_arg( array( 'sl_activation' => 'false', 'sl_message' => urlencode( $message ) ), $base_url );

		wp_redirect( $redirect );
		exit();
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
	// $license_data->license will be either "deactivated" or "failed"
	if ($license_data->license == 'deactivated') {
		delete_option('DS_PBE_license_status');
		delete_option('DS_PBE_license_key');
	} else {
		$base_url = admin_url( DS_PBE_PLUGIN_PAGE );
		$redirect = add_query_arg( array( 'sl_activation' => 'false', 'sl_message' => urlencode(__('An error occurred during license key deactivation. Please try again or contact support.', 'aspengrove-updater')) ), $base_url );

		wp_redirect( $redirect );
		exit();
	}

	wp_redirect( admin_url( DS_PBE_PLUGIN_PAGE ) );
	exit();
}
?>
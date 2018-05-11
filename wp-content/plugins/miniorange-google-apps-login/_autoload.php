<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MOV_GSUITE_VERSION', '2.1.0' );
define( 'MOV_GSUITE_DIR', plugin_dir_path( __FILE__ ) );
define( 'MOV_GSUITE_URL', plugin_dir_url( __FILE__ ) );
define( 'MOV_GSUITE_CSS_URL', MOV_GSUITE_URL . 'includes/css/mo_customer_validation_style.min.css?version=' . MOV_GSUITE_VERSION );


define( 'MOV_GSUITE_FONT_AWESOME_URL', MOV_GSUITE_URL . 'includes/css/font-awesome.css' );
define( 'MOV_OAUTH_CSS_URL', MOV_GSUITE_URL . 'includes/css/mo_oauth_style.css?version=' . MOV_GSUITE_VERSION );
define( 'MO_GSUITE_SAML_CSS_URL', MOV_GSUITE_URL . 'includes/css/style_settings_saml.css?version=' . MOV_GSUITE_VERSION );

define( 'MOV_GSUITE_INTTELINPUT_CSS', MOV_GSUITE_URL . 'includes/css/intlTelInput.css?version=' . MOV_GSUITE_VERSION );

define( 'MO_TOGGLESWITCH_CSS', MOV_GSUITE_URL . 'includes/css/toggleSwitch.css?version=' . MOV_GSUITE_VERSION );
define( 'MO_TOGGLESWITCH_JS', MOV_GSUITE_URL . 'includes/js/toggleSwitch.js?version=' . MOV_GSUITE_VERSION );

define( 'MOV_GSUITE_JS_URL', MOV_GSUITE_URL . 'includes/js/settings.min.js?version=' . MOV_GSUITE_VERSION );

define( 'MOV_GSUITE_INTTELINPUT_JS', MOV_GSUITE_URL . 'includes/js/intlTelInput.min.js?version=' . MOV_GSUITE_VERSION );

define( 'MOV_GSUITE_DROPDOWN_JS', MOV_GSUITE_URL . 'includes/js/dropdown.min.js?version=' . MOV_GSUITE_VERSION );
define( 'MOV_GSUITE_LOADER_URL', MOV_GSUITE_URL . 'includes/images/loader.gif' );
define( 'MOV_GSUITE_LOGO_URL', MOV_GSUITE_URL . 'includes/images/logo.png' );
define( 'MOV_GSUITE_USE_POLYLANG', true );
//define( 'MO_TEST_MODE', true );

define( 'MO_IS_FREE_PLUGIN', true );

//Include all required files for plugin to work.
includeFileGSuite( '/helper' );
includeFileGSuite( '/handler' );
require_once 'views/common-elements.php';

function includeFileGSuite( $folder ) {
	foreach ( scandir( dirname( __FILE__ ) . $folder ) as $filename ) {
		$path = dirname( __FILE__ ) . $folder . '/' . $filename;
		if ( is_file( $path ) && strpos( $filename, '.php' ) != false ) {
			require_once $path;
		} elseif ( is_dir( $path ) && $filename != "" && $filename != "." && $filename != ".." ) {
			includeFileGSuite( $folder . '/' . $filename );
		}
	}
}


function get_mo_gsuite_option( $string ) {
	return get_site_option( $string );
}

function update_mo_gsuite_option( $string, $value ) {
	update_site_option( $string, $value );
}

function delete_mo_gsuite_option( $string ) {
	delete_site_option( $string );
}

function mo_gsuite_( $string ) {
	return is_scalar( $string ) ? ( Mo_GSuite_Utility::_is_polylang_installed() && MOV_GSUITE_USE_POLYLANG ? pll__( $string )
		: __( $string, 'miniorange-otp-verification' ) ) : $string;
}


/*
 * mo_customer_validation_custom_popups
 * mo_customer_validation_custom_popups
 * mo_customer_email_transactions_remaining
 * mo_customer_phone_transactions_remaining
 * mo_gsuite_select_saml_oauth
 * */
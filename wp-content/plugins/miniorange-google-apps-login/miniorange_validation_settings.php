<?php

/**
 * Plugin Name: Google Apps Login (G Suite)
 * Plugin URI: https://www.miniorange.com
 * Description: Login with Google Apps/ G Suite account in WordPress. Supported using OAuth and SAML protocol.
 * Version: 6.0.0
 * Author: miniOrange
 * Author URI: https://www.miniorange.com
 * Text Domain: miniorange-otp-verification
 * Domain Path: /lang
 * License: GPL2
 */

include '_autoload.php';

class Miniorange_Gsuite_Customer_Validation {
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'otp_load_textdomain' ) );
		add_action( 'admin_menu', array( $this, 'Miniorange_Gsuite_Customer_Validation_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_registration_plugin_settings_style' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mo_registration_plugin_settings_script' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'mo_registration_plugin_frontend_scripts' ), 99 );
		add_action( 'login_enqueue_scripts', array( $this, 'mo_registration_plugin_frontend_scripts' ), 99 );
		add_action( 'mo_gsuite_registration_show_message', array( $this, 'mo_show_otp_message' ), 1, 2 );
		add_action( 'init', array( $this, 'moScheduleTransactionSync' ), 1 );
		add_action( 'hourlySync', array( $this, 'hourlySync' ) );
		add_action( 'admin_init', array( $this, 'register_ppl_strings' ), 1 );

		//add_shortcode('mo_oauth_login', array( $this,'mo_oauth_shortcode_login'));

		register_deactivation_hook( __FILE__, array( $this, 'mo_registration_deactivate' ) );

	}

	function Miniorange_Gsuite_Customer_Validation_menu() {

		$menu_slug = 'mogalsettings';
		add_menu_page( 'Google Apps Login', 'Google Apps Login', 'manage_options', $menu_slug,
			array(
				$this,
				'mo_customer_validation_options'
			), plugin_dir_url( __FILE__ ) . 'includes/images/miniorange_icon.png' );

		add_submenu_page( $menu_slug	,'Google Apps Login'	,'Account','administrator','galoginaccount'
			, array( $this, 'mo_customer_validation_options'));

		add_submenu_page( $menu_slug	,'Google Apps Login'	,'Licensing Plans','administrator','gsuitepricing'
			, array( $this, 'mo_customer_validation_options'));


		if ( strcasecmp( get_mo_gsuite_option( 'mo_gsuite_select_saml_oauth' ), 'true' ) == 0 ) {

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Oauth Configuration', 'administrator', 'configuration_oauth'
				, array( $this, 'mo_customer_validation_options' ) );


			add_submenu_page( $menu_slug, 'Google Apps Login', 'Customization', 'administrator', 'customization_oauth'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Sign In Settings', 'administrator', 'signinsettings_oauth'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Attribute/Role Mapping', 'administrator', 'mapping_oauth'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Reports', 'administrator', 'report_oauth'
				, array( $this, 'mo_customer_validation_options' ) );

		} else {
			add_submenu_page( $menu_slug, 'Google Apps Login', 'Identity Provider', 'administrator', 'identity_provider_saml'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Service Provider', 'administrator', 'service_provider_saml'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Sign in Settings', 'administrator', 'sign_in_setting_saml'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Attribute/Role Mapping', 'administrator', 'mapping_saml'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Import Export Configuration', 'administrator', 'saml_import_export_config'
				, array( $this, 'mo_customer_validation_options' ) );

			add_submenu_page( $menu_slug, 'Google Apps Login', 'Proxy Setup',  'administrator', 'proxy_setup'
				, array( $this, 'mo_customer_validation_options' ) );
		}

	}

	function mo_customer_validation_options() {
		include 'controllers/main-controller.php';
	}

	function mo_registration_plugin_settings_style() {
		wp_enqueue_style( 'mo_customer_validation_admin_settings_style', MOV_GSUITE_CSS_URL );
		wp_enqueue_style( 'mo_customer_validation_oauth_admin_style', MOV_OAUTH_CSS_URL );
		wp_enqueue_style( 'mo_customer_validation_inttelinput_style', MO_TOGGLESWITCH_CSS );/*
		wp_enqueue_style( 'mo_customer_validation_saml_admin_style', MO_GSUITE_SAML_CSS_URL );*/

	}

	function mo_registration_plugin_settings_script() {
		wp_enqueue_script( 'mo_customer_validation_admin_settings_script', MOV_GSUITE_JS_URL, array( 'jquery' ) );
		wp_enqueue_style( 'mo_saml_wpb-fa', MOV_GSUITE_FONT_AWESOME_URL );
		wp_enqueue_script( 'mo_customer_validation_toggle_script', MO_TOGGLESWITCH_JS, array( 'jquery' ) );

	}

	function mo_registration_plugin_frontend_scripts() {
		if ( ! get_mo_gsuite_option( 'mo_customer_validation_show_dropdown_on_form' ) ) {
			return;
		}
		$selector = apply_filters( 'mo_phone_dropdown_selector', array() );
		if ( Mo_GSuite_Utility::isBlank( $selector ) ) {
			return;
		}
		$selector = array_unique( $selector ); // get unique values 
		wp_enqueue_script( 'mo_customer_validation_inttelinput_script', MOV_GSUITE_INTTELINPUT_JS, array( 'jquery' ) );

		wp_enqueue_script( 'mo_customer_validation_toggle_script', MO_TOGGLESWITCH_SCRIPT, array( 'jquery' ) );

		wp_enqueue_style( 'mo_customer_validation_inttelinput_style', MO_TOGGLESWITCH_CSS );

		wp_enqueue_style( 'mo_customer_validation_inttelinput_style', MOV_GSUITE_INTTELINPUT_CSS );
		wp_register_script( 'mo_customer_validation_dropdown_script', MOV_GSUITE_DROPDOWN_JS, array( 'jquery' ), MOV_GSUITE_VERSION, true );



		wp_localize_script( 'mo_customer_validation_dropdown_script', 'modropdownvars', array(
			'selector' => json_encode( $selector ),
			'defaultCountry' => Mo_Gsuite_CountryList::getDefaultCountryIsoCode(),
			'onlyCountries' => Mo_Gsuite_CountryList::getOnlyMo_Gsuite_CountryList(),
		) );
		wp_enqueue_script( 'mo_customer_validation_dropdown_script' );
	}

	function mo_registration_deactivate() {
		wp_clear_scheduled_hook( 'hourlySync' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_transactionId' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_password' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_registration_status' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_transactionId' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_new_registration' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_customer_key' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_api_key' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_customer_token' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_validation_verify_customer' );
		delete_mo_gsuite_option( 'mo_customer_validation_message' );
		delete_mo_gsuite_option( 'mo_gsuite_customer_check_ln' );
	}

	function mo_show_otp_message( $content, $type ) {
		new Mo_Gsuite_Display_Messages( $content, $type );
	}

	function moScheduleTransactionSync() {

		if ( ! wp_next_scheduled( 'hourlySync' ) && Mo_GSuite_Utility::micr() ) {
			wp_schedule_event( time(), 'daily', 'hourlySync' );
		}
	}

	function otp_load_textdomain() {
		load_plugin_textdomain( 'miniorange-otp-verification', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	function register_ppl_strings() {
		if ( ! Mo_GSuite_Utility::_is_polylang_installed() ) {
			return;
		}
		foreach ( unserialize( MO_GSUITE_POLY_STRINGS ) as $key => $value ) {
			pll_register_string( $key, $value, 'miniorange-otp-verification' );
		}
	}

	function hourlySync() {

		$customerKey = get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_customer_key' );
		$apiKey      = get_mo_gsuite_option( 'mo_gsuite_customer_validation_admin_api_key' );
		if ( isset( $customerKey ) && isset( $apiKey ) ) {
			Mo_GSuite_Utility::_handle_mo_check_ln( false, $customerKey, $apiKey );
		}
	}

	function mo_oauth_shortcode_login(){
		/*$mowidget = new Mo_Oauth_Widget_new;
		return $mowidget->mo_oauth_login_form(true);*/
	}

}

new Miniorange_Gsuite_Customer_Validation;
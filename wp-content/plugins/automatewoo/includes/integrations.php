<?php

namespace AutomateWoo;

/**
 * @class Integrations
 */
class Integrations {

	/** @var Integration_Mailchimp */
	private static $mailchimp;

	/** @var Integration_ActiveCampaign */
	private static $activecampaign;

	/** @var Integration_Campaign_Monitor */
	private static $campaign_monitor;


	/**
	 * @return bool
	 */
	static function is_wpml() {
		return class_exists('SitePress');
	}


	/**
	 * @return bool
	 */
	static function is_woo_pos() {
		return class_exists('WC_POS');
	}


	/**
	 * @return bool
	 */
	static function subscriptions_enabled() {
		if ( ! class_exists( '\WC_Subscriptions' ) ) return false;
		if ( version_compare( \WC_Subscriptions::$version, '2.0', '<' ) ) return false;
		return true;
	}


	/**
	 * @return bool
	 */
	static function is_memberships_enabled() {
		if ( ! function_exists( 'wc_memberships' ) ) return false;
		if ( version_compare( wc_memberships()->get_version(), '1.7', '<' ) ) return false;
		return true;
	}


	/**
	 * @return bool
	 */
	static function is_mc4wp() {
		return defined( 'MC4WP_VERSION' );
	}



	static function load_twilio() {
		if ( ! function_exists( '\Services_Twilio_autoload' ) ) {
			require_once AW()->lib_path( '/twilio-php/Services/Twilio.php' );
		}
	}


	/**
	 * @return Integration_Mailchimp
	 */
	static function mailchimp() {
		if ( ! isset( self::$mailchimp ) ) {
			self::$mailchimp = new Integration_Mailchimp( trim( Clean::string( AW()->options()->mailchimp_api_key ) ) );
		}

		return self::$mailchimp;
	}


	/**
	 * @return Integration_ActiveCampaign
	 */
	static function activecampaign() {
		if ( ! isset( self::$activecampaign ) ) {
			$api_url = trim( Clean::string( AW()->options()->active_campaign_api_url ) );
			$api_key = trim( Clean::string( AW()->options()->active_campaign_api_key ) );
			self::$activecampaign = new Integration_ActiveCampaign( $api_url, $api_key );
		}

		return self::$activecampaign;
	}


	/**
	 * @return Integration_Campaign_Monitor
	 */
	static function campaign_monitor() {
		if ( ! isset( self::$campaign_monitor ) ) {
			$api_key = trim( Clean::string( AW()->options()->campaign_monitor_api_key ) );
			$client_id = trim( Clean::string( AW()->options()->campaign_monitor_client_id ) );
			self::$campaign_monitor = new Integration_Campaign_Monitor( $api_key, $client_id );
		}

		return self::$campaign_monitor;
	}


}



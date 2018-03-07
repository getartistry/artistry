<?php

namespace AutomateWoo;

/**
 * @class Options
 * @since 2.0.2
 *
 * @property string $version
 *
 * @property bool $session_tracking_enabled
 * @property bool $abandoned_cart_enabled
 * @property int $abandoned_cart_timeout
 * @property string $guest_email_capture_scope (checkout,all,none)
 * @property bool $clean_expired_coupons
 * @property bool $clear_inactive_carts_after
 * @property bool $abandoned_cart_includes_pending_orders
 *
 * @property bool $email_from_name
 * @property bool $email_from_address
 *
 * @property bool $twilio_integration_enabled
 * @property string $twilio_from
 * @property string $twilio_auth_id
 * @property string $twilio_auth_token
 *
 * @property bool $mailchimp_integration_enabled
 * @property bool $mailchimp_api_key
 *
 * @property bool $campaign_monitor_enabled
 * @property bool $campaign_monitor_api_key
 * @property bool $campaign_monitor_client_id
 *
 * @property bool $active_campaign_integration_enabled
 * @property string $active_campaign_api_url
 * @property string $active_campaign_api_key
 *
 * @property int $conversion_window
 *
 * @property bool $enable_background_system_check
 *
 */

class Options extends Options_API {

	/** @var string */
	public $prefix = 'automatewoo_';

	/** @var array */
	public $defaults = [

		'session_tracking_enabled' => 'yes',
		'abandoned_cart_enabled' => 'yes',
		'abandoned_cart_timeout' => 15,
		'clear_inactive_carts_after' => 60,
		'guest_email_capture_scope' => 'checkout',
		'clean_expired_coupons' => 'yes',
		'abandoned_cart_includes_pending_orders' => 'no',

		'twilio_integration_enabled' => 'no',
		'active_campaign_integration_enabled' => false,
		'campaign_monitor_enabled' => false,
		'mailchimp_integration_enabled' => false,
		'conversion_window' => 14,
		'enable_background_system_check' => true,

	];
}


<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Settings_Tab_Twilio
 */
class Settings_Tab_Twilio extends Admin_Settings_Tab_Abstract {

	function __construct() {
		$this->id = 'twilio';
		$this->name = __( 'Twilio', 'automatewoo' );
	}

	/**
	 * @return array
	 */
	function get_settings() {
		return [
			[
				'type' => 'title',
				'id' => 'automatewoo_twilio_integration'
			],
			[
				'title' => __( 'Enable', 'woocommerce' ),
				'id' => 'automatewoo_twilio_integration_enabled',
				'desc' => __( 'Enable Twilio Integration', 'automatewoo' ),
				'default' => 'no',
				'autoload' => true,
				'type' => 'checkbox',
			],
			[
				'title' => __( 'From', 'automatewoo' ),
				'desc_tip' => __( 'Must be a Twilio phone number (in E.164 format) or alphanumeric sender ID.', 'automatewoo' ),
				'id' => 'automatewoo_twilio_from',
				'type' => 'text',
				'autoload' => false,
			],
			[
				'title' => __( 'Account SID', 'automatewoo' ),
				'id' => 'automatewoo_twilio_auth_id',
				'type' => 'password',
				'autoload' => false,
			],
			[
				'title' => __( 'Auth Token', 'automatewoo' ),
				'id' => 'automatewoo_twilio_auth_token',
				'type' => 'password',
				'autoload' => false,
			],
			[
				'type' => 'sectionend',
				'id' => 'automatewoo_twilio_integration'
			]
		];
	}


	function output() {
		$this->output_settings_form();
		Admin::get_view( 'sms-test-twilio' );
		wp_enqueue_script( 'automatewoo-sms-test' );
	}
}

return new Settings_Tab_Twilio();

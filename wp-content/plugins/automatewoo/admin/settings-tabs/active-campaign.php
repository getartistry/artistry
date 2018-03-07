<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Settings_Tab_Active_Campaign
 */
class Settings_Tab_Active_Campaign extends Admin_Settings_Tab_Abstract {

	function __construct() {
		$this->id = 'active-campaign';
		$this->name = __( 'ActiveCampaign', 'automatewoo' );
	}


	/**
	 * @return array
	 */
	function get_settings() {
		return [
			[
				'type' => 'title',
				'id' => 'automatewoo_active_campaign_integration'
			],
			[
				'title' => __( 'Enable', 'woocommerce' ),
				'id' => 'automatewoo_active_campaign_integration_enabled',
				'desc' => __( 'Enable ActiveCampaign Integration', 'automatewoo' ),
				'default' => 'no',
				'autoload' => true,
				'type' => 'checkbox',
			],
			[
				'title' => __( 'API URL', 'automatewoo' ),
				'id' => 'automatewoo_active_campaign_api_url',
				'type' => 'text',
				'autoload' => false,
			],
			[
				'title' => __( 'API Key', 'automatewoo' ),
				'id' => 'automatewoo_active_campaign_api_key',
				'type' => 'password',
				'autoload' => false,
			],
			[
				'type' => 'sectionend',
				'id' => 'automatewoo_active_campaign_integration'
			],
		];
	}

	function save() {
		Integrations::activecampaign()->clear_cache_data();
		parent::save();
	}

}

return new Settings_Tab_Active_Campaign();

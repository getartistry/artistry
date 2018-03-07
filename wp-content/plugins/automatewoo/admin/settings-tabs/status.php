<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Settings_Tab_Status
 */
class Settings_Tab_Status extends Admin_Settings_Tab_Abstract {

	function __construct() {
		$this->id = 'status';
		$this->name = __( 'Status', 'automatewoo' );
	}


	function output() {
		Admin::get_view('system-check');
		$this->output_settings_form();
	}


	/**
	 * @return array
	 */
	function get_settings() {
		return [
			[
				'type' => 'title',
				'id' 	=> 'automatewoo_system_check_options'
			],
			[
				'title' => __( 'Enable Background Checks', 'woocommerce' ),
				'id' => 'automatewoo_enable_background_system_check',
				'desc' => __( 'Allow occasional background checks for major system issues. If an issue is detected an admin notice will appear.', 'automatewoo' ),
				'default' => 'yes',
				'autoload' => true,
				'type' => 'checkbox',
			],
			[
				'type' => 'sectionend',
				'id' 	=> 'automatewoo_system_check_options'
			],
		];
	}

}

return new Settings_Tab_Status();

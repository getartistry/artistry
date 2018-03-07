<?php

namespace AutomateWoo\Admin\Controllers;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Settings
 */
class Settings extends Base {

	/** @var array */
	public $settings = [];


	function handle() {
		$this->output_view( 'page-settings', [
			'current_tab' => $this->get_current_tab(),
			'tabs' => $this->get_settings_tabs()
		]);
	}


	function save() {

		// Save settings if data has been posted
		if ( empty( $_POST ) )
			return;

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'automatewoo-settings' ) ) {
			die( __( 'Action failed. Please refresh the page and retry.', 'automatewoo' ) );
		}

		$current_tab = $this->get_current_tab();
		$current_tab->save();
	}


	/**
	 * @return \AutomateWoo\Admin_Settings_Tab_Abstract|false
	 */
	function get_current_tab() {

		$current_tab_id = empty( $_GET['tab'] ) ? 'general' : sanitize_title( $_GET['tab'] );

		$tabs = $this->get_settings_tabs();

		return isset( $tabs[$current_tab_id] ) ? $tabs[$current_tab_id] : false;
	}



	/**
	 * @return array
	 */
	function get_settings_tabs() {
		if ( empty( $this->settings ) ) {
			$path = AW()->path( '/admin/settings-tabs/' );

			$settings_includes = apply_filters( 'automatewoo/settings/tabs', [
				$path . 'general.php',
				$path . 'carts.php',
				$path . 'mailchimp.php',
				$path . 'campaign-monitor.php',
				$path . 'active-campaign.php',
				$path . 'twilio.php'
			]);

			$settings_includes[] = $path . 'status.php';

			include_once $path . 'abstract.php';

			foreach ( $settings_includes as $settings_include ) {
				if ( $class = include_once $settings_include ) {
					$this->settings[$class->id] = $class;
				}
			}
		}

		return $this->settings;
	}

}

return new Settings();

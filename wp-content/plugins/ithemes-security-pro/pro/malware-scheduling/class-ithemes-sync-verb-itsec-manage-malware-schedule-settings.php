<?php

class Ithemes_Sync_Verb_ITSEC_Manage_Malware_Schedule_Settings extends Ithemes_Sync_Verb {
	public static $name = 'itsec-manage-malware-schedule-settings';
	public static $description = '';
	
	private $default_arguments = array();
	
	public function run( $arguments ) {
		$settings = get_site_option( 'itsec_malware_scheduling' );
		
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}
		if ( ! isset( $settings['enabled'] ) ) {
			$settings['enabled'] = false;
		}
		
		if ( isset( $arguments['enabled'] ) && ( $arguments['enabled'] != $settings['enabled'] ) ) {
			require_once( dirname( __FILE__ ) . '/class-itsec-malware-scheduling-admin.php' );
			
			ITSEC_Malware_Scheduling_Admin::update_schedule( $arguments['enabled'] );
		}
		
		$settings = array_merge( $settings, $arguments );
		
		return update_site_option( 'itsec_malware_scheduling', $settings );
	}
}

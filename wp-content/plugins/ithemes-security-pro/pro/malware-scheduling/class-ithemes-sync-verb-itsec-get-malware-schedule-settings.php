<?php

class Ithemes_Sync_Verb_ITSEC_Get_Malware_Schedule_Settings extends Ithemes_Sync_Verb {
	public static $name = 'itsec-get-malware-schedule-settings';
	public static $description = '';
	
	private $default_arguments = array();
	
	public function run( $arguments ) {
		return get_site_option( 'itsec_malware_scheduling' );
	}
}

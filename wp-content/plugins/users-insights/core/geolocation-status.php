<?php

class USIN_Geolocation_Status{
	
	const TR_KEY = 'usin_geolocation_paused';
	
	public static function pause(){
		set_transient(self::TR_KEY, true, 6*HOUR_IN_SECONDS);
	}
	
	public static function is_paused(){
		return get_transient(self::TR_KEY);
	}
	
	public static function resume(){
		delete_transient( self::TR_KEY );
	}
	
}
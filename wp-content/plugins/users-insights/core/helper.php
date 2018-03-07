<?php

class USIN_Helper{

	public static function get_roles(){
		$wp_roles = get_editable_roles();
		$roles = array();

		foreach ($wp_roles as $role_id => $role) {
			$roles[] = array('key'=>$role_id, 'val'=>$role['name']);
		}

		return $roles;
	}

	public static function get_months(){
		$months = array();

		for($i=1; $i<=12; $i++){
			$date = new DateTime();
			$date->setDate(2010, $i, 1);
			$months[]=date_i18n('M', self::get_unix_timestamp($date));
		}

		return $months;
	}

	public static function format_date($date){
		if(is_numeric($date)){
			//it's a timestamp - don't format it for now, since the Date filters don't support timestamps
			return $date;
		}else{
			return date_i18n( get_option( 'date_format' ), strtotime( $date ) );
		}
	}

	public static function format_date_time($date){
		return date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), strtotime( $date ) );
	}

	public static function format_date_human($date){
		$date1 = new DateTime($date);
		$date2 = new DateTime(current_time('mysql'));
		$minutes_diff = (self::get_unix_timestamp($date2) - self::get_unix_timestamp($date1))/60;

		if($minutes_diff<=5){
			return 'now';
		}else{

			$hours_diff = (self::get_unix_timestamp($date2) - self::get_unix_timestamp($date1))/(60*60);

			if($hours_diff<=24){
				return human_time_diff( self::get_unix_timestamp($date2) , self::get_unix_timestamp($date1)).' '.__('ago', 'usin');
			}else{
				//reset the time so that the days number is determined by the date only
				$date1->setTime(0,0,0);
				$date2->setTime(0,0,0);

				$day_diff = (self::get_unix_timestamp($date2) - self::get_unix_timestamp($date1))/(60*60*24);

				if($day_diff <= 30){
					return human_time_diff( self::get_unix_timestamp($date2) , self::get_unix_timestamp($date1)).' '.__('ago', 'usin');
				}
			}
			
			return self::format_date($date);
		}

	}

	public static function get_unix_timestamp($date){
		return intval($date->format('U'));
	}

	public static function is_plugin_activated($plugin){
		$activated = in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
		if(is_multisite() && !$activated){
			$active_plugins = get_site_option('active_sitewide_plugins') ;
			if(!empty($active_plugins) && isset($active_plugins[$plugin])){
				$activated = true;
			}
		}
		return $activated;
	}

	public static function array_to_sql_string($arr){
		$new_arr = array();
		foreach ($arr as $value) {
			$new_arr []= "'".$value."'";
		}
		return implode(',', $new_arr);
	}

	public static function get_allowed_post_statuses($return_type = 'array'){
		$allowed_statuses = array_keys(get_post_stati(array('exclude_from_search'=>false)));

		if($return_type == 'sql_string'){
			return self::array_to_sql_string($allowed_statuses);
		}

		//return an array with the allowed statuses
		return $allowed_statuses;
		
	}

	public static function get_allowed_post_types($return_type = 'array'){
		$allowed_post_types = array_keys(get_post_types());

		$exclude_post_types = self::get_exclude_post_types();

		$allowed_post_types = array_merge(array_diff($allowed_post_types, $exclude_post_types));
		
		if($return_type == 'sql_string'){
			return self::array_to_sql_string($allowed_post_types);
		}

		//return an array with the allowed statuses
		return $allowed_post_types;
	}
	
	public static function get_exclude_comment_types($return_type = 'array'){
		$exclude_comment_types = array();
		$exclude_comment_types = apply_filters('usin_exclude_comment_types', $exclude_comment_types);
		if($return_type == 'sql_string'){
			return self::array_to_sql_string($exclude_comment_types);
		}
		return $exclude_comment_types;
	}
	
	public static function get_exclude_post_types(){
		$exclude_post_types = array('revision', 'attachment', 'nav_menu_item');
		$exclude_post_types = apply_filters('usin_exclude_post_types', $exclude_post_types);
		return $exclude_post_types;
	}
	
	public static function coordinates_string_to_array($coordinates){
		if(!empty($coordinates)){
			$parts = explode(',', $coordinates);
			if(sizeof($parts)==2){
				return array(
					'lat' => (float)$parts[0],
					'lng' => (float)$parts[1]
				);
			}
		}
	}

}
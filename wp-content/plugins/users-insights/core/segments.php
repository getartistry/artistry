<?php

class USIN_Segments{
	
	public static $option_key = '_usin_segments';
	
	public static function get(){
		$segments = get_option(self::$option_key, array());
		return self::strip_slashes($segments);
	}
	
	public static function add($name, $filters){
		$segments = self::get();
		$id = self::get_next_id($segments);
		
		$segments[]= array(
			'id' => $id,
			'name' => $name,
			'user' => get_current_user_id(),
			'filters' => $filters
		);
		return self::save_segments($segments);
	}
	
	public static function delete($id){
		$segments = self::get();
		
		foreach ($segments as $index => $segment) {
			if($segment['id'] === $id){
				unset($segments[$index]);
				return self::save_segments(array_values($segments));
			}
		}
		return false;
	}
	
	protected static function get_next_id($segments){
		if(sizeof($segments) > 0){
			return max(wp_list_pluck($segments, 'id')) + 1;
		}
		
		return 1;
	}
	
	protected static function save_segments($segments){
		$res = update_option(self::$option_key, $segments);
		return $res ? self::strip_slashes($segments) : false;
	}
	
	protected static function strip_slashes($segments){
		foreach ($segments as &$segment) {
			$segment['name'] = stripslashes($segment['name']);
		}
		return $segments;
	}
	
}
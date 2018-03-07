<?php

class USIN_User_Data{

	protected $user_id;
	protected $table_name;
	protected $allowed_fields = array(
		'last_seen', 'sessions', 'country', 'region', 'city', 'coordinates',  
		'browser', 'browser_version', 'platform');

	function __construct($user_id){

		$this->user_id = intval($user_id);
		$this->table_name = self::get_table_name();
	}

	public static function get_table_name(){
		global $usin, $wpdb;

		return $wpdb->prefix.$usin->manager->user_data_db_table;
	}

	public function save($field_name, $value){
		$data = array($field_name => $value);
		return $this->save_array($data);
	}

	public function save_array($values){
		global $wpdb;

		if(empty($this->user_id)){
			return false;
		}

		$data = $this->sanitize_data($values);

		if(empty($data)){
			return false;
		}

		$res = false;

		if($this->saved_data_exists()){
			//update the existing data
			$res = $wpdb->update( $this->table_name, $data, array('user_id' => $this->user_id) );
		}else{
			//insert a new row
			$data['user_id'] = $this->user_id;
			$res = $wpdb->insert($this->table_name, $data);
		}

		return (bool)$res;
	}

	protected function sanitize_data($data){
		$sanitized = array();
		foreach ($data as $key => $value) {
			if($this->is_field_allowed($key)){
				$sanitized[$key] = $value;
			}
		}

		return $sanitized;
	}

	protected function saved_data_exists(){
		global $wpdb;

		$row = $wpdb->get_row( $wpdb->prepare (
			"SELECT * FROM $this->table_name WHERE user_id = %d", $this->user_id ));

		return !empty($row);
	}

	public function get($field_name){
		if($this->is_field_allowed($field_name)){
			global $wpdb;

			$res = $wpdb->get_var(
				$wpdb->prepare("SELECT $field_name FROM $this->table_name WHERE user_id = %d", $this->user_id)
			);

			return $res;
		}
	}

	protected function is_field_allowed($field_name){
		return in_array($field_name, $this->allowed_fields);
	}

	/**
	 * WARNING : The WHERE clause must be prepared with $wpdb->prepare
	 */
	public static function get_users($where = ''){
		global $wpdb;

		$table_name = self::get_table_name();
		$query = "SELECT * FROM $table_name";
		if(!empty($where)){
			$query.=' WHERE '.$where;
		}

		return $wpdb->get_results($query);		
	}

}
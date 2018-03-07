<?php

class USIN_Field{
	
	protected $field_options;
	
	public function __construct($field_options){
		$this->field_options = $field_options;
	}
	
	public function update_value_for_user($user_id, $field_value){
		if(!isset($this->field_options['editable'])){
			return false;
		}
		
		$key = isset($this->field_options['editable']['id']) ? $this->field_options['editable']['id'] : $this->field_options['id'];
	
		if($this->field_options['editable']['location'] == 'meta'){
			//update a user meta field
			
			$current_value = get_user_meta($user_id, $key, true);
			
			if($field_value !== ''){
				//update user meta
				if($current_value == $field_value){
					return true; //the value was not changed, return success
				}
				return update_user_meta($user_id, $key, $field_value);
			}else{
				if(empty($current_value)){
					return true; //the field doesn't exist, no need to delete it, return success
				}
				//delete user meta
				return delete_user_meta($user_id, $key);
			}
		}
		
		return false;
		
	}
	
}
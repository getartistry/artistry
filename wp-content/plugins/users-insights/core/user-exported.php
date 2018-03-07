<?php

/**
 * Extends the main User class to change the way some of the data is loaded 
 * for an export format.
 */
class USIN_User_Exported extends USIN_User{
	
	protected $should_ignore_date_format = true;
	
	/**
	 * The user groups should be automatically loaded from the database query,
	 * with the names separated by commas
	 */
	protected function set_user_groups(){
		return;
	}
	
	/**
	 * Avatar is not needed in the export file.
	 */
	protected function set_avatar(){
		return;
	}
	
	/**
	 * If the comments are not set to the user by default, it means that the
	 * comments column is not needed in the export and we don't need to load
	 * the comments number for each user.
	 */
	protected function set_comments(){
		return;
	}
	
	protected function set_role(){
		if($this->is_field_exported('role')){
			parent::set_role();
		}
	}
	
	protected function is_field_exported($field){
		return isset($this->options['export_fields'])
			&& in_array($field, $this->options['export_fields']);
	}
}
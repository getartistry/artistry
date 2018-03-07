<?php

/**
 * Contains the general plugin action hooks.
 */
class USIN_Actions{

	public function init(){
		add_action('deleted_user', array($this, 'delete_user_data'));
	}

	/**
	 * Deletes the saved by Users Insights user data (such as geolocation and browser info),
	 * after a user has been deleted
	 * @param $user_id the ID of the deleted user
	 */
	public function delete_user_data($user_id){
		global $wpdb;
		$manager = usin_manager();
		$table_name = $wpdb->prefix.$manager->user_data_db_table;
		$wpdb->delete( $table_name, array( 'user_id' => $user_id ) );
	}
	
}


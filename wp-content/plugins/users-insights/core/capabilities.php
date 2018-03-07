<?php

if(!defined( 'ABSPATH')){
	exit;
}

class USIN_Capabilities{
	
	const LIST_USERS = 'users_insights_list_users';
	const UPDATE_USERS = 'users_insights_update_user_profile';
	const MANAGE_CUSTOM_FIELDS = 'users_insights_manage_custom_fields';
	const MANAGE_GROUPS = 'users_insights_manage_groups';
	const MANAGE_OPTIONS = 'users_insights_manage_options';
	const MANAGE_SEGMENTS = 'users_insights_manage_segments';
	const EXPORT_USERS = 'users_insights_export_users';
	
	protected $all_caps;
	
	public function __construct(){
		
		$this->all_caps = array(self::LIST_USERS, self::UPDATE_USERS, self::MANAGE_GROUPS,
			self::MANAGE_CUSTOM_FIELDS, self::MANAGE_OPTIONS, self::MANAGE_SEGMENTS, self::EXPORT_USERS);
		
		if(is_admin()){
			add_action( 'plugins_loaded', array($this, 'set_capabilities_to_admin') );
		}
	}
	
	public function set_capabilities_to_admin($only = null){
		$role = get_role('administrator');
		
		foreach ($this->all_caps as $cap ) {
			if(!$role->has_cap($cap)){
				$role->add_cap($cap);
			}
		}
	}
	
}

new USIN_Capabilities();
<?php

class USIN_BuddyPress_Query{
	
	protected $prefix;
	protected $xprofile;
	protected $xp_table_prefix = 'xprofile_';
	
	public function __construct($xprofile){
		$this->xprofile = $xprofile;
	}

	public function init(){
		global $wpdb;
		$this->prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_custom_select', array($this, 'filter_query_select'), 10, 2);
		add_filter('usin_db_aggregate_columns', array($this, 'filter_aggregate_columns'));
	}

	protected function is_bp_feature_active($feature){
		return USIN_BuddyPress::is_bp_feature_active($feature);
	}

	public function filter_db_map($db_map){
		global $wpdb;

		if($this->is_bp_feature_active('groups')){
			$db_map['groups'] = array('db_ref'=>'groups', 'db_table'=>'gm', 'null_to_zero'=>true, 'set_alias'=>true);
			$db_map['groups_created'] = array('db_ref'=>'groups_created', 'db_table'=>'gr', 'null_to_zero'=>true, 'set_alias'=>true);
			$db_map['bp_group'] = array('db_ref'=>'group_id', 'db_table'=>'bpg', 'set_alias'=>true);
		}
			
		if($this->is_bp_feature_active('friends')){
			$db_map['friends'] = array('db_ref'=>'meta_value', 'db_table'=>'friends_meta', 'null_to_zero'=>true, 'cast'=>'DECIMAL', 'custom_select'=>true);
		}

		if($this->is_bp_feature_active('activity')){
			$db_map['activity_updates'] = array('db_ref'=>'activity_updates', 'db_table'=>'au', 'null_to_zero'=>true, 'set_alias'=>true);
		}
		
		//xprofile fields
		$fields = $this->xprofile->get_fields();
		foreach ($fields as $field ) {
			$map = array('db_ref'=>'value', 'db_table'=>$this->xp_table_prefix.$field['bpx_id'], 'nulls_last'=>true);
			if($field['filter']['type']=='number'){
				$map['cast'] = 'DECIMAL';
			}
			if($field['filter']['type']=='date'){
				$map['cast'] = 'DATETIME';
			}
			
			$db_map[$field['id']] = $map;
		}
		
		return $db_map;
	}

	public function filter_query_select($query_select, $field){
		if($field == 'friends'){
			if($this->is_bp_feature_active('friends')){
				$query_select.="IFNULL(cast(friends_meta.meta_value AS DECIMAL),0)";
			}
		}
		return $query_select;
	}
	
	public function filter_aggregate_columns($columns){
		$columns[]='bp_group';
		return $columns;
	}

	public function filter_query_joins($query_joins, $table){
		global $wpdb;

		if(strpos($table, $this->xp_table_prefix) === 0){
			//xprofile field
			$field_id = (int)str_replace($this->xp_table_prefix, '', $table);
			$query_joins .= " LEFT JOIN ".$this->prefix."bp_xprofile_data AS $table ON".
				" $wpdb->users.ID = $table.user_id AND $table.field_id = $field_id";
		}else{
			switch ($table) {
				case 'gm':
					$query_joins .= " LEFT JOIN (SELECT user_id, COUNT(".$this->prefix."bp_groups_members.id) as groups FROM ".$this->prefix."bp_groups_members GROUP BY user_id) gm on $wpdb->users.ID = gm.user_id";
					break;
				case 'gr':
					$query_joins .= " LEFT JOIN (SELECT creator_id, COUNT(".$this->prefix."bp_groups.id) as groups_created FROM ".$this->prefix."bp_groups GROUP BY creator_id) gr on $wpdb->users.ID = gr.creator_id";
					break;
				case 'friends_meta':
					$query_joins .= " LEFT JOIN $wpdb->usermeta AS friends_meta ON ".
						"($wpdb->users.ID = friends_meta.user_id AND friends_meta.meta_key = 'total_friend_count')";
					break;
				case 'au':
					$query_joins .= " LEFT JOIN (SELECT user_id, COUNT(".$this->prefix."bp_activity.id) as activity_updates FROM ".$this->prefix."bp_activity WHERE type='activity_update' GROUP BY user_id) au on $wpdb->users.ID = au.user_id";
					break;
				case 'bpg':
					$query_joins .= " LEFT JOIN ".$this->prefix."bp_groups_members AS bpg ON $wpdb->users.ID = bpg.user_id";
					break;
				
			}
		}
			return $query_joins;
	}

}
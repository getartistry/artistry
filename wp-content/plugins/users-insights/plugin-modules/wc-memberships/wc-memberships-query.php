<?php

class USIN_WC_Memberships_Query{
	
	protected $post_type;
	protected $memberships_join_applied = false;
	
	public function __construct($post_type){
		$this->post_type = $post_type;
		$this->init();
	}
	
	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_custom_query_filter', array($this, 'apply_filters'), 10, 2);
		add_filter('usin_custom_select', array($this, 'filter_query_select'), 10, 2);
		add_filter('usin_db_aggregate_columns', array($this, 'filter_aggregate_columns'));
		add_filter('usin_user_db_data', array($this, 'set_status_names'));
	}
	
	public function filter_db_map($db_map){
		global $wpdb;
		$db_map['membership_num'] = array('db_ref'=>'membership_num', 'db_table'=>'memberships', 'null_to_zero'=>true, 'set_alias'=>false, 'custom_select'=>true, 'no_ref'=>true);
		$db_map['member_since'] = array('db_ref'=>'member_since', 'db_table'=>'memberships_since', 'set_alias'=>true, 'nulls_last'=>true);
		$db_map['membership_statuses'] = array('db_ref'=>'membership_statuses', 'db_table'=>'mem_statuses', 'set_alias'=>true, 'nulls_last'=>true);
		$db_map['has_membership_plan'] = array('db_ref'=>'', 'db_table'=>'memberships', 'no_select'=>true);
		return $db_map;
	}

	public function filter_query_select($query_select, $field){
		if($field == 'membership_num'){
			$query_select="COUNT(DISTINCT memberships.ID) AS membership_num";
		}
		return $query_select;
	}
	
	public function filter_aggregate_columns($columns){
		$columns[]='membership_num';
		return $columns;
	}
	
	public function filter_query_joins($query_joins, $table){
		global $wpdb;
		
		if(!in_array($table, array('memberships', 'memberships_since', 'mem_statuses'))){
			return $query_joins;
		}

		if($table =='memberships'){
			$query_joins .= $this->get_memberships_join();
				 
		}elseif($table == 'memberships_since'){
			
			$query_joins .= " LEFT JOIN (
					SELECT MIN(CAST(meta_value AS DATETIME)) AS member_since, $wpdb->posts.post_author AS user_id FROM $wpdb->postmeta
					INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID
					WHERE $wpdb->postmeta.meta_key = '_start_date' AND $wpdb->posts.post_type = '$this->post_type' AND $wpdb->posts.post_status IN (".$this->get_status_string().")
					GROUP BY $wpdb->posts.post_author
				) AS memberships_since ON $wpdb->users.ID = memberships_since.user_id";
				
		}elseif($table == 'mem_statuses'){
			$query_joins .= " LEFT JOIN (
				SELECT GROUP_CONCAT(post_status SEPARATOR ',') AS membership_statuses, post_author AS user_id
				FROM $wpdb->posts
				WHERE post_type='$this->post_type' AND post_status IN (".$this->get_status_string().")
				GROUP BY $wpdb->posts.post_author
				) AS mem_statuses ON  $wpdb->users.ID = mem_statuses.user_id";
		}

		return $query_joins;
	}
	
	
	protected function get_memberships_join(){
		if($this->memberships_join_applied === true){
			return '';
		}
		
		$this->memberships_join_applied = true;
		global $wpdb;
		return " LEFT JOIN $wpdb->posts AS memberships 
			ON $wpdb->users.ID = memberships.post_author AND memberships.post_type='$this->post_type'
			AND memberships.post_status IN (".$this->get_status_string().")";
	}
	
	protected function get_status_string(){
		return USIN_Helper::array_to_sql_string($this->get_membership_statuses_keys());
	}



	public function apply_filters($custom_query_data, $filter){
		global $wpdb;
		
		if($filter->by == 'membership_statuses'){
			$operator = $filter->operator == 'include' ? '>' : '=';

			$custom_query_data['joins'] = $this->get_memberships_join();
			$custom_query_data['having'] = $wpdb->prepare(" AND SUM(memberships.post_status IN (%s)) $operator 0", $filter->condition);
		
		}elseif($filter->by == 'has_membership_plan'){
			$operator = $filter->operator == 'include' ? '>' : '=';
			
			$custom_query_data['having'] = $wpdb->prepare(" AND SUM(memberships.post_parent IN (%d)) $operator 0", $filter->condition);
		}

		return $custom_query_data;
	}
	
	public function set_status_names($user_data){
		$statuses = USIN_WC_Memberships::get_statuses();
		
		if(property_exists($user_data, 'membership_statuses') && !empty($user_data->membership_statuses)){
			$user_statuses = explode(',', $user_data->membership_statuses);
			foreach ($user_statuses as $key => $status) {
				if(isset($statuses[$status])){
					$user_statuses[$key] = $statuses[$status]['label'];
				}
			}
			$user_data->membership_statuses = implode($user_statuses, ', ');
		}
		
		return $user_data;
	}
	
	protected function get_membership_statuses_keys(){
		$statuses = USIN_WC_Memberships::get_statuses();
		return array_keys($statuses);
	}
	
}
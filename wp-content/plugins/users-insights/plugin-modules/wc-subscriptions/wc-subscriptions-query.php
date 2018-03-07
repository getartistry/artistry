<?php

class USIN_WC_Subscriptions_Query{
	
	protected $post_type;
	protected $has_subscription_status_join_applied = false;
	
	public function __construct($post_type){
		$this->post_type = $post_type;
		$this->init();
	}
	
	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_custom_query_filter', array($this, 'apply_filters'), 10, 2);
		add_filter('usin_user_db_data', array($this, 'set_status_names'));
	}
	
	public function filter_db_map($db_map){
		$db_map['subscription_num'] = array('db_ref'=>'subscription_num', 'db_table'=>'subscriptions', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['subscripton_statuses'] = array('db_ref'=>'statuses', 'db_table'=>'subscriptions', 'nulls_last'=>true);
		$db_map['subscripton_next_payment'] = array('db_ref'=>'next_payment', 'db_table'=>'subscripton_payments', 'set_alias'=>true, 'nulls_last'=>true);
		return $db_map;
	}

	public function filter_query_joins($query_joins, $table){
		global $wpdb;

		if($table === 'subscriptions'){
			$query_joins .= " LEFT JOIN (SELECT count(ID) as subscription_num,  GROUP_CONCAT(post_status SEPARATOR ',') AS statuses, $wpdb->postmeta.meta_value as user_id FROM $wpdb->posts".
				" INNER JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id".
				" WHERE $wpdb->postmeta.meta_key = '_customer_user' AND $wpdb->posts.post_type = '$this->post_type'";
				
			$allowed_statuses = USIN_Helper::array_to_sql_string($this->get_subscription_statuses_keys());
			
			if(!empty($allowed_statuses)){
				$query_joins .= " AND $wpdb->posts.post_status IN ($allowed_statuses)";
			}
			$query_joins .=" GROUP BY user_id) as subscriptions ON $wpdb->users.ID = subscriptions.user_id";
		}elseif($table === 'subscripton_payments'){
			$exclude_statuses = array('wc-on-hold', 'wc-cancelled', 'wc-pending-cancel', 'wc-expired');
			$allowed_statuses = USIN_Helper::array_to_sql_string($this->get_subscription_statuses_keys($exclude_statuses));
			
			$query_joins .= " LEFT JOIN (
					SELECT MIN(CAST(next_payments.meta_value AS DATETIME)) AS next_payment, user_ids.meta_value as user_id FROM $wpdb->posts
					INNER JOIN $wpdb->postmeta AS user_ids on $wpdb->posts.ID = user_ids.post_id AND user_ids.meta_key = '_customer_user'
					INNER JOIN $wpdb->postmeta AS next_payments on $wpdb->posts.ID = next_payments.post_id AND next_payments.meta_key = '_schedule_next_payment'
					WHERE $wpdb->posts.post_type = '$this->post_type' AND $wpdb->posts.post_status IN (".$allowed_statuses.") AND next_payments.meta_value  >= CURDATE()
					GROUP BY user_ids.meta_value
				) AS subscripton_payments ON $wpdb->users.ID = subscripton_payments.user_id";
		}

		return $query_joins;
	}



	public function apply_filters($custom_query_data, $filter){

		if(in_array($filter->operator, array('include', 'exclude'))){
			global $wpdb;
			
			$operator = $filter->operator == 'include' ? '>' : '=';
		
			if($filter->by == 'subscripton_statuses'){
		
				if(!$this->has_subscription_status_join_applied){
					//apply the joins only once, even when this type of filter is applied multiple times
					$custom_query_data['joins'] .=
						" INNER JOIN $wpdb->postmeta AS wcs_meta ON $wpdb->users.ID = wcs_meta.meta_value".
						" INNER JOIN $wpdb->posts AS wcs_posts ON wcs_meta.post_id = wcs_posts.ID AND wcs_posts.post_type = '$this->post_type'";
		
					$this->has_subscription_status_join_applied = true;
				}
		
		
				$custom_query_data['where'] = " AND wcs_meta.meta_key = '_customer_user'";
				if($filter->operator == 'exclude'){
					$custom_query_data['where'].=" AND subscription_num > 0";
				}
		
				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(wcs_posts.post_status IN (%s)) $operator 0", $filter->condition);
			
			}
		}
		
		return $custom_query_data;
	}
	
	
	public function set_status_names($user_data){
		$statuses = USIN_WC_Subscriptions::get_statuses();
		
		if(property_exists($user_data, 'subscripton_statuses') && !empty($user_data->subscripton_statuses)){
			$user_statuses = explode(',', $user_data->subscripton_statuses);
			foreach ($user_statuses as $key => $status) {
				if(isset($statuses[$status])){
					$user_statuses[$key] = $statuses[$status];
				}
			}
			$user_data->subscripton_statuses = implode($user_statuses, ', ');
		}
		
		return $user_data;
	}
	
	protected function get_subscription_statuses_keys($except = array()){
		$statuses = USIN_WC_Subscriptions::get_statuses();
		$status_keys = array_keys($statuses);
		if(!empty($except)){
			$status_keys = array_diff($status_keys, $except);
		}
		return array_values($status_keys);
	}
	
}
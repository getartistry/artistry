<?php

class USIN_Woocommerce_Query{

	protected $order_post_type;
	protected $has_ordered_join_applied = false;
	protected $has_order_status_join_applied = false;
	protected $coupon_join_applied = false;

	public function __construct($order_post_type){
		$this->order_post_type = $order_post_type;
	}

	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_custom_query_filter', array($this, 'apply_filters'), 10, 2);
		add_filter('usin_custom_select', array($this, 'filter_query_select'), 10, 2);
		add_filter('usin_query_fields_without', array($this, 'filter_fields_without'));
		add_filter('usin_users_raw_data', array($this, 'filter_raw_db_data'));
	}

	public function filter_db_map($db_map){
		$db_map['order_num'] = array('db_ref'=>'order_num', 'db_table'=>'orders', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['has_ordered'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['has_order_status'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['has_used_coupon'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['last_order'] = array('db_ref'=>'last_order', 'db_table'=>'orders', 'nulls_last'=>true, 'cast'=>'DATETIME');
		$db_map['lifetime_value'] = array('db_ref'=>'value', 'db_table'=>'lifetime_values', 'null_to_zero'=>true, 'custom_select'=>true, 'cast'=>'DECIMAL', 'set_alias'=>true);
		$db_map['reviews'] = array('db_ref'=>'reviews_num', 'db_table'=>'reviews', 'null_to_zero'=>true, 'set_alias'=>true);
		return $db_map;
	}
	
	public function filter_query_select($query_select, $field){
		if($field == 'lifetime_value'){
			$query_select='CAST(IFNULL(lifetime_values.value, 0) AS DECIMAL(10,2))';
		}
		return $query_select;
	}

	public function filter_query_joins($query_joins, $table){
		global $wpdb;

		if($table === 'orders'){
			$query_joins .= " LEFT JOIN (".$this->get_orders_select().") AS orders ON $wpdb->users.ID = orders.user_id";
		}elseif ($table === 'lifetime_values') {
			$query_joins .= " LEFT JOIN (".$this->get_lifetime_values_select().") AS lifetime_values ON $wpdb->users.ID = lifetime_values.user_id";
		}elseif ($table === 'reviews') {
			$query_joins.= " LEFT JOIN (SELECT count(comment_ID) as reviews_num, user_id FROM $wpdb->comments ".
			"INNER JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID AND $wpdb->posts.post_type = 'product' ".
			"GROUP BY user_id) AS reviews ON $wpdb->users.ID = reviews.user_id";
		}

		return $query_joins;
	}
	
	public function filter_fields_without($fields_without){
		return array_merge($fields_without, array('order_num', 'last_order', 'lifetime_value'));
	}
	
	public function filter_raw_db_data($data){
		global $wpdb;
		$orders = array();
		
		if(!empty($data)){
			
			//number of orders
			$user_ids = wp_list_pluck($data, 'ID');
			if($this->should_load_field_data('order_num', $data)){
				$orders = $this->get_orders($user_ids);
				foreach ($data as &$user_data) {
					$user_id = intval($user_data->ID);
					$user_data->order_num = isset($orders[$user_id]) ? $orders[$user_id]->order_num : 0;
				}
			}
			
			//last order date
			if($this->should_load_field_data('last_order', $data)){
				$orders = $this->get_orders($user_ids);
				foreach ($data as &$user_data) {
					$user_id = intval($user_data->ID);
					$user_data->last_order = isset($orders[$user_id]) ? $orders[$user_id]->last_order : null;
				}
			}
			
			//lifeime value
			if($this->should_load_field_data('lifetime_value', $data)){
				$lt_values = $this->get_lifetime_values($user_ids);
				foreach ($data as &$user_data) {
					$user_id = intval($user_data->ID);
					$val = isset($lt_values[$user_id]) ? $lt_values[$user_id]->value : 0;
					$user_data->lifetime_value = number_format($val, 2);
				}
			}
		}
		
		
		return $data;
	}
	
	protected function should_load_field_data($field_id, $current_data){
		if(!empty($current_data) && !isset($current_data[0]->$field_id) &&
			usin_options()->is_field_visible($field_id)){
				return true;
			}
		return false;
	}
	
	protected function get_orders($user_ids){
		if(!isset($this->orders)){
			global $wpdb;
			$query = $this->get_orders_select($user_ids);
			$orders = $wpdb->get_results( $query );
			$this->orders = $this->set_user_id_as_array_index($orders);
		}
		return $this->orders;
	}
	
	protected function get_lifetime_values($user_ids){
		global $wpdb;
		$query = $this->get_lifetime_values_select($user_ids);
		$lt_values = $wpdb->get_results( $query );
		return $this->set_user_id_as_array_index($lt_values);
	}
	
	protected function get_orders_select($for_users = null){
		global $wpdb;
		
		$query = "SELECT count(ID) as order_num, MAX(post_date) as last_order, $wpdb->postmeta.meta_value as user_id FROM $wpdb->posts".
			" INNER JOIN $wpdb->postmeta on $wpdb->posts.ID = $wpdb->postmeta.post_id".
			" WHERE $wpdb->postmeta.meta_key = '_customer_user' AND $wpdb->posts.post_type = '$this->order_post_type'";
			
		$allowed_statuses = USIN_Helper::get_allowed_post_statuses('sql_string');
		if(!empty($allowed_statuses)){
			$query .= " AND $wpdb->posts.post_status IN ($allowed_statuses)";
		}
		if(!empty($for_users)){
			$query .= " AND $wpdb->postmeta.meta_value IN (".implode(",", $for_users).")";
		}
		
		$query .=" GROUP BY user_id";
		return $query;
	}
	
	protected function set_user_id_as_array_index($arr){
		$new_arr = array();
		foreach ($arr as $val) {
			$user_id = intval($val->user_id);
			$new_arr[$user_id] = $val;
		}
		return $new_arr;
	}
	
	protected function get_lifetime_values_select($for_users = null){
		global $wpdb;
		
		$query = "SELECT SUM(meta2.meta_value) AS value, meta.meta_value AS user_id ".
			"FROM $wpdb->posts as posts ".
			"LEFT JOIN $wpdb->postmeta AS meta ON posts.ID = meta.post_id ".
			"LEFT JOIN $wpdb->postmeta AS meta2 ON posts.ID = meta2.post_id ".
			"WHERE   meta.meta_key       = '_customer_user' ".
			"AND     posts.post_type     = '$this->order_post_type' ".
			"AND     posts.post_status   IN ( 'wc-completed', 'wc-processing' ) ".
			"AND     meta2.meta_key      = '_order_total' ";
		
		if(!empty($for_users)){
			$query .= " AND meta.meta_value IN (".implode(",", $for_users).")";
		}
				
		$query .= " GROUP BY meta.meta_value";
		
		return $query;
	}



	public function apply_filters($custom_query_data, $filter){
		global $wpdb;
		
		if(in_array($filter->operator, array('include', 'exclude'))){
			global $wpdb;
			
			$operator = $filter->operator == 'include' ? '>' : '=';

			if($filter->by == 'has_ordered'){
				
				if(!$this->has_ordered_join_applied){
					//apply the joins only once, even when this type of filter is applied multiple times
					$custom_query_data['joins'] .= 
						" INNER JOIN $wpdb->postmeta AS wpm ON $wpdb->users.ID = wpm.meta_value".
						" INNER JOIN $wpdb->posts AS woop ON wpm.post_id = woop.ID".
						" INNER JOIN ".$wpdb->prefix."woocommerce_order_items AS woi ON woop.ID =  woi.order_id".
						" INNER JOIN ".$wpdb->prefix."woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id";

					$this->has_ordered_join_applied = true;
				}
				

				$custom_query_data['where'] = " AND wpm.meta_key = '_customer_user' AND woim.meta_key = '_product_id'";

				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(woim.meta_value IN (%d)) $operator 0", $filter->condition);


			}elseif($filter->by == 'has_order_status'){

				if(!$this->has_order_status_join_applied){
					//apply the joins only once, even when this type of filter is applied multiple times
					$custom_query_data['joins'] .=
						" INNER JOIN $wpdb->postmeta AS wsm ON $wpdb->users.ID = wsm.meta_value".
						" INNER JOIN $wpdb->posts AS wsp ON wsm.post_id = wsp.ID";

					$this->has_order_status_join_applied = true;
				}


				$custom_query_data['where'] = " AND wsm.meta_key = '_customer_user'";

				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(wsp.post_status IN (%s)) $operator 0", $filter->condition);
			
			}
		}elseif($filter->by == 'has_used_coupon'){
			if(!$this->coupon_join_applied){
				$custom_query_data['joins'] .= 
					" INNER JOIN $wpdb->postmeta AS wccm ON $wpdb->users.ID = wccm.meta_value".
					" INNER JOIN $wpdb->posts AS wccp ON wccm.post_id = wccp.ID".
					" INNER JOIN ".$wpdb->prefix."woocommerce_order_items AS wc_coupons ON wccp.ID =  wc_coupons.order_id";
				$this->coupon_join_applied = true;
			}
			
			$custom_query_data['where'] = " AND wccm.meta_key = '_customer_user' AND wc_coupons.order_item_type = 'coupon'";
			$custom_query_data['having'] = $wpdb->prepare(" AND SUM(wc_coupons.order_item_name = %s) > 0", $filter->condition);
			
		}

		return $custom_query_data;
	}
	
	
	/**
	 * Resets the query options - this should be called when more than one
	 * query is performed per http request
	 */
	public function reset(){
		unset($this->orders);
		$this->has_ordered_join_applied = false;
		$this->has_order_status_join_applied = false;
		$this->coupon_join_applied = false;
	}

}
<?php

class USIN_User_Query extends USIN_Query{


	public function get_users($default_fields = null){
		global $wpdb;

		$this->build_query($default_fields);
		$results =  $wpdb->get_results( $this->query );

		$total = $wpdb->get_var( 'SELECT FOUND_ROWS()' );
		$results = apply_filters('usin_users_raw_data', $results);
		
		$users = $this->db_rows_to_objects($results);

		$all_users = count_users();
		return array('users'=>$users, 'total'=> $total, 'alltotal'=>$all_users['total_users']);

	}

	public function get_user($user_id){
		global $wpdb;

		$filter = new stdClass();
		$filter->by = 'ID';
		$filter->operator = 'equals';
		$filter->condition = $user_id;
		$filter->type = 'number';

		$this->filters = array($filter);
		
		$general_fields = usin_options()->get_field_ids_by_field_type('general');
		$personal_fields = usin_options()->get_field_ids_by_field_type('personal');
		$additional_fields = array('coordinates');
		$all_fields = array_merge($general_fields, $personal_fields, $additional_fields);
		$all_fields = apply_filters('usin_single_user_query_fields', $all_fields);

		$this->build_query($all_fields);

		$db_user = $wpdb->get_row ( $this->query );
		$db_user = apply_filters('usin_single_user_db_data', $db_user);

		if(!empty($db_user)){
			return new USIN_User($db_user);
		}
	}


	public function build_query($default_fields = null){
		global $wpdb;

		$args = $this->args;
		$filters = $this->filters;

		$this->set_query_select($default_fields);

		$this->set_filters();

		$this->set_query_order();

		$this->query .= $this->query_select;
		$this->query .= $this->get_query_joins();

		$this->set_conditions();
		
		
		$this->query .= $this->query_order;

		//set a limit
		if ( isset( $args['number'] ) && $args['number'] ) {
			if ( isset($args['offset'])){
				$this->query .= $wpdb->prepare(" LIMIT %d, %d", $args['offset'], $args['number']);
			}else{
				$this->query .= $wpdb->prepare(" LIMIT %d", $args['number']);
			}
		}
	}

	


}
<?php 

/**
 * Includes the DB query functionality for the EDD module.
 */
class USIN_EDD_Query{
	
	protected $has_ordered_join_applied = false;
	protected $payments_join_set = false;
	protected $order_post_type;
	protected $joins = '';
	protected $customers_join_set = false;
	protected $require_customer_join = false;

	/**
	 * @param string $order_post_type the order post type
	 */
	public function __construct($order_post_type){
		$this->order_post_type = $order_post_type;
	}
	
	/**
	 * Inits the main functionality - registers filter hooks.
	 */
	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_query_joins', array($this, 'filter_global_query_joins'));
		add_filter('usin_custom_query_filter', array($this, 'apply_filters'), 10, 2);
		add_filter('usin_custom_select', array($this, 'filter_query_select'), 10, 2);
	}
	
	/**
	 * Filters the default DB map fields and adds the custom EDD fields to the map.
	 * @param  array $db_map the default DB map array
	 * @return array         the default DB map array including the EDD fields
	 */
	public function filter_db_map($db_map){
		$db_map['edd_order_num'] = array('db_ref'=>'purchase_count', 'db_table'=>'edd_customers', 'null_to_zero'=>true);
		$db_map['edd_total_spent'] = array('db_ref'=>'purchase_value', 'db_table'=>'edd_customers', 'null_to_zero'=>true, 'cast'=>'DECIMAL', 'custom_select'=>true);
		$db_map['edd_has_ordered'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['edd_has_order_status'] = array('db_ref'=>'', 'db_table'=>'payments', 'no_select'=>true);
		$db_map['edd_last_order'] = array('db_ref'=>'edd_last_order', 'db_table'=>'payments_dates', 'nulls_last'=>true, 'cast'=>'DATETIME');
		return $db_map;
	}
	
	/**
	 * Adds the custom SELECT clauses for the EDD fields.
	 * @param  string $query_select the main SELECT clause to which to append the
	 * EDD selects
	 * @return string               the modified SELECT clause
	 */
	public function filter_query_select($query_select, $field){
		if($field == 'edd_total_spent'){
			$query_select='CAST(IFNULL(edd_customers.purchase_value, 0) AS DECIMAL(10,2))';
		}
		return $query_select;
	}

	/**
	 * Adds the custom query JOINS for the EDD fields.
	 * @param  string $query_joins the main JOINS string to which to append the 
	 * custom EDD joins 
	 * @return string              the modified JOINS query
	 */
	public function filter_query_joins($query_joins, $table){
		global $wpdb;

		if($table == 'edd_customers'){
			$query_joins .= $this->get_customers_join();
		}elseif($table == 'payments_dates'){
			$query_joins .= $this->get_customers_join();
			$query_joins .= " LEFT JOIN (SELECT $wpdb->postmeta.meta_value as customer_id, MAX($wpdb->posts.post_date) as edd_last_order FROM $wpdb->posts ".
				"LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->postmeta.meta_key = '_edd_payment_customer_id' ".
				"WHERE post_type = '$this->order_post_type' ".
				"GROUP BY customer_id) ".
			"AS payments_dates ON edd_customers.id = payments_dates.customer_id";
		}
		
		return $query_joins;
	}
	
	/**
	 * Serializes an array value (with the format key=>value) and returns 
	 * the value of the serialized string, by stripping the array part, so that
	 * this value can be used to search serialized strings in the database.
	 * @param  string $key   the key of the array element 
	 * @param  mixed $value the value of the array element
	 * @return string        the serialized key=>value value
	 */
	public function get_serialized_value($key, $value){
	    $arr = array($key=>$value);
	    $ser_arr = serialize($arr);
	    
	    //strip the array parts of the serialized string
	    $ser_arr = str_replace('a:1:{', '', $ser_arr);
	    $ser_arr = str_replace('}', '', $ser_arr);
	    
	    return $ser_arr;
	}
	
	public function filter_global_query_joins($joins){
		if($this->require_customer_join){
			$joins .= $this->get_customers_join();
		}
		$joins .= $this->joins;
		return $joins;
	}
	
	protected function get_customers_join(){
		if(!$this->customers_join_set){
			global $wpdb;
			$this->customers_join_set = true;
			return " LEFT JOIN ".$wpdb->prefix."edd_customers AS edd_customers ON $wpdb->users.ID = edd_customers.user_id";
		}
		return '';
	}
	
	/**
	 * Generates a LEFT JOIN with the posts table to join the orders (edd payments)
	 * posts only. This JOIN is generated only once.
	 * @return string the JOIN clause if it hasn't been loaded yet or an empty 
	 * string otherwise.
	 */
	protected function get_payments_join(){
		if(!$this->payments_join_set){
			global $wpdb;
			
			$this->payments_join_set = true;
			$this->require_customer_join = true;
			return " LEFT JOIN (SELECT $wpdb->postmeta.meta_value as customer_id, $wpdb->posts.* FROM $wpdb->posts ".
			           "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id and $wpdb->postmeta.meta_key = '_edd_payment_customer_id' ".
			           "WHERE post_type = 'edd_payment') ".
			           "AS payments ON edd_customers.id = payments.customer_id";
			
		}
		return '';
	}
	
	/**
	 * Applies the custom filters for "Products ordered include/exclude" and 
	 * "Orders status include/exclude"
	 * @param  array $custom_query_data includes the default joins, where and having 
	 * clauses, so that this function can generate them and return this array
	 * @param  object $filter            filter object, contains the filter data 
	 * such as condition and operator
	 * @return array                    the modified $custom_query_data array, that 
	 * includes the generated JOIN, WHERE and HAVING clauses
	 */
	public function apply_filters($custom_query_data, $filter){

		if(in_array($filter->operator, array('include', 'exclude'))){
			global $wpdb;
			$operator = $filter->operator == 'include' ? '>' : '=';
			
			if($filter->by == 'edd_has_ordered'){
				//filter by the products ordered (can be include or exclude)
				
				if(!$this->has_ordered_join_applied){
					//this join depends on the edd_customers join above, so we are going to append it
					//to the main joins query, instead of this one
					$this->joins .=  $this->get_payments_join().
						" INNER JOIN $wpdb->postmeta AS edd_meta ON payments.ID = edd_meta.post_id";

					$this->has_ordered_join_applied = true;
				}
				
				$condition = $this->get_serialized_value('id', (string)$filter->condition); //serialize the value so it can search in the serialized meta field
				$condition2 = $this->get_serialized_value('id', (int)$filter->condition); //sometimes the product ID is saved as integer

				$custom_query_data['where'] = " AND edd_meta.meta_key = '_edd_payment_meta'";

				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(edd_meta.meta_value LIKE '%%%s%%' OR edd_meta.meta_value LIKE '%%%s%%') $operator 0", $condition, $condition2);

			}elseif($filter->by == 'edd_has_order_status'){
				//filter by the status of the orders (can be include or exclude)
			
				$this->joins .= $this->get_payments_join();
			
				$custom_query_data['having'] = $wpdb->prepare(" AND SUM(payments.post_status IN (%s)) $operator 0", $filter->condition);
			
			}
		}

		return $custom_query_data;
	}
	
	/**
	 * Resets the query options - this should be called when more than one
	 * query is performed per http request
	 */
	public function reset(){
		$this->has_ordered_join_applied = false;
		$this->customers_join_set = false;
		$this->payments_join_set = false;
		$this->require_customer_join = false;
		$this->joins = '';
	}
	
}
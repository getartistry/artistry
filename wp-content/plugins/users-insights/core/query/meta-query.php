<?php

/**
 * User meta field query - generates the required database statements for a
 * field from the WordPress user_meta table.
 */
class USIN_Meta_Query{
	
	protected $key = '';
	protected $ref = '';
	protected $use_prefix = true;
	protected $type = '';
	protected $db_table = '';
	protected static $meta_fields = array();
	protected static $filters_set = false;
	
	/**
	 * [__construct description]
	 * @param string $key    the key of the field as used in the user_meta table
	 * @param string $type   the type of the field: text/number
	 * @param string $prefix optional prefix that can be used to prefix the field
	 * in the database queries. It is recommened to set a prefix to avoid conflicts
	 * with existing fields.
	 */
	public function __construct($key, $type, $prefix = ''){
		$this->key = $key;
		$this->type = $type;
		$this->ref = $prefix.$key;
		$this->db_table = '`'.$this->ref.'_meta`';
		
		self::$meta_fields[$this->ref]=array('key'=>$key, 'prefix'=>$prefix);
	}
	
	/**
	 * Registers all of the required hooks.
	 */
	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		
		if(!self::$filters_set){
			//set these filters only once
			add_filter('usin_single_user_query_fields', array($this, 'remove_meta_fields_from_query'));
			add_filter('usin_single_user_db_data', array($this, 'add_meta_fields_to_user_data'), 1);
			self::$filters_set = true;
		}
	}
	
	/**
	 * Adds the field options to the main query DB map.
	 * @param  array $db_map the default DB map options
	 * @return array         the default DB map options including the custom field
	 * options
	 */
	public function filter_db_map($db_map){
		global $wpdb;

		$map = array(
			'db_ref'=>'meta_value', 
			'db_table'=>$this->db_table,
			'nulls_last' => true
		);
		
		if($this->type == 'number'){
			$map['cast']='DECIMAL';
		}
			
		$db_map[$this->ref] = $map;
		
		return $db_map;
	}

	/**
	 * Adds a join statement for this field to the main DB query join statement.
	 * @param  string $query_joins the default query join
	 * @return string               the default query join including the join
	 * statement for this field
	 */
	public function filter_query_joins($query_joins, $table){
		global $wpdb;
		
		if($table === $this->db_table){
			$query_joins .= " LEFT JOIN $wpdb->usermeta AS $this->db_table ON ".
					"($wpdb->users.ID = $this->db_table.user_id AND $this->db_table.meta_key = '$this->key')";			
		}

		return $query_joins;
	}
	
	/**
	 * Removes the meta fields from the single user query, so that with a large
	 * number of custom meta fields, the query won't be too long and won't have
	 * too many joins.
	 * @param  array $fields the default query field keys
	 * @return array         the field keys without the meta field keys
	 */
	public function remove_meta_fields_from_query($fields){
		
		foreach ($fields as $key => $field) {
			if(isset(self::$meta_fields[$field])){
				unset($fields[$key]);
			}
		}
		$fields = array_values($fields);
		return $fields;
	}
	
	/**
	 * Loads the user meta data for the fields that are removed from the main query
	 * with the remove_meta_fields_from_query() method above. In this way a single 
	 * simple query is used for loading all the meta data for one user, instead of
	 * adding a JOIN to the main query for each field.
	 * @param object $user_data the user data containing the meta fields values
	 */
	public function add_meta_fields_to_user_data($user_data){
		
		global $wpdb;
		
		$query = $wpdb->prepare("SELECT * FROM $wpdb->usermeta WHERE user_id = %d", $user_data->ID);
		$meta_rows = $wpdb->get_results($query);
		
		//set the meta in a 1-dimensional array as key=>value for easier search
		$meta = array();
		foreach ($meta_rows as $row) {
			$meta[$row->meta_key] = $row->meta_value;
		}
		
		foreach (self::$meta_fields as $ref => $field) {
			if(isset($meta[$field['key']])){
				$user_data->$ref = $meta[$field['key']];
			}
		}
		
		return $user_data;
	}
	
}
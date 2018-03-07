<?php

/**
 * Includes the main functionality to build the database query that loads the users data.
 */
class USIN_Query{
	
	/**
	 * Sets the mapping of the options to the database columns names and table names
	 * db_ref : database column name
	 * db_table : database column table - "main" sets the users table, "meta" is the
	 * user_meta table
	 * nulls_last : when set to true and the data is ordered by this field ascending,
	 * it would load the null rows last instead of first
	 * null_to_zero : when set to true and the value of this column has to meet a condition
	 *
	 * custom_select : do not build select query automatically, but load a custom select clause
	 * no_ref: do not build a reference in the form of table.column
	 * @var array
	 */
	protected $db_map = array(
		'ID' => array('db_ref'=>'ID', 'db_table'=>'main'),
		'username' => array('db_ref'=>'user_login', 'db_table'=>'main'),
		'role' => array('db_ref'=>'meta_value', 'db_table'=>'role_meta', 'no_select'=>true),
		'email' => array('db_ref'=>'user_email', 'db_table'=>'main'),
		'name' => array('db_ref'=>'display_name', 'db_table'=>'main'),
		'first_name' => array('db_ref'=>'meta_value', 'db_table'=>'first_name_meta'),
		'last_name' => array('db_ref'=>'meta_value', 'db_table'=>'last_name_meta'),
		'registered' => array('db_ref'=>'user_registered', 'db_table'=>'main'),
		'website' => array('db_ref'=>'user_url', 'db_table'=>'main'),
		'posts' => array('db_ref'=>'posts', 'db_table'=>'', 'custom_select'=>true, 'set_alias'=>true),
		'comments' => array('db_ref'=>'comment_num', 'db_table'=>'comment_count', 'null_to_zero'=>true),
		'last_seen' => array('db_ref'=>'last_seen', 'db_table'=>'user_data', 'nulls_last'=>true, 'cast'=>'DATETIME'),
		'sessions' => array('db_ref'=>'sessions', 'db_table'=>'user_data', 'nulls_last'=>true, 'cast'=>'DECIMAL'),
		'browser' => array('db_ref'=>'browser', 'db_table'=>'user_data', 'nulls_last'=>true),
		'coordinates' => array('db_ref'=>'coordinates', 'db_table'=>'user_data'),
		'browser_version' => array('db_ref'=>'browser_version', 'db_table'=>'user_data', 'nulls_last'=>true),
		'platform' => array('db_ref'=>'platform', 'db_table'=>'user_data', 'nulls_last'=>true),
		'country' => array('db_ref'=>'country', 'db_table'=>'user_data', 'nulls_last'=>true),
		'city' => array('db_ref'=>'city', 'db_table'=>'user_data', 'nulls_last'=>true),
		'region' => array('db_ref'=>'region', 'db_table'=>'user_data', 'nulls_last'=>true),
		'user_groups' => array('db_ref'=>'term_id', 'db_table'=>'tt', 'custom_select'=>true, 'set_alias' => false),
		'notes_count' => array('db_ref'=>'meta_value', 'db_table'=>'nc', 'null_to_zero'=>true, 'cast'=>'DECIMAL')
	);

	public $args;
	public $filters;
	public $query;
	public $query_select = '';
	public $query_joins = '';
	public $query_where = ' WHERE 1=1';
	public $query_having = ' HAVING 1=1';
	public $query_order = '';
	public $user_data_db_table;
	public $join_tables = array();
	public $loaded_join_tables = array();
	
	protected $meta_query_num = 0;

	/**
	 * @param array  $args    the options/arguments for the query
	 * @param array $filters the filters to apply to the query
	 */
	public function __construct($args=array(), $filters=null){

		$this->args = $args;
		$this->filters = $filters;
		$this->db_map = apply_filters('usin_db_map', $this->db_map);
		$this->build_db_ref();

		global $usin;
		$this->user_data_db_table = $usin->manager->user_data_db_table;
	}

	/**
	 * For each registered column in the mapping, builds a unique reference to the column 
	 * that can be used in the query.
	 */
	protected function build_db_ref(){
		global $wpdb;

		foreach ($this->db_map as $key => &$map) {
			if(isset($map['db_ref']) && isset($map['db_table'])){
				$table = $map['db_table'];
				$ref = $map['db_ref'];
				
				//set if the select should set an alias
				if(!isset($map['set_alias'])){
					$map['set_alias'] = $key != $ref;
				}
				
				if(!isset($map['no_ref'])){
					if($table == 'main'){
						$map['db_ref'] = $wpdb->users.'.'.$ref;
					}elseif(!empty($table)){
						$map['db_ref'] = $map['db_table'].'.'.$ref;
					}
				}
			}
			
		}
	}
	
	/**
	 * Builds the SELECT clause
	 */
	protected function set_query_select($default_fields = null){
		global $wpdb;
		
		$fields_without = array();
		
		if(!empty($default_fields)){
			$fields = $default_fields;
		}else{
			$fields = usin_options()->get_visible_fields();
			$fields_without[]='comments';
			$fields_without[]='user_groups';
			$fields_without = apply_filters('usin_query_fields_without', $fields_without);
		}
		
		$fields = array_diff($fields, $fields_without); //some fields shouldn't be loaded 
		//unless they are used in filters or order by
		
		if(isset($this->args['orderby'])){
			//add order by to the fields
			$fields[]=$this->args['orderby'];
		}
		//add the fields added to the filters
		$filter_fields = empty($this->filters) ? array() : wp_list_pluck($this->filters, 'by');
		$fields = array_unique(array_merge($fields, $filter_fields));
		
		$selects = array($this->db_map['ID']['db_ref']);
		
		foreach ($fields as $field) {
			if(isset($this->db_map[$field])){
				
				$map = $this->db_map[$field];
				
				if(!isset($map['no_select'])){
					if(isset($map['custom_select'])){
						//apply a custom select
						$select = $this->get_custom_select($field);
					}else{
						$select = $map['db_ref'];
						if(isset($map['null_to_zero']) && $map['null_to_zero']){
							$select = "IFNULL($select, 0)";
						}
					}
					
					if(!empty($select)){
						$selects[] = $map['set_alias'] ? sprintf("%s AS `%s`", $select, $field) : $select;
					}
				}
				
				$db_table = empty($map['db_table']) ? $field : $map['db_table']; //if no table is set, use the field key as reference
				if($db_table != 'main' && !isset($this->join_tables[$db_table])){
					$this->join_tables[] = $db_table;
				}
				
			}
		}
		
		$this->query_select = 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $selects).' FROM '.$wpdb->users;
		$this->query_select = apply_filters('usin_user_query_select', $this->query_select);
	}
	
	/**
	 * Builds a custom select clause for a single field when its select statement
	 * is complex and can't be built automatically
	 * @param  string $field the field ID
	 * @return string        the select statement
	 */
	protected function get_custom_select($field){
		global $wpdb;
		
		switch ($field) {
			case 'posts':
				return "COUNT(DISTINCT $wpdb->posts.ID)";
				break;
			case 'user_groups':
				$select = '';
				if(isset($this->args['export'])){
					//export process loads the groups with a left join
					$select = "GROUP_CONCAT(DISTINCT wpt.name SEPARATOR ', ') as user_groups";
				}
				return $select;
				break;
			default:
				return apply_filters('usin_custom_select', '', $field);
				break;
		}
	}
	

	/**
	 * Calls the required functions to apply the filters/conditions to the query.
	 */
	protected function set_filters(){
		if(!empty($this->filters)){
			$this->apply_filters();
		}

		$this->add_multisite_filter();
	}

	/**
	 * Appends the conditional WHERE/HAVING queries to the main query.
	 */
	protected function set_conditions(){
		global $wpdb;
		$this->query_where = apply_filters('usin_query_where', $this->query_where);
		if($this->query_where != " WHERE 1=1"){
			$this->query .= $this->query_where;
		}

		$this->query .= " GROUP BY $wpdb->users.ID";
		
		$this->query_having = apply_filters('usin_query_having', $this->query_having);
		if($this->query_having != " HAVING 1=1"){
			$this->query .= $this->query_having;
		}
	}

	/**
	 * Adds a filter to load the users for the current network/site on multisite
	 * installations. Since the multisite installations share one users table
	 * between all of the sites, the way we filter the users by the current site
	 * is by applying an inner join to the user meta table on the wp_[ID]_capability field.
	 */
	protected function add_multisite_filter(){
		global $wpdb;
		if ( is_multisite()) {
			$blog_id = $GLOBALS['blog_id'];
			if($blog_id){
				$key = $wpdb->get_blog_prefix( $blog_id ) . 'capabilities';
				$this->generate_meta_ref($key);
			}

		}
	}
	
	
	/**
	 * Checks if the filter by is a column that has been set by an
	 * aggregate function
	 * @param  array $filter the filter options
	 * @return boolean          true if the filter column has been set by an
	 * aggregate function and false otherwise.
	 */
	protected function filter_contains_function_col($filter){
		//add list with column names that have been generated from aggregate functions
		$func_columns = array('posts', 'user_groups');
		$func_columns = apply_filters('usin_db_aggregate_columns', $func_columns);

		if(isset($filter->by) && in_array($filter->by, $func_columns)){
			return true;
		}
		
		return false;
	}

	/**
	 * Checks whether the current filters contain a selected column.
	 * @param  string $column the column ID
	 * @return boolean         true if the filters contain the column and false
	 * otherwise.
	 */
	protected function filters_contain_col($column){
		if(!empty($this->filters)){
			foreach ($this->filters as $filter) {
				if(isset($filter->by) && $filter->by == $column){
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Retrieves the JOIN clauses for the query.
	 * @return string a string containing all of the JOIN clauses
	 */
	protected function get_query_joins(){
		global $wpdb;
		
		foreach ($this->join_tables as $table ) {
			
			if(!in_array($table, $this->loaded_join_tables)){
				switch ($table) {
					case 'user_data':
						$this->query_joins.= " LEFT JOIN ".$wpdb->prefix."$this->user_data_db_table as user_data ON ".
							"$wpdb->users.ID = user_data.user_id";
						break;
					
					case 'tt':
						//user groups
						$this->query_joins.= " LEFT JOIN $wpdb->term_relationships rel ON $wpdb->users.ID = rel.object_id";
						$this->query_joins.= " LEFT JOIN $wpdb->term_taxonomy tt ON rel.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy = '".USIN_GROUPS::$slug."'";
						if(isset($this->args['export'])){
							//we need the term names for the export
							$this->query_joins.= " LEFT JOIN $wpdb->terms wpt ON tt.term_id = wpt.term_id";
						}
						break;
					
					case 'nc':
					case 'first_name_meta':
					case 'last_name_meta':
						//meta fields
						$keys = array(
							'nc' => '_usin_note_count',
							'first_name_meta' => 'first_name',
							'last_name_meta' => 'last_name'
						);
						$this->query_joins.=$wpdb->prepare(" LEFT JOIN $wpdb->usermeta $table ON $wpdb->users.ID = $table.user_id AND $table.meta_key = %s", $keys[$table]);
						break;
						
					case 'posts':
						//post count
						$join_query = " LEFT JOIN $wpdb->posts on $wpdb->users.ID = $wpdb->posts.post_author";

						$allowed_statuses = USIN_Helper::get_allowed_post_statuses('sql_string');
						if(!empty($allowed_statuses)){
							$join_query .= " AND $wpdb->posts.post_status IN ($allowed_statuses)";
						}

						$allowed_post_types = USIN_Helper::get_allowed_post_types('sql_string');
						if(!empty($allowed_post_types)){
							$join_query .= " AND $wpdb->posts.post_type IN ($allowed_post_types)";
						}

						$this->query_joins .= $join_query;
						break;
					case 'comment_count':
						$exclude_comment_types = USIN_Helper::get_exclude_comment_types('sql_string');
						$where = '';
						if(!empty($exclude_comment_types)){
							$where = "WHERE comment_type NOT IN ($exclude_comment_types) ";
						}
						$this->query_joins .= " LEFT JOIN (SELECT user_id, COUNT(*) as comment_num".
							" FROM $wpdb->comments ".$where."GROUP BY user_id)".
							" as comment_count ON ($wpdb->users.ID = comment_count.user_id)";
						break;
					case 'role_meta':
						if(is_multisite()){
							//change the wp_capabilities ref to wp_[ID]_capabilities
							$blog_id = $GLOBALS['blog_id'];
							$key = $wpdb->get_blog_prefix( $blog_id ) . 'capabilities';
						}else{
							$key = $wpdb->prefix . 'capabilities';
						}
						$this->generate_meta_ref($key, true, 'role_meta');
						break;
					default:
						$this->query_joins .= apply_filters('usin_query_join_table', '', $table);
						break;
				}
				
				$this->loaded_join_tables[]=$table;
				
				
			}
			
		}
		
		$this->query_joins = apply_filters('usin_query_joins', $this->query_joins);
		return $this->query_joins;
	}

	/**
	 * Applies the required clauses for ordering.
	 */
	protected function set_query_order(){
		global $wpdb;

		$args = $this->args;
		//set order by
		$orderby = 'user_registered';
		$args['order'] = isset( $args['order'] ) ? strtoupper( $args['order'] ) : '';
		$order = $args['order'] == 'ASC' ? 'ASC' : 'DESC';

		if(!empty($args['orderby']) && isset($this->db_map[$args['orderby']])){
			$map = $this->db_map[$args['orderby']];
			$orderby = $map['db_ref'];
			if(isset($map['cast'])){
				$type = $map['cast'];
				$cast_orderby = "CAST($orderby AS $type)";
			}

		}
		
		if(!isset($cast_orderby)){
			$cast_orderby = $orderby;
		}

		if(isset($args['orderby']) && isset($this->db_map[$args['orderby']]['nulls_last']) && $order=='ASC'){
			//make the NULLs and empty strings displayed last
			$this->query_order = " ORDER BY (ISNULL($orderby) OR $orderby = '') ASC, $cast_orderby ASC";
		}else{
			$this->query_order = " ORDER BY $cast_orderby $order";
		}
		
		if($orderby != 'user_login'){
			$this->query_order .= ", $wpdb->users.user_login ASC";
		}

	}

	/**
	 * Applies the selected filters.
	 */
	protected function apply_filters(){
		if(!empty($this->filters)){
			foreach ($this->filters as $filter) {
				if(isset($filter->by) && isset($this->db_map[$filter->by]) && 
					(isset($filter->condition) || in_array($filter->operator, array('isnull', 'notnull', 'isset', 'notset')))){
					if($filter->by=='role'){
						//set the operator to check the string for contains and not contains
						$filter->operator = $filter->operator == 'is' ? 'contains' : 'notcontains';
					}

					if($this->filter_contains_function_col($filter)){
						$clause = &$this->query_having;
					}else{
						$clause = &$this->query_where;
					}

					switch ($filter->operator) {
						case 'is':
						case 'contains':
						case 'starts':
						case 'ends':
						case 'not' :
						case 'notcontains' :
							$this->add_text_search_filter($filter, $clause);
							break;
						case 'equals':
						case 'bigger':
						case 'smaller':
							$this->add_date_number_filter($filter, $clause);
							break;
						case 'morethan':
						case 'lessthan':
						case 'exactly':
							$this->add_days_ago_filter($filter, $clause);
							break;
						case 'isnull':
						case 'notnull':
							$this->add_null_filter($filter, $clause);
							break;
						case 'include_wn':
						case 'exclude_wn':
						case 'isset':
						case 'notset':
							$this->add_include_exclude_with_nulls_filter($filter, $clause);
							break;
						case 'contains_ser':
						case 'notcontains_ser':
							$this->add_serialized_search_filter($filter, $clause);
							break;
						case 'include':
						case 'exclude':
						case 'custom':
							$custom_query_data = array(
								'where' => '',
								'having' => '',
								'joins' => ''
								);
							$custom_query_data = apply_filters('usin_custom_query_filter', $custom_query_data, $filter);
							$this->query_where .= $custom_query_data['where'];
							$this->query_having .= $custom_query_data['having'];
							$this->query_joins .= $custom_query_data['joins'];

							break;
					}
				}
			}
		}
	}

	/**
	 * Generates a unique reference for the usermeta table, so that multiple
	 * joins can be made for this table to retrieve different user meta fields
	 * @param  string  $meta_key  the meta key that will be used for the join
	 * @param  boolean $left_join sets when set to true, a left join will be applied,
	 * otherwise inner join will be applied
	 * @return string             the new alias that can be used to access the columns
	 * from this join
	 */
	protected function generate_meta_ref($meta_key, $left_join = false, $custom_alias = null){
		global $wpdb;
		$alias = $custom_alias ? $custom_alias : 'mt'.$this->meta_query_num;
		$join = $left_join ? 'LEFT' : 'INNER';
		$this->query_joins .= " $join JOIN $wpdb->usermeta AS $alias ON ".
			"($wpdb->users.ID = $alias.user_id AND $alias.meta_key = '$meta_key')";

		$this->meta_query_num++;

		return $alias;
	}

	protected function add_text_search_filter($filter, &$clause){
		if( (!empty($filter->condition) || $filter->condition=='0') && isset($this->db_map[$filter->by])){
			global $wpdb;
			$map = $this->db_map[$filter->by];
			$format = $this->get_db_search_format($filter->operator, $filter->condition);
			$operator = in_array($filter->operator, array('not', 'notcontains')) ? 
					'NOT LIKE' : 'LIKE';

			$db_ref = $map['db_ref'];

			$clause .= $wpdb->prepare(" AND $db_ref $operator %s", $format);
		}
	}
	
	protected function add_serialized_search_filter($filter, &$clause){
		if( (!empty($filter->condition) || $filter->condition=='0') && isset($this->db_map[$filter->by])){
			global $wpdb;
			$map = $this->db_map[$filter->by];
			$db_ref = $map['db_ref'];
			$ser = serialize($filter->condition);
			
			if($filter->operator == 'contains_ser'){
				//is operator
				//search for both serialized and unserialized values, e.g. it will search
				//for both 's:5:"value";' and 'value'
				$clause .= $wpdb->prepare(" AND ($db_ref LIKE %s OR $db_ref LIKE %s)", '%'.$ser.'%', $filter->condition);
			}else{
				$clause .= $wpdb->prepare(" AND ($db_ref NOT LIKE %s AND $db_ref NOT LIKE %s)", '%'.$ser.'%', $filter->condition);
			}
			
		}
	}

	protected function add_null_filter($filter, &$clause){
		if(isset($this->db_map[$filter->by])){
			global $wpdb;

			$map = $this->db_map[$filter->by];
			$condition = $filter->operator === 'isnull' ? 
				"(%s='' OR %s IS NULL)" : "%s!='' AND %s IS NOT NULL";

			$db_ref = $map['db_ref'];

			$clause .= " AND ".sprintf($condition, $db_ref, $db_ref);
		}
	}

	protected function add_date_number_filter($filter, &$clause){
		global $wpdb;

		$map = $this->db_map[$filter->by];
		$operators = array ('equals'=>'=', 'bigger'=>'>', 'smaller'=>'<');
		$operator = $operators[$filter->operator];

		$db_ref = $map['db_ref'];


		$ref = $db_ref;
		
		if($filter->type == 'date'){
			$clause .= $wpdb->prepare(" AND DATE($ref) $operator %s", $filter->condition);
		}else{
			if(isset($map['null_to_zero']) && $map['null_to_zero']===true){
				$ref = 'IFNULL('.$ref.', 0)';
			}
			$clause .= $wpdb->prepare(" AND $ref $operator %F", $filter->condition);
		}
	}


	protected function add_days_ago_filter($filter, &$clause){
		global $wpdb;

		$map = $this->db_map[$filter->by];
		$operators = array ('morethan'=>'>', 'lessthan'=>'<', 'exactly'=>'=');
		$operator = $operators[$filter->operator];

		$db_ref = $map['db_ref'];

		$ref = $db_ref;
		$today = current_time('mysql');

		$is_datetime = isset($map['cast']) && $map['cast'] == 'DATETIME';
		
		if($is_datetime && $filter->condition === 1 && $operator == '<'){
			//load the last 24 hours
			$clause .= $wpdb->prepare(" AND TIMESTAMPDIFF(HOUR, $ref, %s) < 24", $today);
		}elseif($is_datetime && $filter->condition === 1 && $operator == '='){
			//load from yesterday's date and the last 24 - 48 hours
			$clause .= $wpdb->prepare(" AND (TIMESTAMPDIFF(HOUR, $ref, %s) BETWEEN 24 AND 48) AND (DATE(%s) - INTERVAL 1 DAY) = DATE($ref)", $today, $today);
		}else{
			$clause .= $wpdb->prepare(" AND (DATE(%s) - INTERVAL %d DAY) $operator DATE($ref)", $today, $filter->condition);
		}
	}
	
	protected function add_include_exclude_with_nulls_filter($filter, &$clause){
		global $wpdb;
		$map = $this->db_map[$filter->by];
		$ref = $map['db_ref'];
	
		switch ($filter->operator) {
			case 'include_wn':
				$clause .= $wpdb->prepare(" AND SUM($ref = %d) > 0", $filter->condition);
				break;
			case 'exclude_wn':
				$clause .= $wpdb->prepare(" AND ( SUM($ref = %d) = 0 OR COUNT($ref) = 0)", $filter->condition);
				break;
			case 'isset':
				$clause .= " AND COUNT($ref) > 0";
				break;
			case 'notset':
				$clause .= " AND COUNT($ref) = 0";
				break;
		}
	}

	protected function db_rows_to_objects($results){
		$users = array();
		$export_options = isset($this->args['export']) ? $this->args['export'] : null;
		
		foreach ($results as $res) {
			if($export_options){
				$res->is_exported = true;
				$users[] = new USIN_User_Exported($res, $export_options);
			}else{
				$users[] = new USIN_User($res);
			}
		}
		return $users;
	}


	protected function get_db_search_format($operator, $string){
		switch ($operator) {
			case 'contains':
			case 'notcontains':
				$f = '%'.$string.'%';
				break;
			case 'starts':
				$f = $string.'%';
				break;
			case 'ends':
				$f = '%'.$string;
				break;
			default:
				$f = $string;
				break;
		}

		return $f;
	}
}
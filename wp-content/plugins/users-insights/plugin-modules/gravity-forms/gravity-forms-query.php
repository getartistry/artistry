<?php

/**
 * Gravity Forms module query functionality.
 */
class USIN_GF_Query{
	
	protected $gf_fields;
	protected $prefix;
	protected $count = 0;

	/**
	 * Initializes the main functionality.
	 */
	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_custom_query_filter', array($this, 'apply_custom_query_filters'), 10, 2);	
	}
	
	public function filter_db_map($db_map){
		$db_map['has_completed_form'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['has_not_completed_form'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		return $db_map;
	}
	
	public function apply_custom_query_filters($custom_query_data, $filter){
		global $wpdb;
		$ref = 'rgl_'.++$this->count;
		
		if($filter->by == 'has_completed_form' || $filter->by == 'has_not_completed_form'){
			$custom_query_data['joins'] .= $wpdb->prepare(" LEFT JOIN ".
				"(SELECT form_id, created_by FROM ".$wpdb->prefix."rg_lead WHERE form_id = %d GROUP BY created_by) AS $ref ON ".
				"$wpdb->users.ID = $ref.created_by", $filter->condition);
				
			$operator = $filter->by == 'has_completed_form' ? 'IS NOT NULL' : 'IS NULL';
			$custom_query_data['where'] = " AND $ref.form_id $operator";
		}
	
		return $custom_query_data;
	}
	
	/**
	 * Initializes the meta query for the Gravity Forms fields.
 	 * @param array $gf_fields the Gravity Forms fields
 	 * @param string $prefix    prefix to use for prefixing the GF fields, so
 	 * they don't overwrite the default fields
 	 */
	public function init_meta_query($gf_fields, $prefix){
		if(is_admin()){
			foreach ($gf_fields as $field ) {
				$meta_query = new USIN_Meta_Query($field['id'], $field['type'], $prefix);
				$meta_query->init();
			}
		}
	}
}
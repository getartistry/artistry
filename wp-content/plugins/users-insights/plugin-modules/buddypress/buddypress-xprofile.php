<?php

class USIN_BuddyPress_XProfile{
	
	protected $wpdb;
	protected $prefix;
	protected $fields = null;
	public $multi_option_fields = array();
	public static $field_prefix = 'bxp_';
	
	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
	}
	
	public function get_fields(){
		if($this->fields === null){
			$fields = array();
			$query = "SELECT id, type, name FROM ".$this->prefix."bp_xprofile_fields ".
				"WHERE parent_id = 0 AND id != 1"; //id != 1 -> the default Name field is created first with ID 1
			
			$results =  $this->wpdb->get_results( $query );
			foreach ($results as $data ) {
				$field = $this->get_field_data($data);
				if(!empty($field)){
					$fields[]=$field;
				}
			}
			
			$this->fields = $fields;
		}
		
		return $this->fields;
	}
	
	protected function get_field_data($db_data){
		if(isset($db_data->name, $db_data->id, $db_data->type)){
			$type = $this->get_usin_field_type($db_data->type);
			$field_id = self::$field_prefix.$db_data->id;
			
			$field = array(
				'name'=>$db_data->name,
				'id'=>$field_id,
				'bpx_id'=>$db_data->id,
				'order' => 'ASC',
				'show' => false,
				'fieldType' => 'general',
				'filter' => array(
					'type' => $type,
				),
				'module' => 'buddypress'
			);
			
			if($this->is_multi_option_field($db_data)){
				$this->multi_option_fields[]=$field_id;
				$field['order'] = false;
			}
			
			if($type=='select'){
				$field['filter']['options'] = $this->get_select_field_options($db_data->id);
			}elseif($type=='date'){
				$field['filter']['yearsRange'] = array(-110, 20);
			}
			
			return $field;
		}
		
	}
	
	
	protected function get_select_field_options($field_id){
		$query = "SELECT name FROM ".$this->prefix."bp_xprofile_fields ".
			"WHERE parent_id = $field_id AND type = 'option'";
		
		$option_names=$this->wpdb->get_col( $query );
		
		foreach ($option_names as $option_name) {
			$options[]= array('key'=>$option_name, 'val'=>$option_name);
		}
		
		return $options;
		
	}
	
	protected function is_multi_option_field($field){
		$mo_fields = array('multiselectbox', 'checkbox');
		return in_array($field->type, $mo_fields);
	}
	
	
	protected function get_usin_field_type($bp_type){
		switch ($bp_type) {
			case 'number':
				return 'number';
				break;
			case 'datebox':
				return 'date';
				break;
			case 'selectbox':
			case 'radio':
				return 'select';
				break;
			case 'checkbox':
			case 'multiselectbox':
				return 'multioption_text';
				break;
			default:
				return 'text';
				break;
		}
	}
	
}
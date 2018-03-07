<?php

class USIN_UM_Field{
	
	protected $options;
	protected $prefix;
	protected $module_id;
	
	public function __construct($options, $prefix, $module_id){
		$this->options = $options;
		$this->prefix = $prefix;
		$this->module_id = $module_id;
	}
	
	public function get_meta_key(){
		return isset($this->options['metakey']) ? $this->options['metakey'] : null;
	}
	
	protected function get_um_type(){
		return isset($this->options['type']) ? $this->options['type'] : null;
	}
	
	/**
	 * Generates the field options in a Users Insights field options format.
	 * @return array the Users Insights field options for this Ultimate Member field
	 */
	public function to_usin_field(){
		$meta_key = $this->get_meta_key();
		$type = $this->get_usin_field_type();
		
		$field = array(
			'id' => $this->prefix.$meta_key,
			'meta_key' => $meta_key,
			'name' => $this->options['title'],
			'order' => 'ASC',
			'show' => false,
			'fieldType' => 'general',
			'filter' => array(
				'type' => $type,
			),
			'module' => $this->module_id
		);
		
		if($this->is_field_data_serialized()){
			//this field's data is serialized
			$field['order'] = false; //disable sorting by this field
		}
		
		if($this->is_option_field() && !empty($this->options['options'])){
			//this is a field with an option to select, such as a select or radio field
			$field['filter']['options'] = $this->generate_options($this->options['options']);
		}
		
		if($type=='date'){
			$field['filter'] = $this->get_date_filter_options();
		}
		
		return $field;
		
	}
	
	/**
	 * Checks if this is a field with one option to select.
	 * @param  array  $field the field data
	 * @return boolean        true if it is an option field and false otherwise
	 */
	protected function is_option_field(){
		$opt_fields = array('select', 'radio', 'multiselect', 'checkbox', 'user_tags');
		return in_array($this->get_um_type(), $opt_fields);
	}
	
	
	/**
	 * Matches an Ultimate Member field type to Users Insights field type.
	 * @return string          the corresponding Users Insights field type
	 */
	protected function get_usin_field_type(){
		switch ($this->get_um_type()) {
			case 'number':
			case 'rating':
				return 'number';
			case 'checkbox':
			case 'multiselect':
				return 'serialized_multioption';
			case 'radio':
				return 'serialized_option';
			case 'select':
				return 'select';
			case 'date':
				return 'date';
			case 'user_tags':
				return 'multioption_text';
			default:
				return 'text';
		}
	}
	
	/**
	 * Checks if this is a field that stores the data in a serialized format.
	 * @param  array  $field the field data
	 * @return boolean        true if the field stores the data in a serialized 
	 * format and false otherwise
	 */
	public function is_field_data_serialized(){
		$ser_fields = array('multiselect', 'checkbox', 'radio', 'user_tags');
		return in_array($this->get_um_type(), $ser_fields);
	}
	
	
	/**
	 * Checks if the current user can view this field
	 * @return boolean        true if the user can view this field and false otherwise
	 */
	protected function is_visible_for_current_user(){
		if(isset($this->options['public'])){
			$public = $this->options['public'];
			if($public == -3){
				if(!empty($this->options['roles']) && is_array($this->options['roles'])){
					foreach ($this->options['roles'] as $role ) {
						if(current_user_can($role)){
							return true;
						}
					}
				}
				//the current user is not in the allowed roles
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Generates options for a select field in the Users Insights field options
	 * format.
	 * @param  array $arr        the array containing the options
	 * @return array             the options formatted for the Users Insights
	 * field options
	 */
	protected function generate_options($arr){
		$options = array();
		
		foreach ($arr as $key => $value) {
			$options[]= array('key'=>$value, 'val'=>$value);
		}
		return $options;
	}
	
	
	/**
	 * Generates the filter options for a date field. Checks the default UM
	 * field range settings and applies the the range to the default Users Insights
	 * year range option.
	 * @return array        the filter data that will be used in Users Insights
	 */
	protected function get_date_filter_options(){
		$filter = array('type'=>'date');
		
		if(isset($this->options['years']) && isset($this->options['years_x'])){
			$years = (int)$this->options['years'];
			
			if(isset($this->options['range_start']) && isset($this->options['range_end'])){
				//it's a range of dates
				$currentYear = (int)date("Y");
				$startYear = (int)substr($this->options['range_start'], 0, 4);
				$endYear = (int)substr($this->options['range_end'], 0, 4);
				$rangeStart = -($currentYear-$startYear);
				$rangeEnd = $endYear-$currentYear;
				
				if($startYear && $endYear &&  //make sure it is a real range
					abs($rangeStart < 150) && abs($rangeEnd < 150)){
					$range = array($rangeStart, $rangeEnd);
				}
			}else{
				switch ($this->options['years_x']) {
					case 'past':
						$range = array(-$years, 0);
						break;
					case 'future':
						$range = array(0, $years);
						break;
					case 'equal':
						$range = array(-$years, $years);
						break;
				}
			}
			
		}
		
		if(!empty($range)){
			$filter['yearsRange'] = $range;
		}
		
		return $filter;
	}
	
	/**
	 * Checks whether the field should be ignored. Some fields don't make sense
	 * to be added to the user table, such as image fields or password fields.
	 * Other fields are just the default WP fields that Users Insights loads.
	 * All those fields should be ignored.
	 * @return boolean        true if the field should be ignored and false otherwise
	 */
	public function should_be_ignored(){
		$meta_key = $this->get_meta_key();
		
		if(empty($meta_key)){
			//no meta ket set, ignore field
			return true;
		}
		
		if(!$this->is_visible_for_current_user()){
			//the current user is not allowed to see this field, ignore field
			return true;
		}
		
		$ignore_fields = apply_filters('usin_ignore_um_fields',
			array('user_password', 'username', 'user_login', 'user_email', 
					'user_registered', 'role_select', 'last_login'));
		if(in_array($meta_key, $ignore_fields)){
			return true;
		}
		
		$type = $this->get_um_type();
		if(empty($type)){
			//no type set, ignore field
			return true;
		}
		
		$ignore_types = apply_filters('usin_ignore_um_types', 
			array('row', 'divider', 'spacing', 'password', 'shortcode', 'block', 'image', 'file'));
		if(in_array($type, $ignore_types)){
			return true;
		}
			
		return false;
	}
	
	
}
<?php

class USIN_Options{

	protected $options = array(
		'users_per_page' => 20,
		'fields' => array(),
		'orderby' => 'last_seen',
		'order' => 'DESC',
		'field_order' => array()
	);
	protected $visble_fields_cache = array();

	public function get($option_key, $default = null){
		switch ($option_key) {
			case 'fields':
				return $this->get_fields();
				break;
			case 'users_per_page':
				$val = $this->get_user_option($option_key);
				return $val ? $val : $this->options[$option_key];
				break;
			default:
				if(isset($this->options[$option_key])){
					return $this->options[$option_key];
				}
				return $default;
				break;
		}
	}

	public function get_user_option($key){
		$user_id = get_current_user_id();
		return get_user_meta($user_id, 'usin_'.$key, true);
	}


	public function get_fields(){
		if(!isset($this->fields)){
			//load the fields data
			$fields = $this->get_default_fields();
			$fields = $this->get_active_module_fields($fields);

			$user_id = get_current_user_id();
			$displayed_fields = get_user_meta($user_id, 'usin_fields', true);
			if(!empty($displayed_fields)){
				foreach ($fields as &$field){
					$field['show'] = in_array($field['id'], $displayed_fields);
				}
			}

			$this->fields = $fields;
		}

		return $this->fields;
	}
	
	public function get_ordered_fields($order = null){
		$fields = $this->get_fields();
		
		if($order == null){
			$order = $this->get_user_option('field_order');
		}
		
		if(!empty($order)){
			$field_ids = wp_list_pluck($fields, 'id');
			usort($fields, array(new USIN_Options_Field_Sorter($order, $field_ids), 'sort'));
		}
		
		return $fields;
	}
	
	public function get_visible_fields(){
		$fields = $this->get_fields();
		$visible_fields = array();
		foreach ($fields as $field ) {
			if($field['show']){
				$visible_fields[]=$field['id'];
			}
		}
		return $visible_fields;
	}

	/**
	 * This function caches the results so that it is not repeated when 
	 * loading the data for big amounts of users
	 * @param  [type] $type [description]
	 * @return [type]       [description]
	 */
	public function get_field_ids_by_type($type){
		$fields = $this->get_fields();
		$fields_by_type = array();

		foreach ($fields as $field) {
			if(isset($field['filter']['type']) && $field['filter']['type'] == $type){
				$fields_by_type[]=$field['id'];
			}
		}

		return $fields_by_type;
	}
	
	public function get_field_ids_by_field_type($type){
		$fields = $this->get_fields();
		$fields_by_type = array();

		foreach ($fields as $field) {
			if(isset($field['fieldType']) && $field['fieldType'] == $type){
				$fields_by_type[]=$field['id'];
			}
		}

		return $fields_by_type;
	}
	
	public function get_field_types(){
		return USIN_Field_Defaults::get_field_types();
	}

	public function get_filter_operators(){
		$field_types = $this->get_field_types();
		$operators = array();
		foreach ($field_types as $key => $options) {
			$operators[$key] = $options['operators'];
		}
		return $operators;
	}
	
	public function get_field_types_by_type($type){
		$field_types = $this->get_field_types();
		$res = array();
		foreach ($field_types as $key => $options) {
			if(isset($options['type']) && $options['type'] === $type){
				$res[]=$key;
			}
		}
		
		return $res;
	}
	
	protected function get_default_fields(){
		$fields = USIN_Field_Defaults::get_fields();
		$fields = apply_filters('usin_fields', $fields);

		return $fields;
	}
	
	/**
	 * Sets a default icon if it is missing.
	 * @param array $fields the fields to which to set the icons
	 */
	public function set_icons($fields){
		
		foreach ($fields as &$field ) {
			if(!isset($field['icon'])){
				$field['icon'] = isset($field['module']) ? $field['module'] : 'field';
			}
		}
		
		return $fields;
	}

	protected function get_active_module_fields($fields){
		$active_fields = array();

		foreach ($fields as $key => $field) {
			if( !isset($field['module']) ||
				(isset($field['module']) && usin_module_options()->is_module_active($field['module']))){
				$active_fields[]= $field;
			}
		}

		return $active_fields;
	}
	
	public function get_editable_fields(){
		$fields = $this->get_fields();
		$editable_fields = array();
		
		foreach ($fields as $field) {
			if(!empty($field['editable'])){
				$editable_fields[]=$field['id'];
			}
		}
		return $editable_fields;
	}

	public function get_field_by_id($field_id){
		$fields = $this->get_fields();
		
		foreach ($fields as $field) {
			if($field['id'] == $field_id){
				return $field;
			}
		}
	}
	
	public function is_field_visible($field_id){
		if(!isset($this->visible_fields_cache[$field_id])){
			$field = $this->get_field_by_id($field_id);
			$visible = (!empty($field) && isset($field['show'])) ? $field['show'] : false;
			$this->visible_fields_cache[$field_id] = $visible;
		}
		
		return $this->visible_fields_cache[$field_id];
	}

	public function update_user_option($key, $val){
		$user_id = get_current_user_id();
		if(isset($this->options[$key]) && !empty($val) && $user_id){
			$res = update_user_meta( $user_id, 'usin_'.$key, $val );
			return (bool)$res;
		}

		return false;
	}

}

class USIN_Options_Field_Sorter{
    private $order;
    private $def_field_ids;

    function __construct( $order, $def_field_ids ) {
        $this->order = $order;
        $this->def_field_ids = $def_field_ids;
    }

    function sort( $a, $b ) {
		
		$a_index = array_search($a['id'], $this->order);
		$b_index = array_search($b['id'], $this->order);
		if($a_index === false){
			//the field doesn't exist in the order list
			//set a big index so it is displayed last
			$a_index = 10000 + $this->get_default_index($a['id']);
		}
		if($b_index === false){
			//the field doesn't exist in the order list
			//set a big index so it is displayed last
			$b_index = 10000 + $this->get_default_index($b['id']);
		}
		return ($a_index < $b_index) ? -1 : 1;
    }
	
	function get_default_index($field_id){
		return intval(array_search($field_id, $this->def_field_ids));
	}
}
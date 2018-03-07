<?php

if(!defined( 'ABSPATH' )){
	exit;
}

/**
 * Includes the functionality to register the custom fields to the UsersInsights
 * default fields functionality.
 */
class USIN_Custom_Fields{
	
	protected $custom_fields;
	public $prefix = 'usin_meta_';
	
	public function __construct(){
		$this->init();
	}
	
	/**
	 * Inits the main functionality and registers the required hooks.
	 */
	public function init(){
		add_filter('usin_fields', array($this , 'register_fields'));
		$this->register_fields_query();
	}
	
	/**
	 * Registers the fields to the UsersInsights users table, so that they are 
	 * available in the table and filters.
	 * @param  array $fields the default UsersInsights fields
	 * @return array         the default UsersInsights fields including the 
	 * custom user meta fields.
	 */
	public function register_fields($fields){
		$custom_fields = $this->get_custom_fields();
		
		if(!empty($custom_fields) && !empty($fields) && is_array($fields)){
			foreach ($custom_fields as $custom_field) {
				$order = $custom_field['type'] == 'text' ? 'ASC' : 'DESC';
				
				$field = array(
					'name' => $custom_field['name'],
					'id' => $this->prefix.$custom_field['key'],
					'order' => $order,
					'show' => true,
					'fieldType' => 'general',
					'filter' => array(
						'type' => $custom_field['type']
					),
					'icon' => 'custom-field'
				);
				
				if($custom_field['type'] != 'date'){
					$field['editable'] = array(
						'id' =>  $custom_field['key'],
						'location' => 'meta'
					);
				}
				
				$fields[]= $field;
			}
		}
		return $fields;
	}
	
	/**
	 * Registers a meta query for the custom user meta fields. This meta query will
	 * be responsible of generating the required database statements.
	 */
	protected function register_fields_query(){
		$custom_fields = $this->get_custom_fields();
		foreach ($custom_fields as $custom_field) {
			$query = new USIN_Meta_Query($custom_field['key'], $custom_field['type'], $this->prefix);
			$query->init();
		}
	}
	
	/**
	 * Retrieves the registered custom fields.
	 * @return array array containing the refgistered custom fields
	 */
	protected function get_custom_fields(){
		if(empty($this->custom_fields)){
			$this->custom_fields = USIN_Custom_Fields_Options::get_saved_fields();
		}
		return $this->custom_fields;
	}
	
}

new USIN_Custom_Fields();
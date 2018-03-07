<?php

if(!defined( 'ABSPATH' )){
	exit;
}

/**
 * Gravity Forms Module:
 * - loads the user data saved via the Gravity Forms User Registration Add-on
 * - loads data and provides filters about the completed by the users forms
 */
class USIN_Gravity_Forms{
	
	protected $gf_fields = array();
	protected $prefix = 'gf_';
	protected $module_name = 'gravityforms';
	protected $gfur;
	protected $is_user_reg_active = false;

	public function __construct(){
		add_filter('usin_module_options', array($this , 'register_module'));
		
		$this->is_user_reg_active = USIN_Helper::is_plugin_activated('gravityformsuserregistration/userregistration.php');

		if(USIN_Helper::is_plugin_activated('gravityforms/gravityforms.php')){
			add_action('admin_init', array($this, 'init'));
			add_filter('usin_fields', array($this , 'register_fields'));
		}
	}

	/**
	 * Initialize the main module functionality.
	 */
	public function init(){
		if(usin_module_options()->is_module_active('gravityforms')){
			require_once 'gravity-forms-user-registration.php';
			require_once 'gravity-forms-query.php';
			require_once 'gravity-forms-user-activity.php';
			
			$this->gf_query = new USIN_GF_Query();
			$this->gf_query->init();
			
			$gf_user_activity = new USIN_GF_User_Activity();
			$gf_user_activity->init();
			
			if($this->is_user_reg_active){
				$this->gfur = new USIN_Gravity_Forms_User_Registration($this->prefix);
				$this->gf_fields = $this->gfur->get_form_fields();
				$this->gf_query->init_meta_query($this->gf_fields, $this->prefix);
				add_filter('usin_user_db_data', array($this , 'filter_user_data'));
			}
		}
	}
	
	/**
	 * Registers the module.
	 */
	public function register_module($default_modules){
		if(!empty($default_modules) && is_array($default_modules)){
			$default_modules[]=array(
				'id' => $this->module_name,
				'name' => 'Gravity Forms',
				'desc' => __('Provides Gravity Forms related filters and data. Detects and displays the custom user data saved with the Gravity Forms User Registration Add-on.', 'usin'),
				'allow_deactivate' => true,
				'buttons' => array(
					array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/gravity-forms-list-search-filter-user-data/', 'target'=>'_blank')
				),
				'active' => false
			);
		}
		return $default_modules;
	}
	
	/**
	 * Registers the Gravity Form user fields
	 * @param  array $fields the default Users Insights fields
	 * @return array         the default Users Insights fields including the 
	 * Gravity Form fields
	 */
	public function register_fields($fields){
		if(!empty($fields) && is_array($fields)){
			
			$form_options = $this->get_form_options();

			$fields[]=array(
				'name' => __('Has completed form', 'usin'),
				'id' => 'has_completed_form',
				'order' => 'ASC',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'select_option',
					'options' => $form_options
				),
				'module' => $this->module_name
			);

			$fields[]=array(
				'name' => __('Has not completed form', 'usin'),
				'id' => 'has_not_completed_form',
				'order' => 'ASC',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'select_option',
					'options' => $form_options
				),
				'module' => $this->module_name
			);
			
			if($this->is_user_reg_active){
				//Gravity form user registration meta fields
				
				foreach ($this->gf_fields as $key => $field) {
					$field['id'] = $this->prefix.$field['id'];
					
					//do not add fields with existing keys
					$fields[]=array_merge(array(
						'order' => 'ASC',
						'show' => false,
						'fieldType' => 'general',
						'filter' => array(
							'type' => $field['type'],
						),
						'module' => $this->module_name
					), $field);
				}
			}
		}

		return $fields;
	}
	
	/**
	 * Filters the user data that is loaded from the database and applied to
	 * the user when creating a new user. Formats the JSON data to a string/
	 * @param  object $data the user DB data
	 * @return object       the DB data with unserialized values
	 */
	public function filter_user_data($data){
		$json_fields = $this->gfur->get_json_fields();
		
		if(!empty($json_fields)){
			$json_keys = array_unique($json_fields);
			foreach ($json_keys as $key ) {
				$key = $this->prefix.$key;
				if(!empty($data->$key)){
					$data->$key = $this->gfur->format_json_field_data($data->$key);
				}
			}
		}
		
		return $data;
	}
	
	protected function get_form_options(){
		$form_options = array();
		if(method_exists('GFAPI', 'get_forms')){
			$forms = GFAPI::get_forms();
			if(is_array($forms)){
				foreach ($forms as $form ) {
					$form_options[]=array('key'=>$form['id'], 'val'=>$form['title']);
				}
			}
		}
		
		return $form_options;
	}
	
}

new USIN_Gravity_Forms();
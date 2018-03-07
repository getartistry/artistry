<?php

if(!defined( 'ABSPATH' )){
	exit;
}

/**
 * Ultimate Member Module that loads the Ultimate member custom fields data
 */
class USIN_Ultimate_Member{
	
	protected $fields;
	protected $serialized_fields = array();
	protected $um_query;
	protected $prefix = 'um_';
	protected $module_id = 'ultimate-member';

	public function __construct(){
		add_filter('usin_module_options', array($this , 'register_module'));

		if(USIN_Helper::is_plugin_activated('ultimate-member/index.php') || USIN_Helper::is_plugin_activated('ultimate-member/ultimate-member.php')){
			add_action('admin_init', array($this, 'init'));
			add_filter('usin_fields', array($this , 'register_fields'), 30);
			add_filter('usin_user_db_data', array($this , 'filter_user_data'));
			add_filter('usin_user_actions', array($this, 'add_user_profile_button'), 20, 2);
		}
	}

	/**
	 * Initialize the main pluin functionality.
	 */
	public function init(){
		if($this->is_um_module_active()){
			
			require_once 'ultimate-member-query.php';
			require_once 'ultimate-member-field.php';

			$um_fields = $this->get_form_fields();
			$this->um_query = new USIN_UM_Query($um_fields, $this->prefix);
			$this->um_query->init();
			
		}
	}
	
	protected function is_um_module_active(){
		return usin_module_options()->is_module_active('ultimate-member');
	}

	/**
	 * Registers the module.
	 */
	public function register_module($default_modules){
		if(!empty($default_modules) && is_array($default_modules)){
			$default_modules[]=array(
				'id' => $this->module_id,
				'name' => 'Ultimate Member',
				'desc' => __('Detects and displays the custom user fields data generated with the Ultimate Member forms.', 'usin'),
				'allow_deactivate' => true,
				'buttons' => array(
					array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/ultimate-member-data-search-filters/', 'target'=>'_blank')
				),
				'active' => false
			);
		}
		return $default_modules;
	}

	/**
	 * Registers the Ultimate Member form fields
	 * @param  array $fields the default Users Insights fields
	 * @return array         the default Users Insights fields including the 
	 * Ultimate Member fields
	 */
	public function register_fields($fields){
		if($this->is_um_module_active() && is_array($fields)){
			//community role field
			if(self::is_um_older_than_v2()){
				$fields[]=$this->get_role_field_options();
			}
			
			//Ultimate Member form fields
			$form_fields = $this->get_form_fields();
			$fields = array_merge($fields, $form_fields);
		}

		return $fields;
	}

	public static function is_um_older_than_v2(){
		return defined('ultimatemember_version') && version_compare(ultimatemember_version, '2.0', '<');
	}
	
	/**
	 * Loads all of the registered Ultimate Member form fields.
	 * @return array containing the fields data, formatted as Users Insights fields
	 */
	protected function get_form_fields(){
		if(!isset($this->fields)){
			$forms = get_posts(array('post_type'=>'um_form', 'posts_per_page' => -1));
			$fields = array();
			
			foreach ($forms as $form ) {
				$custom_fields = get_post_meta($form->ID, '_um_custom_fields', true);
				
				foreach ($custom_fields as $field_id => $field_options) {
						
					$field = new USIN_UM_Field($field_options, $this->prefix, $this->module_id);
					$meta_key = $field->get_meta_key();
					
					if(!$field->should_be_ignored() && !isset($fields[$meta_key])){
						$fields[$meta_key] = $field->to_usin_field();
						
						if($field->is_field_data_serialized()){
							$this->serialized_fields[]=$meta_key;
						}
					}
						
				}
			}
			
			$this->fields = $fields;
		}
		
		return $this->fields;
	}
	
	protected function get_role_field_options(){
		$filter = array('type'=>'text');
		if(function_exists('um_get_roles')){
			//get the role options, so that the field filter can be a select type
			$roles = um_get_roles();
			if(!empty($roles) && is_array($roles)){
				$filter['type'] = 'select';
				$filter['options'] = $this->generate_options($roles);
				$filter['disallow_null'] = true;
			}
		}
		return array(
			'name' => __('Community Role', 'usin'),
			'id' => $this->prefix.'role',
			'order' => 'ASC',
			'show' => false,
			'fieldType' => 'general',
			'filter' => $filter,
			'module' => $this->module_id
		);
	}
	

	/**
	 * Filters the user data that is loaded from the database and applied to
	 * the user when creating a new user. Unserializes the serialized data fields.
	 * @param  object $data the user DB data
	 * @return object       the DB data with unserialized values
	 */
	public function filter_user_data($data){
		if($this->is_um_module_active()){
			$ser_keys = array_unique($this->serialized_fields);
			foreach ($ser_keys as $key ) {
				$key = $this->prefix.$key;
				if(isset($data->$key)){
					$val = maybe_unserialize($data->$key);
					$data->$key = is_array($val) ? implode(', ', $val) : $val;
				}
			}
		}
		return $data;
	}
	
	/**
	 * Filters the Users Insights user profile button actions to add
	 * a button to the Ultimate Member profile page.
	 * @param array $actions the default actions array
	 * @param int $user_id the user ID
	 */
	public function add_user_profile_button($actions, $user_id){
		if(function_exists('um_fetch_user') && function_exists('um_user_profile_url') &&
			function_exists('um_reset_user')){
				um_fetch_user($user_id);
				$profile_url = um_user_profile_url();
				$actions[]=array(
					'id'=>'ultimate-member',
					'name' => __('View Ultimate Member Profile', 'usin'),
					'link' => $profile_url
				);
				um_reset_user();
			}
			
		return $actions;
	}
	
	/**
	 * Generates options for a select field in the Users Insights field options
	 * format.
	 * @param  array $arr        the array containing the options
	 * @param  boolean $ignore_key when true, the array value will be used as a key
	 * @return array             the options formatted for the Users Insights
	 * field options
	 */
	protected function generate_options($arr, $ignore_key = false){
		$options = array();
		
		foreach ($arr as $key => $value) {
			$k = $ignore_key ? $value : $key;
			$options[]= array('key'=>$k, 'val'=>$value);
		}
		return $options;
	}
	
	
}

new USIN_Ultimate_Member();
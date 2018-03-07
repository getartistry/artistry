<?php

abstract class USIN_Plugin_Module{
	
	protected $module_name;
	protected $plugin_path;
	
	abstract public function register_module();
	abstract public function register_fields();
	abstract public function init();
	
	public function __construct(){
		add_filter('usin_module_options', array($this , 'add_to_module_options'));

		if($this->is_plugin_active()){
			add_action('admin_init', array($this, 'init_module'));
			add_filter('usin_fields', array($this , 'add_module_fields'));
			$this->apply_module_actions();
		}
	}
	
	public function add_to_module_options($default_modules){
		if(!empty($default_modules) && is_array($default_modules)){
			$module = $this->register_module();
			if(!empty($module) && is_array($module)){
				$default_modules[]=$module;
			}
		}
		return $default_modules;
	}
	
	public function add_module_fields($fields){
		if($this->is_module_active()){
			$module_fields = $this->register_fields();
			if(is_array($module_fields) && !empty($module_fields)){
				$fields = array_merge($fields, $module_fields);
			}
		}
		return $fields;
	}
	
	public function init_module(){
		if($this->is_module_active()){
			$this->init();
		}
	}
	
	/**
	 * Optional function that can be overwritten to apply other custom actions
	 */
	protected function apply_module_actions(){}
	
	protected function is_plugin_active(){
		return USIN_Helper::is_plugin_activated($this->plugin_path);
	}
	
	protected function is_module_active(){
		return usin_module_options()->is_module_active($this->module_name);
	}
	
}
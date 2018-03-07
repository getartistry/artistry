<?php

if(!defined( 'ABSPATH' )){
	exit;
}

class USIN_bbPress{

	public function __construct(){
		add_filter('usin_module_options', array($this , 'register_module'));

		if(USIN_Helper::is_plugin_activated('bbpress/bbpress.php')){
			add_action('admin_init', array($this, 'init'));
			add_filter('usin_fields', array($this , 'register_fields'));
		}
	}

	public function init(){
		if(usin_module_options()->is_module_active('bbpress')){
			
			require_once 'bbpress-query.php';
			require_once 'bbpress-user-activity.php';

			$bb_query = new USIN_bbPress_Query();
			$bb_query->init();

			$bb_user_activity = new USIN_bbPress_User_Activity();
			$bb_user_activity->init();
			
		}
	}

	public function register_module($default_modules){
		if(!empty($default_modules) && is_array($default_modules)){
			$default_modules[]=array(
				'id' => 'bbpress',
				'name' => 'bbPress',
				'desc' => __('Retrieves and displays data about the users activity in the bbPress forums.', 'usin'),
				'allow_deactivate' => true,
				'buttons' => array(
					array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/bbpress-users-data/', 'target'=>'_blank')
				),
				'active' => false
			);
		}
		return $default_modules;
	}

	public function register_fields($fields){
		if(!empty($fields) && is_array($fields)){

			$fields[]=array(
				'name' => __('Forums', 'usin'),
				'id' => 'forums',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'bbpress',
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => 'bbpress'
			);

			$fields[]=array(
				'name' => __('Topics', 'usin'),
				'id' => 'topics',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'bbpress',
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => 'bbpress'
			);

			$fields[]=array(
				'name' => __('Replies', 'usin'),
				'id' => 'replies',
				'order' => 'ASC',
				'show' => true,
				'fieldType' => 'bbpress',
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => 'bbpress'
			);

		}

		return $fields;
	}
	
}

new USIN_bbPress();
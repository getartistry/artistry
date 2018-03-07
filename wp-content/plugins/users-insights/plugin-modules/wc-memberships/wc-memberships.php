<?php

if(!defined( 'ABSPATH' )){
	exit;
}


class USIN_WC_Memberships extends USIN_Plugin_Module{
	
	protected $module_name = 'wc-memberships';
	protected $plugin_path = 'woocommerce-memberships/woocommerce-memberships.php';
	protected $post_type = 'wc_user_membership';
	protected static $statuses = null;

	
	protected function apply_module_actions(){
		add_filter('usin_exclude_post_types', array($this, 'exclude_post_types'));
	}
	

	public function init(){
		require_once 'wc-memberships-query.php';
		require_once 'wc-memberships-user-activity.php';
		
		new USIN_WC_Memberships_Query($this->post_type);
		new USIN_WC_Memberships_User_Activity($this->post_type);
	}


	public function register_module(){
		return array(
			'id' => $this->module_name,
			'name' => __('WooCommerce Memberships', 'usin'),
			'desc' => __('Retrieves and displays the data from the WooCommerce Memberships extension.', 'usin'),
			'allow_deactivate' => true,
			'buttons' => array(
				array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/woocommerce-memberships-search-filter-user-data/', 'target'=>'_blank')
			),
			'active' => false
		);
	}

	public function register_fields(){
		return array(
			array(
				'name' => __('Memberships', 'usin'),
				'id' => 'membership_num',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'icon' => 'woocommerce',
				'module' => $this->module_name
			),
			
			array(
				'name' => __('Member since', 'usin'),
				'id' => 'member_since',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'date'
				),
				'icon' => 'woocommerce',
				'module' => $this->module_name
			),
			
			array(
				'name' => __('Membership statuses', 'usin'),
				'id' => 'membership_statuses',
				'order' => 'ASC',
				'show' => false,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'include_exclude',
					'options' => $this->get_status_options()
				),
				'icon' => 'woocommerce',
				'module' => $this->module_name
			),
			
			array(
				'name' => __('Membership plans', 'usin'),
				'id' => 'has_membership_plan',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'include_exclude',
					'options' => $this->get_membership_plans()
				),
				'icon' => 'woocommerce',
				'module' => $this->module_name
			)
		);
	}
	
	public static function get_statuses(){
		if(self::$statuses === null){
			//load the statuses
			if(function_exists('wc_memberships_get_user_membership_statuses')){
				self::$statuses = wc_memberships_get_user_membership_statuses();
			}else{
				self::$statuses = array();
			}
		}
		return self::$statuses;
	}
	
	protected function get_status_options(){
		$status_options = array();
		
		$wcm_statuses = self::get_statuses();
		foreach ($wcm_statuses as $status_key => $status) {
			$status_options[]= array('key'=>$status_key, 'val'=>$status['label']);
		}
			
		return $status_options;
	}
	
	protected function get_membership_plans(){
		$plan_options = array();
		if(function_exists('wc_memberships_get_membership_plans')){
			$wcm_plans = wc_memberships_get_membership_plans();
			foreach ($wcm_plans as $plan) {
				$plan_options[]= array('key'=>$plan->id, 'val'=>$plan->name);
			}
			
		}
		return $plan_options;
	}
	
	
	public function exclude_post_types($exclude){
		$exclude[]=$this->post_type;
		return $exclude;
	}
	
	/**
	 * Check if the WooCommerce Subscriptions AND WooCommerce are active
	 * @return boolean [description]
	 */
	protected function is_plugin_active(){
		return parent::is_plugin_active() && USIN_Helper::is_plugin_activated('woocommerce/woocommerce.php'); 
	}
	
}

new USIN_WC_Memberships();
<?php

if(!defined( 'ABSPATH' )){
	exit;
}

/**
 * Easy Digital Downloads module - retrieves and displays data from the Easy 
 * Digital Downloads orders made by the WordPress users
 */
class USIN_EDD{

	protected $product_post_type = 'download';
	protected $order_post_type = 'edd_payment';
	public $edd_query;
	public $edd_user_activity;
	
	/**
	 * Constructor - registers the filters and actions hooks.
	 */
	public function __construct(){
		add_filter('usin_module_options', array($this , 'register_module'));
		if(USIN_Helper::is_plugin_activated('easy-digital-downloads/easy-digital-downloads.php')){
			add_action('admin_init', array($this, 'init'));
			add_filter('usin_fields', array($this , 'register_fields'));
			add_filter('usin_exclude_comment_types', array($this , 'exclude_edd_private_comment_types'));
		}
	}
	
	/**
	 * Initalizes the EDD Query and User Activity functionality.
	 */
	public function init(){
		if(usin_module_options()->is_module_active('edd')){
			add_filter('usin_exclude_post_types', array($this , 'exclude_post_types'));
			require_once 'edd-query.php';
			require_once 'edd-user-activity.php';
			
			$this->edd_query = new USIN_EDD_Query($this->order_post_type);
			$this->edd_query->init();
			
			$this->edd_user_activity = new USIN_EDD_User_Activity($this->order_post_type, $this->product_post_type);
			$this->edd_user_activity->init();
		}
	}

	/**
	 * Registers the EDD Module by filtering the default module options.
	 * @param  array $default_modules the default modules array
	 * @return array                  the default modules, including the EDD module
	 */
	public function register_module($default_modules){
		if(!empty($default_modules) && is_array($default_modules)){
			$default_modules[]=array(
				'id' => 'edd',
				'name' => __('Easy Digital Downloads', 'usin'),
				'desc' => __('Retrieves and displays data from the Easy Digital Downloads orders made by the WordPress users.', 'usin'),
				'allow_deactivate' => true,
				'buttons' => array(
					array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/easy-digital-downloads-users-data/', 'target'=>'_blank')
				),
				'active' => false
			);
		}
		return $default_modules;
	}
	
	/**
	 * Registers the additional EDD fields.
	 * @param  array $fields the default Users Insights table fields 
	 * @return array         the default fields including the EDD fields
	 */
	public function register_fields($fields){
		if(!empty($fields) && is_array($fields)){

			$fields[]=array(
				'name' => __('Orders', 'usin'),
				'id' => 'edd_order_num',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'edd',
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => 'edd'
			);
			
			$fields[]=array(
				'name' => __('Lifetime Value', 'usin'),
				'id' => 'edd_total_spent',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => 'edd'
			);
			
			
			$fields[]=array(
				'name' => __('Last Order Date', 'usin'),
				'id' => 'edd_last_order',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'edd',
				'filter' => array(
					'type' => 'date'
				),
				'module' => 'edd'
			);

			$fields[]=array(
				'name' => __('Ordered Products', 'usin'),
				'id' => 'edd_has_ordered',
				'order' => 'ASC',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => 'edd',
				'filter' => array(
					'type' => 'include_exclude',
					'options' => $this->get_product_options()
				),
				'module' => 'edd'
			);
			
			$fields[]=array(
				'name' => __('Orders Status', 'usin'),
				'id' => 'edd_has_order_status',
				'order' => 'ASC',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => 'edd',
				'filter' => array(
					'type' => 'include_exclude',
					'options' => $this->get_order_status_options()
				),
				'module' => 'edd'
			);

		}

		return $fields;
	}
	
	/**
	 * Loads the EDD product list.
	 * @return array the product list
	 */
	protected function get_product_options(){
		$product_options = array();
		$products = get_posts( array( 'post_type' => $this->product_post_type, 'posts_per_page' => -1 ) );

		foreach ($products as $product) {
			$product_options[] = array('key'=>$product->ID, 'val'=>$product->post_title);
		}

		return $product_options;
	}
	
	/**
	 * Loads the registered EDD statuses.
	 * @return array the statuses list
	 */
	protected function get_order_status_options(){
		$status_options = array();
	
		if(function_exists('edd_get_payment_statuses')){
			$edd_statuses = edd_get_payment_statuses();
			if(!empty($edd_statuses)){
				foreach ($edd_statuses as $key => $value) {
					$status_options[]= array('key'=>$key, 'val'=>$value);
				}
			}
		}
	
		return $status_options;
	}

	/**
	 * Excludes the EDD custom post types from the "Posts Created" field in the query
	 * @param  array $exclude the default posts types to exclude
	 * @return array          the default post types to exclude merged with the 
	 * EDD custom post types
	 */
	public function exclude_post_types($exclude){
		return array_merge ($exclude,  array('edd_log','edd_payment','edd_discount'));
	}
	
	public function exclude_edd_private_comment_types($exclude){
		return array_merge ($exclude,  array('edd_payment_note'));
	}
}

new USIN_EDD();
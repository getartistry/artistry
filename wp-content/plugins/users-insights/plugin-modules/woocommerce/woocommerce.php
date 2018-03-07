<?php

if(!defined( 'ABSPATH' )){
	exit;
}

class USIN_Woocommerce extends USIN_Plugin_Module{

	protected $module_name = 'woocommerce';
	protected $plugin_path = 'woocommerce/woocommerce.php';
	protected $order_post_type = 'shop_order';

	protected function apply_module_actions(){
		add_filter('usin_exclude_post_types', array($this , 'exclude_post_types'));
	}

	public function init(){
		require_once 'woocommerce-query.php';
		require_once 'woocommerce-user-activity.php';

		$this->wc_query = new USIN_Woocommerce_Query($this->order_post_type);
		$this->wc_query->init();

		$wc_user_activity = new USIN_Woocommerce_User_Activity($this->order_post_type);
		$wc_user_activity->init();
	}

	public function register_module(){
		return array(
			'id' => $this->module_name,
			'name' => __('WooCommerce', 'usin'),
			'desc' => __('Retrieves and displays data from the WooCommerce orders made by the WordPress users.', 'usin'),
			'allow_deactivate' => true,
			'buttons' => array(
				array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/woocommerce-users-data/', 'target'=>'_blank')
			),
			'active' => false
		);
	}

	public function register_fields(){
		return array(
			array(
				'name' => __('Orders', 'usin'),
				'id' => 'order_num',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => $this->module_name
			),
			array(
				'name' => __('Last order', 'usin'),
				'id' => 'last_order',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'date'
				),
				'module' => $this->module_name
			),
			array(
				'name' => __('Lifetime Value', 'usin'),
				'id' => 'lifetime_value',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'general',
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => $this->module_name
			),
			array(
				'name' => __('Ordered products', 'usin'),
				'id' => 'has_ordered',
				'order' => 'ASC',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'include_exclude',
					'options' => $this->get_product_options()
				),
				'module' => $this->module_name
			),
			array(
				'name' => __('Orders status', 'usin'),
				'id' => 'has_order_status',
				'order' => 'ASC',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'include_exclude',
					'options' => $this->get_order_status_options()
				),
				'module' => $this->module_name
			),
			array(
				'name' => __('Reviews', 'usin'),
				'id' => 'reviews',
				'order' => 'DESC',
				'show' => false,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'number',
					'disallow_null' => true
				),
				'module' => $this->module_name
			),
			array(
				'name' => __('Has used coupon', 'usin'),
				'id' => 'has_used_coupon',
				'show' => false,
				'hideOnTable' => true,
				'fieldType' => $this->module_name,
				'filter' => array(
					'type' => 'select_option',
					'options' => $this->get_coupon_options()
				),
				'module' => $this->module_name
			),
		);
	}

	protected function get_product_options(){
		$product_options = array();
		$products = get_posts( array( 'post_type' => 'product', 'posts_per_page' => -1 ) );

		foreach ($products as $product) {
			$product_options[] = array('key'=>$product->ID, 'val'=>$product->post_title);
		}

		return $product_options;
	}

	protected function get_order_status_options(){
		$status_options = array();

		if(function_exists('wc_get_order_statuses')){
			$wc_statuses = wc_get_order_statuses();
			if(!empty($wc_statuses)){
				foreach ($wc_statuses as $key => $value) {
					$status_options[]= array('key'=>$key, 'val'=>$value);
				}
			}
		}

		return $status_options;
	}
	
	protected function get_coupon_options(){
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'shop_coupon'
		);

		$coupons = get_posts( $args );
		$coupon_options = array();
		
		foreach ($coupons as $coupon ) {
			$coupon_options[]=array('key'=>strtolower($coupon->post_title), 'val'=>$coupon->post_title);
		}
		
		return $coupon_options;
		
	}

	public function exclude_post_types($exclude){
		return array_merge ($exclude,  array('shop_order','shop_order_refund','shop_coupon','shop_webhook', 'product_variation'));
	}
	
}

new USIN_Woocommerce();
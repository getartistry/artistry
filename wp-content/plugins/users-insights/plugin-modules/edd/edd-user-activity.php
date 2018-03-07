<?php

/**
 * Includes the User Activity functionality for the EDD module.
 */
class USIN_EDD_User_Activity{
	
	protected $order_post_type;
	protected $product_post_type;
	protected $payment_page_slug = 'edd-payment-history';

	/**
	 * @param string $order_post_type   the order(payment) post type
	 * @param string $product_post_type the product(download) post type
	 */
	public function __construct($order_post_type, $product_post_type){
		$this->order_post_type = $order_post_type;
		$this->product_post_type = $product_post_type;
	}
	
	/**
	 * Registers the required filter and action hooks.
	 */
	public function init(){
		add_filter('usin_user_activity', array($this, 'add_orders_to_user_activity'), 10, 2);
		add_action('usin_user_profile_data', array($this, 'filter_profile_data'));
	}
	
	/**
	 * Adds the EDD order list to the user activity.
	 * @param array $activity the default user activity data 
	 * @param int $user_id  the ID of the user
	 * @return array the default user activity including the EDD order list
	 */
	public function add_orders_to_user_activity($activity, $user_id){
		if(function_exists('edd_get_payments')){
			$orders = edd_get_payments(array(
				'user' => $user_id, 
				'orderby'=>'date', 
				'order'=>'DESC', 
				'number'=>-1, 
				'nopaging'=>true
			));
			$count = sizeof($orders);
			
			if(!empty($orders)){
				$list = array();
				$min = min($count, 5);
				for ($i = 0; $i < $min; $i++) {
					//load the last several orders only
					
					$order = $orders[$i];
					$title = get_the_date( get_option('date_format'), $order->ID);
					
					$order_status = '';
					if(function_exists('edd_get_payment_status')){
						$status = edd_get_payment_status($order, true);
						$title.= sprintf('<span class="usin-tag usin-tag-%s">%s</span>',
							sanitize_key($status), $status);
						
					}
					
					if(function_exists('edd_get_payment_meta_downloads') &&
						function_exists('edd_get_download')){
						//get the names of the products ordered
						$order_items = edd_get_payment_meta_downloads($order->ID);
						$details = array();
						
						foreach ($order_items as $item) {
							$download = edd_get_download($item['id']);
							$details[]= $download->post_title;
						}
					}
					
					$order_info = array('title'=>$title, 'link'=>get_edit_post_link( $order->ID, ''));
					if(!empty($details)){
						$order_info['details'] = $details;
					}
					$list[]=$order_info;
				}

				$post_type_data = get_post_type_object($this->order_post_type);
				
				$user = get_user_by('id', $user_id);
				$activity[] = array(
					'type' => 'edd_order',
					'for' => $this->order_post_type,
					'label' => _n('Order', 'Orders', $count, 'usin'),
					'count' => $count,
					'link' => admin_url(sprintf('edit.php?post_type=%s&page=%s&user=%s', 
						$this->product_post_type, 
						$this->payment_page_slug, 
						$user->user_email)),
					'list' => $list,
					'icon' => 'edd'
				);
			}
		}
		
		return $activity;
	}
	
	/**
	 * Filters the user profile data - formats the total spent field to include 
	 * the currency.
	 * @param  USIN_User $user the user object
	 * @return USIN_User       the modified user object
	 */
	public function filter_profile_data($user){
		if(isset($user->edd_total_spent) && function_exists('edd_currency_filter')){
			$user->edd_total_spent = html_entity_decode(edd_currency_filter($user->edd_total_spent));
		}
		return $user;
	}
	
	
}
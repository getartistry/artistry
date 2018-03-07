<?php

class USIN_WC_Subscriptions_User_Activity{

	protected $post_type;

	public function __construct($post_type){
		$this->post_type = $post_type;
		$this->init();
	}

	public function init(){
		add_filter('usin_user_activity', array($this, 'add_subscriptions_to_user_activity'), 10, 2);
		add_action('pre_get_posts', array($this, 'admin_subscriptions_filter'));
	}
	
	public function add_subscriptions_to_user_activity($activity, $user_id){

		$args = array(
			'meta_key'    => '_customer_user',
			'meta_value'  => $user_id,
			'post_type'   => $this->post_type,
			'post_status' => 'any',
			'numberposts'=>-1
		);

		$all_subscriptions = get_posts($args);
		$count = sizeof($all_subscriptions);

		$args['numberposts'] = 5;
		$subscriptions = get_posts($args);


		if(!empty($subscriptions)){
			$list = array();
			foreach ($subscriptions as $subscription) {
				$title = '';

				if(class_exists('WC_Subscription')){
					$wc_subscription = new WC_Subscription($subscription->ID);
					
					//get the date
					if(method_exists($wc_subscription, 'get_date')){
						$title .= USIN_Helper::format_date($wc_subscription->get_date('start'));
					}
					
					//get the status
					if(method_exists($wc_subscription, 'get_status') && function_exists('wcs_get_subscription_status_name')){
						$status = $wc_subscription->get_status();
						$title .= sprintf('<span class="usin-tag usin-tag-%s">%s</span>',
							$status, wcs_get_subscription_status_name($status));
					}

					
					//get the items
					if(method_exists($wc_subscription, 'get_items')){
						$subscription_items = $wc_subscription->get_items();
						
						if(!empty($subscription_items) && is_array($subscription_items)){
							$details = array_values(wp_list_pluck($subscription_items, 'name'));
						}
					}
					
				}
				
				$subscription_info = array('title'=>$title, 'link'=>get_edit_post_link( $subscription->ID, ''));
				if(!empty($details)){
					$subscription_info['details'] = $details;
				}
				
				$list[]=$subscription_info;
			}

			$post_type_data = get_post_type_object($this->post_type);

			$activity[] = array(
				'type' => 'subscription',
				'for' => $this->post_type,
				'label' => $count == 1 ? $post_type_data->labels->singular_name : $post_type_data->labels->name,
				'count' => $count,
				'link' => admin_url('edit.php?post_type='.$this->post_type.'&usin_customer='.$user_id),
				'list' => $list,
				'icon' => 'woocommerce'
			);
		}
		
		return $activity;
	}


	public function admin_subscriptions_filter($query){
		if( is_admin() && isset($_GET['usin_customer']) && $query->get('post_type') == $this->post_type){
			$user_id = intval($_GET['usin_customer']);

			if($user_id){
				$query->set('meta_key', '_customer_user');
				$query->set('meta_value', $user_id);
			}
		}
	}
	
}
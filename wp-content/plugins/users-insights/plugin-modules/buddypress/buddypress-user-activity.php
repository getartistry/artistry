<?php

class USIN_BuddyPress_User_Activity{
	
	protected $module_name = 'buddypress';

	public function init(){
		add_filter('usin_user_activity', array($this, 'filter_user_activity'), 10, 2);
		add_filter('usin_user_actions', array($this, 'filter_user_actions'), 10, 2);
		add_filter('groups_get_groups', array($this, 'filter_group_by_user'), 10, 2);
		add_action('pre_get_users', array($this, 'filter_friend_users'));
		add_filter('bp_activity_get', array($this, 'filter_activity_by_user'), 10, 2);
	}

	protected function is_bp_feature_active($feature){
		return USIN_BuddyPress::is_bp_feature_active($feature);
	}


	/**
	 * Filter the user activity to setup the Replies lists load the topic title.
	 * @param  array $activity the user activity to be filtered
	 * @param  int $user_id  the ID of the user
	 * @return array           the filtered activity
	 */
	public function filter_user_activity($activity, $user_id){

		if($this->is_bp_feature_active('friends')){
			//load the friends list
			$friends_activity = $this->get_friends_activity($user_id);
			if(!empty($friends_activity)){
				$activity[]= $friends_activity;
			}
		}

		if($this->is_bp_feature_active('activity')){
			//load the activity updates list
			$activity_updates = $this->get_activity_updates($user_id);
			if(!empty($activity_updates)){
				$activity[]= $activity_updates;
			}
		}

		
		if($this->is_bp_feature_active('groups')){

			//load the groups that the user belongs to
			$groups_activity = $this->get_groups_activity($user_id);
			if(!empty($groups_activity)){
				$activity[]= $groups_activity;
			}

			//load the groups that the user has created
			$groups_created_activity = $this->get_groups_created_activity($user_id);
			if(!empty($groups_created_activity)){
				$activity[]= $groups_created_activity;
			}
		}

		return $activity;
	}


	protected function get_friends_activity($user_id){
		if(function_exists('friends_get_friend_user_ids')){
			$friends_ids = friends_get_friend_user_ids($user_id);
			$count = sizeof($friends_ids);

			if($count > 0){
				$list = array();
				$len = min(5, $count);
				for ($i = 0; $i < $len; $i++) {
					$friend_id = (int)$friends_ids[$i];
					$friend = get_user_by('id', $friend_id);
					if($friend){
						$link = function_exists('bp_core_get_user_domain') ?
							bp_core_get_user_domain($friend_id) : '';
						$list[]=array(
							'title' => $friend->user_nicename,
							'link' => $link
						);
					}

				}

				return array(
					'type' => 'friends',
					'label' => $count === 1 ? 
						__('Friend', 'usin') : __('Friends', 'usin'),
					'count' => $count,
					'link' => admin_url('users.php?usin_friends_of='.$user_id),
					'list' => $list,
					'icon' => $this->module_name
				);
			}
		}

		return null;
	}


	protected function get_activity_updates($user_id){
		if(function_exists('bp_activity_get')){
			$activity_updates = bp_activity_get(array(
				'show_hidden' => true,
				'per_page' => 5,
				'page' => 1,
				'count_total' => true,
				'filter' => array(
					'user_id' => $user_id,
					'action' => 'activity_update'
					)
				));

			if(!empty($activity_updates) && isset($activity_updates['activities'])
				&& isset($activity_updates['total'])){
				$count = (int)$activity_updates['total'];

				if($count){
					$list = array();
					foreach ($activity_updates['activities'] as $a) {

						$link = function_exists('bp_activity_get_permalink') ?
							bp_activity_get_permalink((int)$a->id) : '';

						$list[]=array(
							'title' => stripslashes(wp_html_excerpt($a->content, 130, '[...]')),
							'link' => $link
						);
					}

					return array(
						'type' => 'activity_updates',
						'label' => $count === 1 ? 
							__('Activity Update', 'usin') : __('Activity Updates', 'usin'),
						'count' => $count,
						'link' => admin_url('admin.php?page=bp-activity&usin_user='.$user_id),
						'list' => $list,
						'icon' => $this->module_name
					);
				}

			}

		}

		return null;
	}

	/**
	 * Loads the groups that the user belongs to
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	protected function get_groups_activity($user_id){
		if(function_exists('groups_get_groups')){
			$groups = groups_get_groups(array(
				'user_id'=>$user_id, 
				'per_page'=>5,
				'show_hidden'=>true
			));
			$list = array();

			
			if(isset($groups['groups'])){
				foreach ($groups['groups'] as $group) {
					$list[]=$this->get_group_info($group);
				}
			}

			$count = 0;
			if(function_exists('groups_total_groups_for_user')){
				$count = groups_total_groups_for_user($user_id);
			}

			if(!empty($list)){
				return array(
					'type' => 'groups',
					'label' => $count === 1 ? 
						__('Member of 1 Group', 'usin') :
						sprintf(__('Member of %d Groups', 'usin'), $count),
					'hide_count' => true,
					'count' => $count,
					'link' => admin_url('?page=bp-groups&usin_user='.$user_id),
					'list' => $list,
					'icon' => $this->module_name
				);
			}
		}

		return null;
	}

	/**
	 * Loads the groups that the user has created
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	protected function get_groups_created_activity($user_id){
		global $wpdb;

		$prefix = is_multisite() ? $wpdb->base_prefix : $wpdb->prefix;
		$groups = $wpdb->get_results(
			$wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM ".$prefix."bp_groups ".
			"WHERE creator_id = %d ORDER BY date_created DESC", $user_id));

		$count = $wpdb->get_var( 'SELECT FOUND_ROWS()' );
		$list = array();

		if($count > 0){
			foreach($groups as $group){
				$list[]=$this->get_group_info($group);
			}

			return array(
				'type' => 'groups_created',
				'label' => $count == 1 ? 
					__('Group Created', 'usin') :
					__('Groups Created', 'usin'),
				'count' => $count,
				'list' => $list,
				'icon' => $this->module_name
			);
		}

		return null;
	}


	protected function get_group_info($group){
		$link = function_exists('bp_get_group_permalink') ? 
							bp_get_group_permalink($group) : '';
						
		return array(
			'title' => stripslashes($group->name),
			'link'=> $link
		);
	}


	public function filter_user_actions($actions, $user_id){
		if(function_exists('bp_core_get_user_domain')){
			$actions[]=array(
				'id'=>'view-bp-profile',
				'name' => __('View BuddyPress Profile', 'usin'),
				'link' => bp_core_get_user_domain($user_id)
				);
		}

		return $actions;
	}

	public function filter_group_by_user($groups, $args){
		if(is_admin()){
			if(isset($_GET['usin_user'])){

				$user_id = (int)$_GET['usin_user'];

				if($user_id && method_exists('BP_Groups_Group', 'get')){
					$args['user_id'] = $user_id;
					return BP_Groups_Group::get($args);
				}

			}
		}

		return $groups;
	}

	public function filter_activity_by_user($activity, $args){
		if(is_admin()){
			if(isset($_GET['usin_user'])){

				$user_id = (int)$_GET['usin_user'];

				if($user_id && method_exists('BP_Activity_Activity', 'get')){
					if(empty($args['filter'])){
						$args['filter'] = array();
					}

					$args['filter']['user_id'] = $user_id;
					$args['filter']['action'] = 'activity_update';

					return BP_Activity_Activity::get($args);
				}

			}
		}

		return $activity;
	}

	public function filter_friend_users($query){
		if(is_admin() && isset($_GET['usin_friends_of'])){
			$user_id = (int)$_GET['usin_friends_of'];

			if($user_id && function_exists('friends_get_friend_user_ids')){
				$friends_ids = friends_get_friend_user_ids($user_id);

				if(!empty($friends_ids)){
					//convert the IDs from strings to integers
					foreach ($friends_ids as $key => $friend_id) {
						$friends_ids[$key] = (int)$friend_id;
					}

					$query->set('include', $friends_ids);
				}
			}
		}
	}

}

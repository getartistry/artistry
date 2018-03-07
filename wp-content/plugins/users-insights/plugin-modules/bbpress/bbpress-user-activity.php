<?php

class USIN_bbPress_User_Activity{

	public function init(){
		add_filter('usin_user_activity', array($this, 'filter_user_activity'), 10, 2);
	}

	/**
	 * Filter the user activity to setup the Replies lists load the topic title.
	 * @param  array $activity the user activity to be filtered
	 * @param  int $user_id  the ID of the user
	 * @return array           the filtered activity
	 */
	public function filter_user_activity($activity, $user_id){
		foreach ($activity as &$item) {
			if(isset($item['type']) && $item['type'] == 'reply'){
				$replies = get_posts(array('author'=>$user_id, 'post_type'=>'reply', 
						'posts_per_page'=>5, 'orderby'=>'date', 'order'=>'desc', 'post_status'=>array('publish', 'private')));
					$list = array();

				foreach ($replies as $reply) {
					$title = function_exists('bbp_get_reply_topic_title') ? 
						__('Reply to: ', 'usin').bbp_get_reply_topic_title($reply->ID) : __('Topic reply', 'usin');
					$list[]=array('title'=>$title, 'link'=>get_permalink($reply->ID));
				}

				$item['list'] = $list;
			}
			
			if(isset($item['type']) && in_array($item['type'], array('reply', 'topic', 'forum'))){
				$item['icon'] = 'bbpress';
			}
		}

		return $activity;
	}

}
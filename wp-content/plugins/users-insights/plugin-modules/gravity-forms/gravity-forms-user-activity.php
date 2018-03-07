<?php

class USIN_GF_User_Activity{
	
	public function init(){
		add_filter('usin_user_activity', array($this, 'filter_user_activity'), 10, 2);
	}

	/**
	 * Filter the user activity to add the Gravity Forms Entry list
	 * @param  array $activity the user activity to be filtered
	 * @param  int $user_id  the ID of the user
	 * @return array           the filtered activity
	 */
	public function filter_user_activity($activity, $user_id){
		
		
		
		if(method_exists('GFAPI', 'get_forms') && method_exists('GFAPI', 'get_entries')){
			
			$forms = GFAPI::get_forms();
			if(is_array($forms)){
				foreach ($forms as $form ) {
					
					$list = array();
					$count = 0;
					$search_criteria = array('field_filters' => array(array( 'key' => 'created_by', 'value' => $user_id )));
					$paging = array( 'offset' => 0, 'page_size' => 5 );
					$entries = GFAPI::get_entries( $form['id'] , $search_criteria, null, $paging, $count);
					
					if($count > 0){
						
						foreach ($entries as $entry ) {
							$title = USIN_Helper::format_date($entry['date_created']);
							$link = add_query_arg(array('page'=>'gf_entries', 'view'=>'entry', 'id'=>$form['id'], 'lid'=>$entry['id']), admin_url('admin.php'));
							$list[]=array('title'=>$title, 'link'=>$link);
						}
						
						$activity[] = array(
							'type' => 'gf_entries',
							'for' => 'gf_entries',
							'label' => sprintf(_n('%s Entry', '%s Entries', $count, 'usin'), $form['title']),
							'count' => $count,
							'list' => $list,
							'link' => add_query_arg(array('page'=>'gf_entries', 'id'=>$form['id'], 's'=> $user_id, 'field_id' => 'created_by', 'operator' => 'is'), admin_url('admin.php')),
							'icon' => 'gravityforms'
						);
					}
					
				}
			}
			
			
		}
		

		return $activity;
	}
}
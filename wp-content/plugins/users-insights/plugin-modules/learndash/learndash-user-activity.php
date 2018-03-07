<?php

/**
 * Adds the LearnDash user activity to the user profile section
 */
class USIN_LearnDash_User_Activity{
	
	protected $module_name;

	public function __construct($module_name){
		$this->module_name = $module_name;
		$this->init();
	}

	public function init(){
		add_filter('usin_user_activity', array($this, 'add_leardash_activity'), 10, 2);
	}
	
	public function add_leardash_activity($activity, $user_id){

		$course_activity = $this->get_course_activity($user_id);
		if(!empty($course_activity)){
			$activity[]= $course_activity;
		}
		
		$quiz_activity = $this->get_quiz_activity($user_id);
		if(!empty($quiz_activity)){
			$activity[]= $quiz_activity;
		}
		
		$group_activity = $this->get_group_activity($user_id);
		if(!empty($group_activity)){
			$activity[]= $group_activity;
		}
		
		return $activity;
	}
	
	
	/**
	 * Adds the course activity with progress info
	 * @param  int $user_id the ID of the user
	 * @return array          The original user activity including the course activity
	 */
	protected function get_course_activity($user_id){
		$activity = array();
		
		if(function_exists('ld_get_mycourses')){
			$course_ids = ld_get_mycourses($user_id);
			
			if(!empty($course_ids)){
				
				$list = array();
				$count = sizeof($course_ids);
				
				foreach ($course_ids as $course_id ) {
					$course = get_post( $course_id);
					$title = $course->post_title;
					$details = array();
					
					if(function_exists('learndash_course_progress')){
						$progress = learndash_course_progress( array(
	                        'user_id'   => $user_id,
	                        'course_id' => $course_id,
	                        'array'     => true
	                    ) );
						
						if(isset($progress['percentage'])){
							$title .= USIN_Templates::progress_tag($progress['percentage']);
						}
						
					}
                    
					$course_info = array(
						'title' => $title,
						'link' => get_permalink( $course_id )
					);
					
					$list[]=$course_info;
				}
			}
			
			
			$activity = array(
				'type' => 'ld_courses',
				'for' => 'ld_courses',
				'label' => $count == 1 ? USIN_LearnDash::get_label('course') : USIN_LearnDash::get_label('courses'),
				'count' => $count,
				'list' => $list,
				'icon' => $this->module_name
			);
		}
		
		return $activity;
	}
	
	/**
	 * Adds the quiz activity with passed percentage info
	 * @param  int $user_id the ID of the user
	 * @return array          The original user activity including the quiz activity
	 */
	protected function get_quiz_activity($user_id){
		$activity = array();
		
		$quiz_attempts = get_user_meta( $user_id, '_sfwd-quizzes', true );
		
		if(!empty($quiz_attempts) && is_array($quiz_attempts)){
			$count = sizeof($quiz_attempts);
			$list = array();
			
			foreach ($quiz_attempts as $quiz_attempt ) {
				$quiz = get_post( $quiz_attempt['quiz'] );
				$title = $quiz->post_title;
				
				$percentage = ! empty( $quiz_attempt['percentage'] ) ? $quiz_attempt['percentage'] : ( ! empty( $quiz_attempt['count'] ) ? $quiz_attempt['score'] * 100 / $quiz_attempt['count'] : 0 );
				
				$title .= USIN_Templates::progress_tag($percentage);
				
				
				$quiz_info = array(
					'title' => $title,
					'link' => get_edit_post_link( $quiz->ID, 'usin' )
				);
				
				$list[]=$quiz_info;
			}
			
			$activity = array(
				'type' => 'ld_quizes',
				'for' => 'ld_quizes',
				'label' => sprintf(_n('%s Attempt', '%s Attempts', $count, 'usin'), USIN_LearnDash::get_label('quiz')),
				'count' => $count,
				'list' => $list,
				'icon' => $this->module_name
			);
		}
		
		return $activity;
	}
	
	/**
	 * Adds the quiz activity with passed percentage info
	 * @param  int $user_id the ID of the user
	 * @return array          The original user activity including the quiz activity
	 */
	protected function get_group_activity($user_id){
		$activity = array();
		
		if(function_exists('learndash_get_users_group_ids')){
		
			$groups = learndash_get_users_group_ids($user_id);
			
			if(!empty($groups) && is_array($groups)){
				$count = sizeof($groups);
				$list = array();
				
				foreach ($groups as $group ) {
					$group = get_post( intval($group) );
					
					$group_info = array(
						'title' => $group->post_title,
						'link' => get_edit_post_link( $group->ID, 'usin' )
					);
					
					$list[]=$group_info;
				}
				
				$activity = array(
					'type' => 'ld_groups',
					'for' => 'ld_groups',
					'label' => sprintf(_n('Belongs to 1 Group', 'Belongs to %d Groups', $count, 'usin'), $count),
					'count' => $count,
					'hide_count' => true,
					'list' => $list,
					'icon' => $this->module_name
				);
			}
			
		}
		
		return $activity;
	}

	
}
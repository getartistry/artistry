<?php

class USIN_LearnDash_Query{
	
	protected $has_subscription_status_join_applied = false;
	protected $topic_type = 'topic';
	protected $course_type = 'course';
	protected $lesson_type = 'lesson';
	protected $quiz_type = 'quiz';
	protected $count = 0;
	
	public function __construct(){
		$this->init();
	}
	
	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
		add_filter('usin_custom_query_filter', array($this, 'apply_custom_query_filters'), 10, 2);
	}
	
	public function filter_db_map($db_map){
		global $wpdb;
		$db_map['ld_lessons_completed'] = array('db_ref'=>'lessons_completed', 'db_table'=>'ld_completed', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['ld_topics_completed'] = array('db_ref'=>'topics_completed', 'db_table'=>'ld_completed', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['ld_courses_completed'] = array('db_ref'=>'courses_completed', 'db_table'=>'ld_completed', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['ld_courses_in_progress'] = array('db_ref'=>'courses_in_progress', 'db_table'=>'ld_courses_in_progress', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['ld_quiz_attempts'] = array('db_ref'=>'attempts', 'db_table'=>'ld_quizes', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['ld_quiz_passes'] = array('db_ref'=>'passes', 'db_table'=>'ld_quizes', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['ld_last_activity'] = array('db_ref'=>'last_activity', 'db_table'=>'ld_last_activity', 'set_alias'=>true, 'nulls_last' => true);
		$db_map['ld_has_completed_course'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['ld_has_not_completed_course'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['ld_has_enrolled_course'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['ld_has_not_enrolled_course'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['ld_has_passed_quiz'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['ld_has_not_passed_quiz'] = array('db_ref'=>'', 'db_table'=>'', 'no_select'=>true);
		$db_map['ld_group'] = array('db_ref'=>'ld_group', 'db_table'=>'ld_groups', 'no_select'=>true);
		return $db_map;
	}

	public function filter_query_joins($query_joins, $table){
		global $wpdb;
		
		if($table =='ld_completed'){
			$query_joins.= " LEFT JOIN (
				SELECT user_id,
				SUM(CASE WHEN activity_type = '$this->lesson_type' THEN 1 ELSE 0 END) AS lessons_completed,
				SUM(CASE WHEN activity_type = '$this->course_type' THEN 1 ELSE 0 END) AS courses_completed,
				SUM(CASE WHEN activity_type = '$this->topic_type' THEN 1 ELSE 0 END) AS topics_completed
				FROM ".$this->get_ld_table_name()."
				WHERE activity_status=1 AND activity_type IN ('$this->lesson_type', '$this->course_type', '$this->topic_type')
				GROUP BY user_id
				) AS ld_completed ON $wpdb->users.ID = ld_completed.user_id";
		}elseif($table =='ld_courses_in_progress'){
			$query_joins.= " LEFT JOIN (
				SELECT user_id, COUNT(activity_id) as courses_in_progress
				FROM ".$this->get_ld_table_name()."
				WHERE activity_status=0 AND activity_type = '$this->course_type'
				GROUP BY user_id
				) AS ld_courses_in_progress ON $wpdb->users.ID = ld_courses_in_progress.user_id";
		}elseif($table == 'ld_quizes'){
			$query_joins.= " LEFT JOIN (
				SELECT user_id,
				COUNT(activity_id) as attempts,
				SUM(CASE WHEN activity_status = 1 THEN 1 ELSE 0 END) AS passes
				FROM ".$this->get_ld_table_name()."
				WHERE activity_type = '$this->quiz_type'
				GROUP BY user_id
			)  AS ld_quizes ON $wpdb->users.ID = ld_quizes.user_id";
		}elseif($table == 'ld_last_activity'){
			$query_joins.= " LEFT JOIN (
				SELECT user_id, MAX(from_unixtime(activity_updated)) AS last_activity
				FROM ".$this->get_ld_table_name()."
				GROUP BY user_id
			)  AS ld_last_activity ON $wpdb->users.ID = ld_last_activity.user_id";
		}
		return $query_joins;
	}
	
	public function apply_custom_query_filters($custom_query_data, $filter){
		global $wpdb;
		$ref = 'ldr_'.++$this->count;
		
		if($filter->by == 'ld_has_completed_course' || $filter->by == 'ld_has_not_completed_course'){
			
			$custom_query_data['joins'] .= $wpdb->prepare(" LEFT JOIN
				(SELECT user_id, post_id FROM ".$this->get_ld_table_name()." WHERE post_id = %d 
				AND activity_status = 1 AND activity_type = '$this->course_type'
				GROUP BY user_id) AS $ref ON $wpdb->users.ID = $ref.user_id", $filter->condition);
			
			$operator = $filter->by == 'ld_has_completed_course' ? 'IS NOT NULL' : 'IS NULL';
			$custom_query_data['where'] = " AND $ref.post_id $operator";
			
		}elseif($filter->by == 'ld_has_enrolled_course' || $filter->by == 'ld_has_not_enrolled_course' ){
			
			$custom_query_data['joins'] .= $wpdb->prepare(" LEFT JOIN
				(SELECT user_id, post_id FROM ".$this->get_ld_table_name()." WHERE post_id = %d 
				AND activity_type = '$this->course_type'
				GROUP BY user_id) AS $ref ON $wpdb->users.ID = $ref.user_id", $filter->condition);
			
			$operator = $filter->by == 'ld_has_enrolled_course' ? 'IS NOT NULL' : 'IS NULL';
			$custom_query_data['where'] = " AND $ref.post_id $operator";
			
		}elseif($filter->by == 'ld_has_passed_quiz' || $filter->by == 'ld_has_not_passed_quiz'){
			
			$custom_query_data['joins'] .= $wpdb->prepare(" LEFT JOIN
				(SELECT user_id, post_id FROM ".$this->get_ld_table_name()." WHERE post_id = %d 
				AND activity_status = 1 AND activity_type = '$this->quiz_type'
				GROUP BY user_id) AS $ref ON $wpdb->users.ID = $ref.user_id", $filter->condition);
			
			$operator = $filter->by == 'ld_has_passed_quiz' ? 'IS NOT NULL' : 'IS NULL';
			$custom_query_data['where'] = " AND $ref.post_id $operator";
		}elseif($filter->by == 'ld_group'){
			$custom_query_data['joins'] .= $wpdb->prepare(" LEFT JOIN
				$wpdb->usermeta AS $ref ON $wpdb->users.ID = $ref.user_id AND $ref.meta_key = %s ", 'learndash_group_users_'.$filter->condition);
			
			$operator = $filter->operator == 'include' ? 'IS NOT NULL' : 'IS NULL';
			$custom_query_data['where'] = " AND $ref.meta_value $operator";
		}
	
		return $custom_query_data;
	}
	
	

	protected function get_ld_table_name(){
		global $wpdb;
		
		return $wpdb->prefix.'learndash_user_activity';
	}

	
}
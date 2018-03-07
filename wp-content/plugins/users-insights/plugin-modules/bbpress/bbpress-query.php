<?php

class USIN_bbPress_Query{

	public function init(){
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_join_table', array($this, 'filter_query_joins'), 10, 2);
	}


	public function filter_db_map($db_map){
		$db_map['forums'] = array('db_ref'=>'forums', 'db_table'=>'bb_posts', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['topics'] = array('db_ref'=>'topics', 'db_table'=>'bb_posts', 'null_to_zero'=>true, 'set_alias'=>true);
		$db_map['replies'] = array('db_ref'=>'replies', 'db_table'=>'bb_posts', 'null_to_zero'=>true, 'set_alias'=>true);
		return $db_map;
	}
	
	public function filter_query_joins($query_joins, $table){
		global $wpdb;
		if($table == 'bb_posts'){
			
			$allowed_statuses = USIN_Helper::get_allowed_post_statuses('sql_string');
			if(!empty($allowed_statuses)){
				$where = "WHERE post_status IN ($allowed_statuses) ";
			}
			
			$query_joins .= " LEFT JOIN (SELECT post_author, ".
				"SUM(CASE WHEN post_type = 'forum' THEN 1 ELSE 0 END) AS forums, ". 
				"SUM(CASE WHEN post_type = 'topic' THEN 1 ELSE 0 END) AS topics, ". 
				"SUM(CASE WHEN post_type = 'reply' THEN 1 ELSE 0 END) AS replies ".
				"FROM $wpdb->posts ".$where."GROUP BY post_author) bb_posts ".
			"ON $wpdb->users.ID = bb_posts.post_author";
		}
		return $query_joins;
	}
}
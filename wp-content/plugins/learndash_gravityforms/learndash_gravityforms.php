<?php
/**
 * @package LearnDash GravityForms Addon
 * @version 2.0
 */
/*
Plugin Name: LearnDash GravityForms Addon
Plugin URI: http://www.learndash.com
Description: LearnDash GravityForms Addon 
Version: 2.0
Author: LearnDash
Author URI: http://www.learndash.com
*/

class learndash_gravityforms {
	public $debug = false;
	
	function __construct() {
		add_action("gform_userregistration_feed_settings_fields", array($this, "add_section"), 10, 3);
		add_action("gform_user_registered", array($this, "completed_registration"), 10, 4);
		add_action("gform_user_updated", array($this, "completed_registration"), 10, 4);
	}
	function add_section($fields, $form, $is_validation_error) {
		$courses = $this->list_courses();
		
		$f = array();
		foreach ($fields as $key => $value) {
			$f[$key] = $value;
			if($key == "additional_settings")
			{
				$f['learndash_settings'] = array(
					'title'			=> __("LearnDash Settings", "learndash_settings"),
					'description'	=> '',
					'fields'		=> array()
				);
			}
		}
		$fields = $f;


		$fields['learndash_settings']['fields'][] = array(
		        'name'      => 'gf_user_registration_ldcourses',
		        'label'     => __( 'Courses', 'learndash_gravityforms' ),
		        'type'      => 'checkbox',
		        'choices'   => $courses,
		       // 'tooltip' => sprintf( '<h6>%s</h6> %s', __( 'Tooltip Header', 'my-text-domain' ), __( 'This is the tooltip description', 'my-text-domain' ) ),
		    );

		$accesslevels = $this->list_accesslevels();
		if(!empty($accesslevels)) {
			$fields['learndash_settings']['fields'][] = array(
			        'name'      => 'gf_user_registration_ldaccess',
			        'label'     => __( 'Access Levels', 'learndash_gravityforms' ),
			        'type'      => 'select',
			        'choices'   => $accesslevels,
			      //  'tooltip' => sprintf( '<h6>%s</h6> %s', __( 'Tooltip Header', 'my-text-domain' ), __( 'This is the tooltip description', 'my-text-domain' ) ),
			    );
		}
		return $fields;
	}

	function debug($msg) {
		if(!isset($_GET['debug']) && !$this->debug)
			return;

		$original_log_errors = ini_get('log_errors');
		$original_error_log = ini_get('error_log');
		ini_set('log_errors', true);
		ini_set('error_log', dirname(__FILE__).DIRECTORY_SEPARATOR.'debug.log');
		
		global $ld_sf_processing_id;
		if(empty($ld_sf_processing_id))
		$ld_sf_processing_id	= time();
		
		error_log("[$ld_sf_processing_id] ".print_r($msg, true)); //Comment This line to stop logging debug messages.
		//echo "<pre>"; print_r($msg); echo "</pre>";

		ini_set('log_errors', $original_log_errors);
		ini_set('error_log', $original_error_log);		
	}
	function completed_registration($user_id, $config, $entry, $user_pass) {
			$this->debug($config);

			$courses = $config['meta']['gf_user_registration_ldcourses'];						
			$this->debug("Checking Course Access [gf_user_registration_ldcourses]:".print_r($courses, true));
			
			if(is_array($courses))
			{
				foreach($courses as $courseid => $enabled) {
					if(empty($enabled))
						continue;

					$this->debug("Updating Course Access: Course ID:".$courseid." User ID:".$user_id);
					if(function_exists('ld_update_course_access'))
					{
						$meta = ld_update_course_access($user_id, $courseid);
						
						if(isset($meta['sfwd-courses_course_access_list']))
						$this->debug('Updated Course Access List: '. print_r($meta['sfwd-courses_course_access_list'], true));
						else
						$this->debug('Error: Updated but empty Course Access List for Course ID:'.$course_id);
					}
					else
					{
						$this->debug("LearnDash LMS not installed or not compatible. function ld_update_course_access does not exist.");						
					}
						
				}
			}
			
			$accesslevel = $config['meta']['gf_user_registration_ldaccess'];
			$this->debug("Checking Access Levels[gf_user_registration_ldaccess]:".print_r($accesslevel, true));
			if(!empty($accesslevel))
			{
				$now = time();
				$payment = array('amount' => '-',
							'currency' => " FORM",
							'timestamp' => $now
							);
							
				if(function_exists('learndash_plus_update_user_level'))
				{
					$this->debug("Adding Access Level:".$accesslevel." to User ID:".$user_id." with details:".print_r($payment, true));
					learndash_plus_update_user_level($user_id, $accesslevel, $payment);
				}
				else
				{
					$this->debug("LearnDash Access not installed or not compatible. function learndash_plus_update_user_level does not exist.");
				}
			}
	}
	
	function list_courses() {
		global $post;
		$postid = $post->ID;
		query_posts( array( 'post_type' => 'sfwd-courses', 'posts_per_page' => -1 ) );
		$courses = array();
		while ( have_posts() ) {
			the_post(); 
			$courses[] = array(
					"label" => get_the_title(),
					"value" => 1,
					"name"	=> "gf_user_registration_ldcourses[".get_the_ID()."]",
				);
		}
		wp_reset_query();
		$post = get_post($postid);
		return $courses;
	}
	function list_accesslevels() {
		$access = array();
		if(function_exists('learndash_plus_get_levels'))
		{
			$accesslevels = learndash_plus_get_levels();
			if(empty($accesslevels[0])) {
				$access[0] = array(
					"label" => __("Don't Assign", "learndash_gravityforms"),
					"value"	=> 0,
					"name"	=> "gf_user_registration_ldaccess",
				);
			}
			
			foreach($accesslevels as $id=>$v) {
				$access[] = array(
						"label"	=> $v["name"],
						"value"	=> $id, 
						"name"	=> "gf_user_registration_ldaccess",
					);
			}
		}
		return $access;
	}
}

new learndash_gravityforms();

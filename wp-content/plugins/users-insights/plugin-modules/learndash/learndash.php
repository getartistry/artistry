<?php

if(!defined( 'ABSPATH' )){
	exit;
}

/**
 * LearnDash support for Users Insights.
 * Provides data and filters about the LearnDash user activity, such as
 * courses/lessons completed and quizes passed.
 */
class USIN_LearnDash extends USIN_Plugin_Module{
	
	protected $module_name = 'learndash';
	protected $plugin_path = 'sfwd-lms/sfwd_lms.php';
	protected static $statuses = null;
	protected $course_post_type = 'sfwd-courses';
	protected $quiz_post_type = 'sfwd-quiz';
	protected $groups_post_type = 'groups';
	protected $required_version = '2.3';
	protected $upgrade_notice_set = false;

	
	/**
	 * Overwrites the parent method to also check if the required version of
	 * LearnDash is installed.
	 */
	protected function is_module_active(){
		return parent::is_module_active() && $this->is_required_version_installed();
	}
	
	protected function is_required_version_installed(){
		if(defined('LEARNDASH_VERSION') && version_compare(LEARNDASH_VERSION, $this->required_version, '<')){
			add_action('admin_notices', array($this, 'add_upgrade_notice'));
			return false;
		}
		return true;
	}
	
	public function init(){
		require_once 'learndash-query.php';
		require_once 'learndash-user-activity.php';
		
		new USIN_LearnDash_Query();
		new USIN_LearnDash_User_Activity($this->module_name);
	}
	
	
	public function add_upgrade_notice(){
		if(!$this->upgrade_notice_set){
			$message = __( 'The <b>LearnDash Module</b> of Users Insights requires LearnDash 2.3 or newer. 
				Please update LearnDash to the latest version and run the data upgrade as explained in 
				<a href="https://usersinsights.com/introducing-learndash-integration/">this article</a>.', 'usin' );
			printf( '<div class="notice notice-error"><p>%s</p></div>', $message );
			
			$this->upgrade_notice_set = true;
		}
	}


	public function register_module(){
		return array(
			'id' => $this->module_name,
			'name' => __('LearnDash', 'usin'),
			'desc' => __('Detects the LearnDash user activity and makes it available in the user table and filters.', 'usin'),
			'allow_deactivate' => true,
			'buttons' => array(
				array('text'=> __('Learn More', 'usin'), 'link'=>'https://usersinsights.com/learndash-search-filter-user-data/', 'target'=>'_blank')
			),
			'active' => false
		);
	}

	/**
	 * Registers the module fields
	 * @return array the LearnDash module fields
	 */
	public function register_fields(){
		$fields = array();
		
		//register the numeric fields
		$numeric_fields = array(
			array(
				'name' => sprintf( _x( '%s Completed', 'Lessons Completed Label', 'usin' ), self::get_label( 'lessons' ) ), 
				'id' => 'ld_lessons_completed'
			),
			array(
				'name' => sprintf( _x( '%s Completed', 'Topics Completed Label', 'usin' ), self::get_label( 'topics' ) ),
				'id' => 'ld_topics_completed'
			),
			array(
				'name' => sprintf( _x( '%s Completed', 'Courses Completed Label', 'usin' ), self::get_label( 'courses' ) ), 
				'id' => 'ld_courses_completed'
			),
			array(
				'name' => sprintf( _x( '%s In Progress', 'Courses Started Label', 'usin' ), self::get_label( 'courses' ) ), 
				'id' => 'ld_courses_in_progress'
			),
			array(
				'name' => sprintf(__('%s Attempts', 'usin'), self::get_label('quiz')),
				'id' => 'ld_quiz_attempts'
			),
			array(
				'name' => sprintf(__('%s Passes', 'usin'), self::get_label('quiz')), 
				'id' => 'ld_quiz_passes'
			)
		);
		
		foreach ($numeric_fields as $field ) {
			$fields[]= $this->build_numeric_field($field['name'], $field['id']);
		}
		
		
		$fields[]=array(
			'name' => __('Last Activity', 'usin'),
			'id' => 'ld_last_activity',
			'order' => 'DESC',
			'show' => true,
			'fieldType' => 'general',
			'filter' => array(
				'type' => 'date'
			),
			'module' => $this->module_name
		);
		
		$course_options = $this->get_filter_options($this->course_post_type);
		$quiz_options = $this->get_filter_options($this->quiz_post_type);
		
		//register the filter fields
		$filter_fields = array(
			array('name'=>__('Has enrolled in course', 'usin'), 'id' => 'ld_has_enrolled_course', 'options' => $course_options),
			array('name'=>__('Has not enrolled in course', 'usin'), 'id' => 'ld_has_not_enrolled_course', 'options' => $course_options),
			array('name'=>__('Has completed course', 'usin'), 'id' => 'ld_has_completed_course', 'options' => $course_options),
			array('name'=>__('Has not completed course', 'usin'), 'id' => 'ld_has_not_completed_course', 'options' => $course_options),
			array('name'=>__('Has passed quiz', 'usin'), 'id' => 'ld_has_passed_quiz', 'options' => $quiz_options),
			array('name'=>__('Has not passed quiz', 'usin'), 'id' => 'ld_has_not_passed_quiz', 'options' => $quiz_options)
		);
			
		foreach ($filter_fields as $field ) {
			$fields[]= $this->build_filter_field($field['name'], $field['id'], $field['options']);
		}
		
		//group field
		$group_opions = $this->get_filter_options($this->groups_post_type);
		if(sizeof($group_opions)>0){
			$fields []=array(
				'name' => __('Group', 'usin'),
				'id' => 'ld_group',
				'order' => false,
				'show' => false,
				'fieldType' => $this->module_name,
				'hideOnTable' => true,
				'filter' => array(
					'type' => 'include_exclude_is',
					'options' => $group_opions
				),
				'module' => $this->module_name
			);
		}

		return $fields;
	}
	
	/**
	 * Retrieves the label of an item. If a custom label has been set in the
	 * LeanDash options, the label will be returned. Otherwise the default name
	 * will be returned.
	 * @param  string $item item name such as course, quiz, etc.
	 * @return string       the label
	 */
	public static function get_label($item){
		$label = '';
		
		if(method_exists('LearnDash_Custom_Label', 'get_label')){
			$label = LearnDash_Custom_Label::get_label($item);
		}
		
		if(empty($label)){
			$label = ucfirst($item);
		}
		
		return $label;
	}
	
	protected function get_filter_options($post_type){
		$options = array();
		$posts = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => -1 ) );

		foreach ($posts as $post) {
			$options[] = array('key'=>$post->ID, 'val'=>$post->post_title);
		}

		return $options;
	}
	
	
	protected function build_numeric_field($name, $id){
		return array(
			'name' => $name,
			'id' => $id,
			'order' => 'DESC',
			'show' => true,
			'fieldType' => $this->module_name,
			'filter' => array(
				'type' => 'number',
				'disallow_null' => true
			),
			'module' => $this->module_name
		);
	}
	
	protected function build_filter_field($name, $id, $options){
		return array(
			'name' => $name,
			'id' => $id,
			'order' => 'ASC',
			'show' => false,
			'hideOnTable' => true,
			'fieldType' => $this->module_name,
			'filter' => array(
				'type' => 'select_option',
				'options' => $options
			),
			'module' => $this->module_name
		);
	}
	
	
}

new USIN_LearnDash();
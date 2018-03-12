<?php

// Retrieve the defined learndash courses
function dlk_get_courses() {
	return 
		get_posts(
			array(
				'orderby' => 'title',
				'order' => 'ASC',
				'post_type'   => 'sfwd-courses'
			)
		);
}

// Return an array of course ids and titles suitable for use in course_id select boxes
function dlk_get_course_select_options() {
	$courses = array('all'=>'Current Course');
	$courses +=
		array_map('esc_html__',
			wp_list_pluck(
				dlk_get_courses(), 
				'post_title', 
				'ID'
			)
		);
	return $courses;
}

function dlk_get_categories($taxonomy = 'category') {
	$cats = 
		get_categories(
			array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'taxonomy' => $taxonomy
			)
		);
	if (is_wp_error($cats)) { return array(); }
	return $cats;		
}

function dlk_get_category_select_options($taxonomy = 'category') {
	$cats = dlk_get_categories($taxonomy);
	$select = array('all'=>'Any');
	$select += array_map('esc_html__', wp_list_pluck($cats, 'name', 'cat_ID'));	
	return $select;
}

function dlk_get_tags($taxonomy = 'post_tag') {
	$tags = 
		get_terms(
			array(
				'orderby' => 'name',
				'order'   => 'ASC',
				'taxonomy' => $taxonomy
			)
		);
	if (is_wp_error($tags)) { return array(); }
	return $tags;		
}

function dlk_get_tag_select_options($taxonomy = 'post_tag') {
	$tags = dlk_get_tags($taxonomy);
	$select = array('all'=>'Any');
	$select += array_map('esc_html__', wp_list_pluck($tags, 'name', 'term_id'));	
	return $select;
}


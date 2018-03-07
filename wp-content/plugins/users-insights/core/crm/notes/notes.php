<?php

/**
 * Inits the main Notes functionality.
 */
class USIN_Notes{
	
	/**
	 * Registers the requied hooks.
	 */
	public function init(){
		add_action( 'init', array($this, 'register_post_type') );
		add_filter( 'usin_exclude_post_types', array($this , 'exclude_post_types'));
	}
	
	/**
	 * Registers a note post type.
	 */
	public function register_post_type(){
		$args = array(
	      'public' => false,
	      'label'  => __('Notes', 'usin')
	    );
	    register_post_type( USIN_Note::get_post_type(), $args );
	}
	
	/**
	 * Excludes the note post type from the general Users Insights post query.
	 * @param  array $exclude an array containing the post types to exclude
	 * @return array          the initial array including the note post type
	 */
	public function exclude_post_types($exclude){
		$exclude[]=USIN_Note::get_post_type();
		return $exclude;
	}
}
<?php

function usin_module_options(){
	return USIN_Module_Options::get_instance();
}

function usin_manager(){
	return USIN_Manager::get_instance();
}

function usin_options(){
	return usin_manager()->options;
}

function usin_is_a_users_insights_page(){
	$current_screen = get_current_screen();
	if(!is_admin() || !isset($current_screen->base)){
		return false;
	}
	
	$manager = usin_manager();
	$page_slugs = array($manager->list_page->slug, $manager->module_page->slug, $manager->cf_page->slug);

	foreach($page_slugs as $slug){
		if(strpos( $current_screen->base, $slug ) !== false){
			return true;
		}
	}
	return false;
}
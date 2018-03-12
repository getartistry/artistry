<?php
class DLK_Builder_Module_Template extends ET_Builder_Module {
	
	function init() {}
	
	// Define for earlier Divis
	function video_background($args=array(), $base_name='background') {
		return is_callable('parent::video_background')?parent::video_background($args, $base_name):'';
	}
	
	// Define for earlier Divis
	function get_parallax_image_background($base_name='background') {
		return is_callable('parent::get_parallax_image_background')?parent::get_parallax_image_background($base_name):'';
	}
}
//new DLK_Builder_Module_Template;
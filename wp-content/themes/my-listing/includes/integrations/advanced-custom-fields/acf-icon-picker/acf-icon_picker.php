<?php

if( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists('acf_plugin_icon_picker') ) {

	class acf_plugin_icon_picker {

		function __construct()
		{
			$this->settings = array(
				'version'	=> '1.0.0',
				'url'		=> plugin_dir_url( __FILE__ ),
				'path'		=> plugin_dir_path( __FILE__ )
			);

			add_action('acf/include_field_types', function() {
				include_once('fields/acf-icon_picker-v5.php');
			});
		}
	}

	new acf_plugin_icon_picker();

}
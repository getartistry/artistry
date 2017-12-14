<?php 
if (!defined('ABSPATH')) { exit(); } // No direct access

add_action('load-post-new.php', 'db136_load_post_new_php'); 

function db136_load_post_new_php() { 
	add_filter('et_builder_always_enabled', '__return_true');
}; 